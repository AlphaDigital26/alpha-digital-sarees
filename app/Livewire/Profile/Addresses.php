<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Address;

class Addresses extends Component
{
    public $addresses;
    public $showForm = false;
    public $editingId = null;

    // Form fields
    public $first_name;
    public $last_name;
    public $company;
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
        $this->addresses = auth('customer')->user()->addresses()->orderBy('is_default', 'desc')->get();
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function editAddress($id)
    {
        $address = Address::where('customer_id', auth('customer')->id())->findOrFail($id);
        
        $this->editingId = $address->id;
        $this->first_name = $address->first_name;
        $this->last_name = $address->last_name;
        $this->company = $address->company;
        $this->address_1 = $address->address_1;
        $this->address_2 = $address->address_2;
        $this->city = $address->city;
        $this->province = $address->province;
        $this->postal_code = $address->postal_code;
        $this->phone = $address->phone;
        $this->is_default = $address->is_default;

        $this->showForm = true;
    }

    public function deleteAddress($id)
    {
        $address = Address::where('customer_id', auth('customer')->id())->findOrFail($id);
        $address->delete();
        $this->loadAddresses();
        session()->flash('success', 'Address deleted successfully.');
    }

    public function saveAddress()
    {
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'address_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = auth('customer')->user();

        // If setting as default, unset others
        if ($this->is_default) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'company' => $this->company,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'city' => $this->city,
            'province' => $this->province,
            'country' => 'India',
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'is_default' => $this->addresses->count() === 0 ? true : $this->is_default, // Force default if it's the first one
        ];

        if ($this->editingId) {
            $address = Address::where('customer_id', $customer->id)->findOrFail($this->editingId);
            $address->update($data);
            session()->flash('success', 'Address updated successfully.');
        } else {
            $customer->addresses()->create($data);
            session()->flash('success', 'Address added successfully.');
        }

        $this->resetForm();
        $this->loadAddresses();
        $this->showForm = false;
    }

    private function resetForm()
    {
        $this->reset(['editingId', 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'province', 'postal_code', 'phone', 'is_default']);
    }

    public function render()
    {
        return view('livewire.profile.addresses')->layout('components.profile-layout');
    }
}
