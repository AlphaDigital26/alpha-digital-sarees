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

    public function mount()
    {
        $addressId = session()->get('checkout_address_id');
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

        if (!empty($sessionCart)) {
            $products = Product::whereIn('id', array_keys($sessionCart))->get();
            foreach ($products as $product) {
                $qty = $sessionCart[$product->id] ?? 1;
                $items[] = ['product' => $product, 'qty' => $qty];
                $subtotal += ($product->current_price * $qty); 
            }
        }

        $shipping = ($subtotal > 10000 || $subtotal == 0) ? 0 : 150;
        return ['items' => $items, 'subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $subtotal + $shipping];
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
        ]);

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

            // Deduct stock for each ordered item
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }

            // Clear carts if applicable
            if (session()->has('buy_now_cart')) {
                session()->forget('buy_now_cart');
            } else {
                session()->forget('cart');
            }
            
            session()->forget('checkout_address_id');
            
            return redirect()->route('checkout.success', $order->id); 
        } catch (\Exception $e) {
            $this->paymentFailed();
        }
    }

    // Handles user closing the modal or payment failure
    public function paymentFailed()
    {
        $this->showFailureModal = true;
    }

    public function closeFailureModal()
    {
        $this->showFailureModal = false;
    }

    public function render() { return view('livewire.checkout.summary')->layout('components.layouts.app'); }
}