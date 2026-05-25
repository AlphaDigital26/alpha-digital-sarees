<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserQuery;

class ContactForm extends Component
{
    // These variables match your form inputs
    public $name;
    public $email;
    public $phone;
    public $reason;
    public $message;

    // To show a success message after sending
    public $successMessage = '';

    // Validation rules to ensure we get good data
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'reason' => 'nullable|string|max:255',
        'message' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        // Save to the database
        UserQuery::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'reason' => $this->reason,
            'message' => $this->message,
        ]);

        // Clear the form fields
        $this->reset(['name', 'email', 'phone', 'reason', 'message']);
        
        // Show the success message
        $this->successMessage = 'Thank you! Your inquiry has been sent successfully. We will get back to you soon.';
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}