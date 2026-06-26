<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\Address;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class Summary extends BaseCheckoutComponent
{
    public $address;
    public $cartDetails;
    public $showFailureModal = false;
    public $currentOrderId;
    
    // ADDED: Property to hold the customer's special instructions
    public $customer_note = '';

    public function mount()
    {
        $validation = $this->validateCheckout();
        // If validateCheckout returned a redirect, Livewire handles it automatically via the Redirector
        if (!is_array($validation)) {
            return $validation; // though Livewire redirects usually abort
        }
        
        $this->address = $validation['address'];
        $this->cartDetails = $validation['cart'];
    }

    public function payWithRazorpay()
    {
        $executed = RateLimiter::attempt(
            'checkout-payment:'.auth('customer')->id(),
            5, // Allow 5 attempts per minute
            function() {
                $this->showFailureModal = false; // Hide it if they retry
                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
                $totalInPaise = intval($this->cartDetails['total'] * 100); 
                $receiptId = 'ORD-' . strtoupper(Str::random(10));

                $razorpayOrder = $api->order->create([
                    'receipt' => $receiptId, 'amount' => $totalInPaise, 'currency' => 'INR'
                ]);

                $order = \Illuminate\Support\Facades\DB::transaction(function () use ($receiptId, $razorpayOrder) {
                    $createdOrder = Order::create([
                        'order_number' => $receiptId,
                        'customer_id' => auth('customer')->id(),
                        'total_amount' => $this->cartDetails['total'],
                        'status' => 'pending',
                        'razorpay_order_id' => $razorpayOrder['id'],
                        'payment_status' => 'pending',
                        'customer_note' => $this->customer_note, // ADDED: Saving the note to DB
                    ]);

                    foreach ($this->cartDetails['items'] as $item) {
                        \App\Models\OrderItem::create([
                            'order_id' => $createdOrder->id,
                            'product_id' => $item['product']->id,
                            'quantity' => $item['qty'],
                            'price' => $item['product']->current_price,
                        ]);
                    }
                    return $createdOrder;
                });

                $this->currentOrderId = $order->id;

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
        );

        if (! $executed) {
            $this->dispatch('toast', msg: 'Too many payment attempts. Please try again in a minute.', type: 'error');
        }
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

            $order = Order::with('items')->where('razorpay_order_id', $razorpayOrderId)->firstOrFail();
            
            // SECURITY CHECK: VERIFY EXACT AMOUNT PAID vs ORDER TOTAL
            $paymentInfo = $api->payment->fetch($razorpayPaymentId);
            $expectedAmount = intval($order->total_amount * 100);
            
            if ($paymentInfo->amount !== $expectedAmount || $paymentInfo->status !== 'captured') {
                throw new \Exception("Payment amount mismatch or uncaptured. Potential manipulation detected.");
            }

            // DB Transaction to prevent stock race conditions
            $adminEmail = null;
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $razorpayPaymentId, &$adminEmail) {
                $order->update(['status' => 'new', 'payment_status' => 'paid', 'razorpay_payment_id' => $razorpayPaymentId]);

                // Dispatch Emails Config
                $adminEmail = \App\Models\Setting::first()->contact_email ?? config('mail.from.address');
                
                // Deduct stock for each ordered item with Pessimistic Locking
                foreach ($order->items as $item) {
                    // lockForUpdate prevents other transactions from modifying this row until this transaction is complete
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
                        
                        // Low stock alert if stock drops to 5 or below
                        if ($product->stock <= 5 && $adminEmail) {
                            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminLowStockMail($product));
                        }
                    }
                }
            });

            // Customer Emails
            \Illuminate\Support\Facades\Mail::to(auth('customer')->user()->email)->send(new \App\Mail\OrderNotificationMail($order, 'confirmed'));
            \Illuminate\Support\Facades\Mail::to(auth('customer')->user()->email)->send(new \App\Mail\OrderNotificationMail($order, 'payment_success'));
            
            // Admin Email
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\OrderNotificationMail($order, 'admin_new_order'));
            }

            // Clear carts if applicable
            if (session()->has('buy_now_cart')) {
                $buyNowCart = session()->get('buy_now_cart');
                session()->forget('buy_now_cart');
                
                // Remove the purchased item(s) from the main cart so it empties out
                foreach ($buyNowCart as $id => $qty) {
                    \App\Services\CartService::remove($id);
                }
            } else {
                // Clear the whole DB cart for this user
                \App\Models\Cart::where('customer_id', auth('customer')->id())->delete();
            }
            
            session()->forget('checkout_address_id');
            
            return redirect()->route('checkout.success', $order->id); 
        } catch (\Livewire\Features\SupportRedirects\RedirectException $e) {
            // Re-throw Livewire's redirect exception so the redirect actually happens
            throw $e;
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
                \Illuminate\Support\Facades\Mail::to(auth('customer')->user()->email)->send(new \App\Mail\OrderNotificationMail($order, 'failed'));
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