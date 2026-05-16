<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class LoginPopup extends Component
{
    public $step = 1;
    public $countryCode = '+91';
    public $phone = '';
    public $otp = '';
    
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $dob = '';
    public $gender = '';
    public $subscribe = false;
    public $agree_tos = false;

    public function sendOtp()
    {
        $this->validate([
            'phone' => 'required|numeric|digits:10',
        ]);

        $generatedOtp = rand(1000, 9999);
        session()->put('login_otp_' . $this->phone, $generatedOtp);
        session()->flash('test_otp', "TEST MODE OTP: " . $generatedOtp);

        $this->step = 2;
    }

    public function verifyOtp()
    {
        $this->validate(['otp' => 'required|numeric|digits:4']);

        $savedOtp = session()->get('login_otp_' . $this->phone);

        if ($this->otp == $savedOtp) {
            $customer = Customer::where('phone', $this->phone)->first();

            if ($customer && $customer->first_name) {
                Auth::guard('customer')->login($customer);
                $this->step = 4;
            } else {
                $this->step = 3;
            }
        } else {
            $this->addError('otp', 'Invalid OTP. Please try again.');
        }
    }

    public function saveDetails()
    {
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'agree_tos' => 'accepted', 
        ]);

        $customer = Customer::updateOrCreate(
            ['phone' => $this->phone],
            [
                'name' => trim($this->first_name . ' ' . $this->last_name), 
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'dob' => $this->dob,
                'gender' => $this->gender,
                'is_subscribed' => $this->subscribe ? true : false,
                'agreed_to_tos' => true,
            ]
        );

        Auth::guard('customer')->login($customer);
        session()->forget('login_otp_' . $this->phone);

        $this->step = 4;
    }

    public function closePopup()
    {
        $this->dispatch('close-login-modal');
        return redirect(request()->header('Referer')); 
    }

    public function render()
    {
        return view('livewire.auth.login-popup');
    }
}