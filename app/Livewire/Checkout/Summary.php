<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\Address;
use Razorpay\Api\Api;
use Illuminate\Support\Str;

class Summary extends Component
{
    public $address;
    public $cartDetails;
    public $showFailureModal = false;
    public $currentOrderId;
    
    // ADDED: Property to hold the customer's special instructions
    public $customer_note = '';

    public function mount()
    {
        $addressId = session()->get('checkout_address_id');
        
        // Auto-select default address if not in session
        if (!$addressId && auth('customer')->check()) {
            $defaultAddress = auth('customer')->user()->addresses()->where('is_default', true)->first() 
                           ?? auth('customer')->user()->addresses()->first();
            if ($defaultAddress) {
                $addressId = $defaultAddress->id;
                session()->put('checkout_address_id', $addressId);
            }
        }

        if (!$addressId) return redirect()->route('checkout.address');
        
        $this->address = Address::find($addressId);
        $this->cartDetails = $this->getCartData();
        
        if (empty($this->cartDetails['items'])) return redirect()->route('cart');
    }

    private function getCartData()
    {
        // Use buy_now_cart if it exists, otherwise use normal cart
        $sessionCart = session()->has('buy_now_cart') 
            ? session()->get('buy_now_cart') 
            : session()->get('cart', []);
            
        $items = [];
        $subtotal = 0;
        $originalPriceTotal = 0;
        $totalItems = 0;

        if (!empty($sessionCart)) {
            $products = Product::whereIn('id', array_keys($sessionCart))->get();
            foreach ($products as $product) {
                $qty = $sessionCart[$product->id] ?? 1;
                $items[] = ['product' => $product, 'qty' => $qty];
                $subtotal += ($product->current_price * $qty); 
                // Fallback to current_price if original_price is null or 0
                $origPrice = $product->original_price > 0 ? $product->original_price : $product->current_price;
                $originalPriceTotal += ($origPrice * $qty);
                $totalItems += $qty;
            }
        }

        $shipping = ($subtotal > 10000 || $subtotal == 0) ? 0 : 150;
        $discount = $originalPriceTotal - $subtotal;

        return [
            'items' => $items, 
            'subtotal' => $subtotal, 
            'original_price_total' => $originalPriceTotal,
            'discount' => $discount,
            'total_items' => $totalItems,
            'shipping' => $shipping, 
            'total' => $subtotal + $shipping
        ];
    }

    public function payWithRazorpay()
    {
        $this->showFailureModal = false; // Hide it if they retry
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $totalInPaise = intval($this->cartDetails['total'] * 100); 
        $receiptId = 'ORD-' . strtoupper(Str::random(10));

        $razorpayOrder = $api->order->create([
            'receipt' => $receiptId, 'amount' => $totalInPaise, 'currency' => 'INR'
        ]);

        $order = Order::create([
            'order_number' => $receiptId,
            'customer_id' => auth('customer')->id(),
            'total_amount' => $this->cartDetails['total'],
            'status' => 'pending',
            'razorpay_order_id' => $razorpayOrder['id'],
            'payment_status' => 'pending',
            'customer_note' => $this->customer_note, // ADDED: Saving the note to DB
        ]);

        $this->currentOrderId = $order->id;

        foreach ($this->cartDetails['items'] as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['qty'],
                'price' => $item['product']->current_price,
            ]);
        }

        $this->dispatch('razorpay-checkout', [
            'key' => env('RAZORPAY_KEY'),
            'amount' => $totalInPaise,
            'order_id' => $razorpayOrder['id'],
            'name' => 'ALPHA DIGITAL',
            'prefill' => [
                'name' => auth('customer')->user()->name,
                'email' => auth('customer')->user()->email,
                'contact' => auth('customer')->user()->phone,
            ]
        ]);
    }

    public function verifyPayment($razorpayPaymentId, $razorpayOrderId, $razorpaySignature)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try {
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ]);

            $order = Order::with('items.product')->where('razorpay_order_id', $razorpayOrderId)->first();
            $order->update(['status' => 'new', 'payment_status' => 'paid', 'razorpay_payment_id' => $razorpayPaymentId]);

            // Dispatch Emails
            $adminEmail = \App\Models\Setting::first()->contact_email ?? config('mail.from.address');
            
            // Deduct stock for each ordered item
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                    
                    // Low stock alert if stock drops to 5 or below
                    if ($item->product->stock <= 5 && $adminEmail) {
                        \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminLowStockMail($item->product));
                    }
                }
            }

            // Customer Emails
            \Illuminate\Support\Facades\Mail::to(auth('customer')->user()->email)->send(new \App\Mail\OrderConfirmedMail($order));
            \Illuminate\Support\Facades\Mail::to(auth('customer')->user()->email)->send(new \App\Mail\PaymentSuccessMail($order));
            
            // Admin Email
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminNewOrderMail($order));
            }

            // Clear carts if applicable
            if (session()->has('buy_now_cart')) {
                $buyNowCart = session()->get('buy_now_cart');
                session()->forget('buy_now_cart');
                
                // Remove the purchased item(s) from the main cart so it empties out
                $mainCart = session()->get('cart', []);
                foreach ($buyNowCart as $id => $qty) {
                    unset($mainCart[$id]);
                }
                session()->put('cart', $mainCart);
            } else {
                session()->forget('cart');
            }
            
            session()->forget('checkout_address_id');
            
            return redirect()->route('checkout.success', $order->id); 
        } catch (\Exception $e) {
            $this->paymentFailed();
        }
    }

    public function paymentFailed()
    {
        if ($this->currentOrderId) {
            $order = Order::find($this->currentOrderId);
            if ($order && $order->status === 'pending') {
                $order->update(['status' => 'failed', 'payment_status' => 'failed']);
                \Illuminate\Support\Facades\Mail::to(auth('customer')->user()->email)->send(new \App\Mail\OrderFailedMail($order));
            }
        }
        $this->showFailureModal = true;
    }

    public function closeFailureModal()
    {
        $this->showFailureModal = false;
    }

    public function render() { return view('livewire.checkout.summary')->layout('components.layouts.app'); }
}