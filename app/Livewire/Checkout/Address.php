<?php

namespace App\Livewire\Checkout;

use Livewire\Component;

class Address extends Component
{
    public $addresses = [];
    public $selectedAddressId;

    public $showForm = false;
    
    public $first_name;
    public $last_name;
    public $address_1;
    public $address_2;
    public $city;
    public $province;
    public $postal_code;
    public $phone;
    public $is_default = false;

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = auth('customer')->user()->addresses()->get();
        if ($this->addresses->count() > 0 && !$this->selectedAddressId) {
            $default = $this->addresses->where('is_default', true)->first();
            $this->selectedAddressId = $default ? $default->id : $this->addresses->first()->id;
        }
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        $this->resetValidation();
    }

    public function saveAddress()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        if ($this->is_default) {
            auth('customer')->user()->addresses()->update(['is_default' => false]);
        }

        $address = \App\Models\Address::create([
            'customer_id' => auth('customer')->id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,

            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'province' => $this->province,
            'country' => 'India',
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'is_default' => $this->is_default,
        ]);

        $this->selectedAddressId = $address->id;
        $this->showForm = false;
        $this->reset(['first_name', 'last_name', 'address_1', 'address_2', 'city', 'province', 'postal_code', 'phone', 'is_default']);
        $this->loadAddresses();
    }

    public function continueToSummary()
    {
        if (!$this->selectedAddressId) {
            $this->addError('selectedAddressId', 'Please select or add a delivery address.');
            return;
        }

        session()->put('checkout_address_id', $this->selectedAddressId);
        return redirect()->route('checkout.summary');
    }

    public function render()
    {
        return view('livewire.checkout.address')->layout('components.layouts.app');
    }
}