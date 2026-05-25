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

    public function checkEmail()
    {
        $this->validate([
            'email' => 'required|email',
        ]);

        $customer = Customer::where('email', $this->email)->first();

        if ($customer) {
            $this->step = 2; // Sign in
        } else {
            $this->step = 3; // Register
        }
    }

    public function authenticate()
    {
        $this->validate([
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->redirectUrl = session()->pull('url.intended', request()->header('Referer') ?? '/');
            session()->regenerate();
            $this->processPendingWishlist();
            $this->step = 4;
        } else {
            $this->addError('password', 'Incorrect password. Please try again.');
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
            'dob' => 'required|date',
            'gender' => 'required|string',
            'agree_tos' => 'accepted', 
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

        Auth::guard('customer')->login($customer, $this->remember);
        $this->redirectUrl = session()->pull('url.intended', request()->header('Referer') ?? '/');
        session()->regenerate();
        $this->processPendingWishlist();

        $this->step = 4;
    }

    private function processPendingWishlist()
    {
        if (session()->has('pending_wishlist_item')) {
            $productId = session()->pull('pending_wishlist_item');
            $wishlist = session()->get('wishlist', []);
            
            if (!in_array($productId, $wishlist)) {
                $wishlist[] = $productId;
                session()->put('wishlist', $wishlist);
                session()->flash('success', 'Added to Wishlist!');
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.login-popup');
    }
}