<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class LoginPopup extends Component
{
    public $step = 1;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $remember = false;
    public $redirectUrl = '/';
    
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $dob = '';
    public $gender = '';
    public $subscribe = false;
    public $agree_tos = false;

    public $otp_code = '';

    public function checkEmail()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $customer = Customer::where('email', $this->email)->first();

        if ($customer) {
            if (is_null($customer->email_verified_at)) {
                $this->sendOtp($customer);
                $this->step = 7; // Go directly to OTP verification step
            } else {
                $this->step = 2; // Sign in
            }
        } else {
            $this->step = 3; // Register
        }
    }

    public function authenticate()
    {
        $executed = \Illuminate\Support\Facades\RateLimiter::attempt(
            'login-attempts:'.$this->email,
            5,
            function() {
                $this->validate([
                    'password' => 'required',
                ]);

                $customer = Customer::where('email', $this->email)->first();

                if ($customer && \Illuminate\Support\Facades\Hash::check($this->password, $customer->password)) {
                    // Age restriction check
                    if ($customer->dob && \Carbon\Carbon::parse($customer->dob)->age < 18) {
                        $this->addError('email', 'You must be at least 18 years old to log in to this website.');
                        return;
                    }

                    if (is_null($customer->email_verified_at)) {
                        $this->sendOtp($customer);
                        $this->step = 7; // Go to OTP verification step
                        return;
                    }

                    Auth::guard('customer')->login($customer, $this->remember);
                    
                    // Merge guest cart and wishlist
                    \App\Services\CartService::mergeGuestCartToCustomer();
                    \App\Services\WishlistService::mergeGuestWishlistToCustomer();
                    
                    $this->redirectUrl = session()->pull('url.intended', request()->header('Referer') ?? '/');
                    session()->regenerate();
                    $this->processPendingWishlist();
                    $this->step = 4;
                } else {
                    $this->addError('password', 'Incorrect password. Please try again.');
                }
            }
        );

        if (! $executed) {
            $this->addError('email', 'Too many login attempts. Please try again later.');
        }
    }

    public function sendResetLink()
    {
        $this->validate(['email' => 'required|email']);
        
        $status = \Illuminate\Support\Facades\Password::broker('customers')->sendResetLink(
            ['email' => $this->email]
        );

        if ($status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            $this->step = 6; 
        } else {
            $this->addError('email', __($status));
        }
    }

    public function saveDetails()
    {
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|numeric|digits:10|unique:customers,phone',
            'password' => 'required|string|min:6|confirmed',
            'dob' => 'required|date|before_or_equal:-18 years',
            'gender' => 'required|string',
            'agree_tos' => 'accepted', 
        ], [
            'phone.unique' => 'This phone number is already registered.',
            'dob.before_or_equal' => 'You must be at least 18 years old to create an account.',
        ]);

        $customer = Customer::create([
            'name' => trim($this->first_name . ' ' . $this->last_name), 
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => \Illuminate\Support\Facades\Hash::make($this->password),
            'dob' => $this->dob,
            'gender' => $this->gender,
            'is_subscribed' => $this->subscribe ? true : false,
            'agreed_to_tos' => true,
        ]);

        $this->sendOtp($customer);
        $this->step = 7; // Go to OTP verification step
    }

    public function sendOtp(Customer $customer)
    {
        $otp = (string) rand(100000, 999999);
        $customer->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($customer->email)->send(new \App\Mail\CustomerOtpMail($otp, $customer->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function resendOtp()
    {
        $executed = \Illuminate\Support\Facades\RateLimiter::attempt(
            'resend-otp:'.$this->email,
            1,
            function() {
                $customer = Customer::where('email', $this->email)->first();
                if ($customer) {
                    $this->sendOtp($customer);
                    session()->flash('otp_message', 'A new OTP has been sent to your email.');
                    $this->dispatch('otp-resent');
                }
            },
            30
        );

        if (! $executed) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn('resend-otp:'.$this->email);
            $this->addError('otp_code', "Please wait {$seconds} seconds before requesting a new OTP.");
        }
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $customer = Customer::where('email', $this->email)->first();

        if (!$customer) {
            $this->addError('otp_code', 'Customer not found.');
            return;
        }

        if ($customer->otp !== $this->otp_code) {
            $this->addError('otp_code', 'Invalid verification code.');
            return;
        }

        if ($customer->otp_expires_at && $customer->otp_expires_at->isPast()) {
            $this->addError('otp_code', 'This verification code has expired. Please request a new one.');
            return;
        }

        $customer->update([
            'email_verified_at' => now(),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        Auth::guard('customer')->login($customer, $this->remember);
        
        // Merge guest cart and wishlist
        \App\Services\CartService::mergeGuestCartToCustomer();
        \App\Services\WishlistService::mergeGuestWishlistToCustomer();
            
        $this->redirectUrl = session()->pull('url.intended', request()->header('Referer') ?? '/');
        session()->regenerate();
        $this->processPendingWishlist();

        $this->step = 4;
    }

    private function processPendingWishlist()
    {
        if (session()->has('pending_wishlist_item')) {
            $productId = session()->pull('pending_wishlist_item');
            
            \App\Services\WishlistService::add($productId);
            session()->flash('success', 'Added to Wishlist!');
        }
    }

    public function render()
    {
        return view('livewire.auth.login-popup');
    }
}