<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get the current cart identifier (customer_id if logged in, session_id if guest)
     */
    protected static function getIdentifier()
    {
        if (Auth::guard('customer')->check()) {
            return ['customer_id' => Auth::guard('customer')->id()];
        }
        return ['session_id' => Session::getId()];
    }

    /**
     * Merge guest cart to customer cart upon login
     */
    public static function mergeGuestCartToCustomer()
    {
        if (!Auth::guard('customer')->check()) {
            return;
        }

        $customerId = Auth::guard('customer')->id();
        $sessionId = Session::getId();

        $guestCarts = Cart::where('session_id', $sessionId)->get();

        foreach ($guestCarts as $guestCart) {
            $existing = Cart::where('customer_id', $customerId)
                ->where('product_id', $guestCart->product_id)
                ->first();

            if ($existing) {
                // Determine new quantity, respecting product stock
                $product = Product::find($guestCart->product_id);
                $newQty = $existing->quantity + $guestCart->quantity;
                if ($product && $newQty > $product->stock) {
                    $newQty = $product->stock;
                }
                
                $existing->update(['quantity' => $newQty]);
                $guestCart->delete();
            } else {
                $guestCart->update([
                    'customer_id' => $customerId,
                    'session_id' => null
                ]);
            }
        }
        
        // Also merge the old session format if it exists (for backward compatibility during rollout)
        $sessionCart = session()->get('cart', []);
        if (!empty($sessionCart)) {
            foreach ($sessionCart as $productId => $qty) {
                self::add($productId, $qty);
            }
            session()->forget('cart');
        }
    }

    public static function getCart()
    {
        $identifier = self::getIdentifier();
        
        $carts = Cart::where($identifier)
            ->with('product.fabric')
            ->get();

        $items = [];
        $subtotal = 0;
        $totalOriginalPrice = 0;
        $totalItems = 0;

        foreach ($carts as $cart) {
            if (!$cart->product) continue;
            
            $items[$cart->product_id] = [
                'product' => $cart->product,
                'qty' => $cart->quantity,
            ];
            $subtotal += ($cart->product->current_price * $cart->quantity); 
            $totalOriginalPrice += (($cart->product->original_price ?? $cart->product->current_price) * $cart->quantity);
            $totalItems += $cart->quantity;
        }

        $totalDiscount = $totalOriginalPrice - $subtotal;
        $shipping = ($subtotal > 10000 || $subtotal == 0) ? 0 : 150; 

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'totalItems' => $totalItems,
            'totalOriginalPrice' => $totalOriginalPrice,
            'totalDiscount' => $totalDiscount,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    public static function getCartCount()
    {
        $identifier = self::getIdentifier();
        return Cart::where($identifier)->sum('quantity');
    }

    public static function add($productId, $qty = 1)
    {
        $identifier = self::getIdentifier();
        $product = Product::find($productId);
        if (!$product) return false;

        $cart = Cart::where($identifier)->where('product_id', $productId)->first();

        if ($cart) {
            if ($cart->quantity >= $product->stock) {
                return false; // Already at max stock
            }
            $newQty = $cart->quantity + $qty;
            if ($newQty <= $product->stock) {
                $cart->update(['quantity' => $newQty]);
            } else {
                $cart->update(['quantity' => $product->stock]);
            }
        } else {
            if ($product->stock < 1) return false;
            Cart::create(array_merge($identifier, [
                'product_id' => $productId,
                'quantity' => min($qty, $product->stock)
            ]));
        }

        return true;
    }

    public static function incrementQty($productId)
    {
        $identifier = self::getIdentifier();
        $product = Product::find($productId);
        if (!$product) return false;

        $cart = Cart::where($identifier)->where('product_id', $productId)->first();

        if ($cart && $cart->quantity < $product->stock) {
            $cart->increment('quantity');
            return true;
        }
        
        return false;
    }

    public static function decrementQty($productId)
    {
        $identifier = self::getIdentifier();
        $cart = Cart::where($identifier)->where('product_id', $productId)->first();

        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->decrement('quantity');
            } else {
                $cart->delete();
            }
            return true;
        }
        
        return false;
    }

    public static function remove($productId)
    {
        $identifier = self::getIdentifier();
        Cart::where($identifier)->where('product_id', $productId)->delete();
    }
}
