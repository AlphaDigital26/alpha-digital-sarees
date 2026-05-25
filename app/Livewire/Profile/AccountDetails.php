<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class AccountDetails extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $dob;
    public $phone;
    public $gender;
    public $isEditing = false;
    
    public $change_password = false;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $customer = auth('customer')->user();
        $this->first_name = $customer->first_name;
        $this->last_name = $customer->last_name;
        $this->email = $customer->email;
        $this->dob = $customer->dob;
        $this->phone = $customer->phone;
        $this->gender = $customer->gender;
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function updateProfile()
    {
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:customers,email,' . auth('customer')->id(),
            'phone' => 'nullable|numeric|digits:10|unique:customers,phone,' . auth('customer')->id(),
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
        ];

        if ($this->change_password) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $this->validate($rules);

        $customer = auth('customer')->user();

        if ($this->change_password) {
            if (!\Illuminate\Support\Facades\Hash::check($this->current_password, $customer->password)) {
                $this->addError('current_password', 'The provided password does not match your current password.');
                return;
            }
            $customer->password = \Illuminate\Support\Facades\Hash::make($this->new_password);
        }

        $customer->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => trim($this->first_name . ' ' . $this->last_name),
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'dob' => $this->dob,
        ]);

        $this->change_password = false;
        $this->current_password = null;
        $this->new_password = null;
        $this->new_password_confirmation = null;
        $this->isEditing = false; // Switch back to view mode

        session()->flash('success', 'Account details updated successfully.');
    }

    public function render()
    {
        return view('livewire.profile.account-details')->layout('components.profile-layout');
    }
}
