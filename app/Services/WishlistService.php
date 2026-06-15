<?php

namespace App\Services;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    /**
     * Get the current wishlist identifier (customer_id if logged in, session_id if guest)
     */
    protected static function getIdentifier()
    {
        if (Auth::guard('customer')->check()) {
            return ['customer_id' => Auth::guard('customer')->id()];
        }
        return ['session_id' => Session::getId()];
    }

    /**
     * Merge guest wishlist to customer wishlist upon login
     */
    public static function mergeGuestWishlistToCustomer()
    {
        if (!Auth::guard('customer')->check()) {
            return;
        }

        $customerId = Auth::guard('customer')->id();
        $sessionId = Session::getId();

        $guestWishlists = Wishlist::where('session_id', $sessionId)->get();

        foreach ($guestWishlists as $guestWishlist) {
            $existing = Wishlist::where('customer_id', $customerId)
                ->where('product_id', $guestWishlist->product_id)
                ->first();

            if ($existing) {
                // If it's already in the customer's wishlist, delete the guest duplicate
                $guestWishlist->delete();
            } else {
                // Otherwise, assign it to the customer
                $guestWishlist->update([
                    'customer_id' => $customerId,
                    'session_id' => null
                ]);
            }
        }
        
        // Merge the old session format if it exists (for backward compatibility during rollout)
        $sessionWishlist = session()->get('wishlist', []);
        if (!empty($sessionWishlist)) {
            foreach ($sessionWishlist as $productId) {
                self::add($productId);
            }
            session()->forget('wishlist');
        }
    }

    public static function getWishlist()
    {
        $identifier = self::getIdentifier();
        
        return Wishlist::where($identifier)
            ->with('product.fabric')
            ->get();
    }

    public static function getWishlistCount()
    {
        $identifier = self::getIdentifier();
        return Wishlist::where($identifier)->count();
    }
    
    public static function getWishlistProductIds()
    {
        $identifier = self::getIdentifier();
        return Wishlist::where($identifier)->pluck('product_id')->toArray();
    }

    public static function add($productId)
    {
        $identifier = self::getIdentifier();
        $product = Product::find($productId);
        if (!$product) return false;

        $exists = Wishlist::where($identifier)->where('product_id', $productId)->exists();

        if (!$exists) {
            Wishlist::create(array_merge($identifier, [
                'product_id' => $productId
            ]));
        }

        return true;
    }

    public static function remove($productId)
    {
        $identifier = self::getIdentifier();
        Wishlist::where($identifier)->where('product_id', $productId)->delete();
    }
    
    public static function toggle($productId)
    {
        $identifier = self::getIdentifier();
        $wishlist = Wishlist::where($identifier)->where('product_id', $productId)->first();
        
        if ($wishlist) {
            $wishlist->delete();
            return false; // Removed
        } else {
            self::add($productId);
            return true; // Added
        }
    }
}
