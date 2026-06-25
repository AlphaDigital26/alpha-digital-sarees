<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if customer already exists by email
            $customer = Customer::where('email', $googleUser->getEmail())->first();

            if (!$customer) {
                // Create a new customer
                $customer = Customer::create([
                    'name' => $googleUser->getName(),
                    'first_name' => $googleUser->user['given_name'] ?? '',
                    'last_name' => $googleUser->user['family_name'] ?? '',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(), // Auto-verify email
                    'password' => \Illuminate\Support\Facades\Hash::make(Str::random(16)), // Fallback password
                    'agreed_to_tos' => true,
                    'is_active' => true,
                ]);
            } else {
                // Update existing customer with Google ID
                $customer->update([
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => $customer->email_verified_at ?? now()
                ]);
            }

            // Log the customer in using your custom guard
            Auth::guard('customer')->login($customer, true);

            // Merge guest cart and wishlist (from your existing logic)
            CartService::mergeGuestCartToCustomer();
            WishlistService::mergeGuestWishlistToCustomer();

            return redirect()->intended('/');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Something went wrong during Google Login.');
        }
    }
}