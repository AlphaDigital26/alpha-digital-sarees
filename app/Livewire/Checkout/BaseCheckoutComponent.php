<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use App\Models\Address;

abstract class BaseCheckoutComponent extends Component
{
    /**
     * Get the authenticated customer.
     */
    protected function getCustomer()
    {
        return auth('customer')->user();
    }

    /**
     * Ensure the customer is authenticated.
     */
    protected function ensureAuthenticated()
    {
        if (!auth('customer')->check()) {
            return redirect()->route('login');
        }
    }

    /**
     * Get the default or first address for the customer.
     */
    protected function getDefaultAddress()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return null;
        }

        return $customer->addresses()->where('is_default', true)->first() 
            ?? $customer->addresses()->first();
    }

    /**
     * Get the address stored in the session or fallback to default.
     */
    protected function getCheckoutAddress()
    {
        $addressId = session()->get('checkout_address_id');

        if (!$addressId && $this->getCustomer()) {
            $default = $this->getDefaultAddress();
            if ($default) {
                $addressId = $default->id;
                session()->put('checkout_address_id', $addressId);
            }
        }

        return $addressId ? Address::find($addressId) : null;
    }

    /**
     * Retrieve Cart Data from session/db
     */
    protected function getCartData()
    {
        // Use buy_now_cart if it exists
        if (session()->has('buy_now_cart')) {
            $sessionCart = session()->get('buy_now_cart');
            $items = [];
            $subtotal = 0;
            $originalPriceTotal = 0;
            $totalItems = 0;

            if (!empty($sessionCart)) {
                $products = \App\Models\Product::whereIn('id', array_keys($sessionCart))->get();
                foreach ($products as $product) {
                    $qty = $sessionCart[$product->id] ?? 1;
                    $items[] = ['product' => $product, 'qty' => $qty];
                    $subtotal += ($product->current_price * $qty); 
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

        // Otherwise use regular cart
        $cart = \App\Services\CartService::getCart();
        
        // Reformat items array to match existing structure
        $items = [];
        foreach ($cart['items'] as $item) {
            $items[] = ['product' => $item['product'], 'qty' => $item['qty']];
        }

        return [
            'items' => $items,
            'subtotal' => $cart['subtotal'],
            'original_price_total' => $cart['totalOriginalPrice'],
            'discount' => $cart['totalDiscount'],
            'total_items' => $cart['totalItems'],
            'shipping' => $cart['shipping'],
            'total' => $cart['total']
        ];
    }

    protected function validateCheckout()
    {
        $this->ensureAuthenticated();
        
        $address = $this->getCheckoutAddress();
        if (!$address) {
            return redirect()->route('checkout.address');
        }

        $cart = $this->getCartData();
        if (empty($cart['items'])) {
            return redirect()->route('cart');
        }

        return ['address' => $address, 'cart' => $cart];
    }
}
