<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;
use App\Models\Order;
use Razorpay\Api\Api;
use Illuminate\Support\Str;

class Cart extends Component
{
    #[Computed]
    public function cartData()
    {
        $sessionCart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        if (!empty($sessionCart)) {
            $products = Product::with('fabric')->whereIn('id', array_keys($sessionCart))->get();

            foreach ($products as $product) {
                $qty = $sessionCart[$product->id] ?? 1;
                $items[$product->id] = [
                    'product' => $product,
                    'qty' => $qty,
                ];
                $subtotal += ($product->current_price * $qty); 
            }
        }

        $shipping = ($subtotal > 10000 || $subtotal == 0) ? 0 : 150;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    public function incrementQty($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]++;
            session()->put('cart', $cart);
        }
    }

    public function decrementQty($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]);
                session()->flash('success', 'Item removed from cart');
            }
            session()->put('cart', $cart);
        }
    }

    public function removeItem($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            session()->flash('success', 'Item removed from cart');
        }
    }

    public function checkout()
    {
        if (!auth('customer')->check()) {
            $this->dispatch('open-login-modal');
            return;
        }
        
        $cart = $this->cartData();
        if (empty($cart['items'])) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        try {
            // 1. Initialize Razorpay API
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            
            // Razorpay expects amount in paise (multiply by 100)
            $totalInPaise = intval($cart['total'] * 100); 
            $receiptId = 'ORD-' . strtoupper(Str::random(10));

            // 2. Create Razorpay Order
            $razorpayOrder = $api->order->create([
                'receipt'         => $receiptId,
                'amount'          => $totalInPaise,
                'currency'        => 'INR',
            ]);

            // 3. Save "Pending" Order in Database
            $order = Order::create([
                'order_number' => $receiptId,
                'customer_id' => auth('customer')->id(),
                'total_amount' => $cart['total'],
                'status' => 'pending',
                'razorpay_order_id' => $razorpayOrder['id'],
                'payment_status' => 'pending',
            ]);

            // Save order items
            foreach ($cart['items'] as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['qty'],
                    'price' => $item['product']->current_price,
                ]);
            }

            // 4. Dispatch event to open the popup on the frontend
            $this->dispatch('razorpay-checkout', [
                'key' => env('RAZORPAY_KEY'),
                'amount' => $totalInPaise,
                'order_id' => $razorpayOrder['id'],
                'name' => 'ALPHA DIGITAL',
                'description' => 'Premium Indian Handlooms',
                'prefill' => [
                    'name' => auth('customer')->user()->name ?? auth('customer')->user()->first_name,
                    'email' => auth('customer')->user()->email,
                    'contact' => auth('customer')->user()->phone,
                ]
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Network timeout while connecting to secure payment gateway. Please try again.');
        }
    }

    // 5. This method is called from Javascript after a successful payment
    public function verifyPayment($razorpayPaymentId, $razorpayOrderId, $razorpaySignature)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ];

            // Verifies if the payment is legitimate
            $api->utility->verifyPaymentSignature($attributes);

            // Update order status
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();
            if ($order) {
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid',
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);

                // Clear the cart
                session()->forget('cart');

                // Redirect to the order history page
                session()->flash('success', 'Payment successful! Order placed.');
                return redirect()->route('profile.orders'); 
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Payment failed or signature mismatch.');
        }
    }

    public function render()
    {
        return view('livewire.shop.cart');
    }
}