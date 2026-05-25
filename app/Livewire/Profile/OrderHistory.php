<?php

namespace App\Livewire\Profile;

use Livewire\Component;

class OrderHistory extends Component
{
    public function render()
    {
        return view('livewire.profile.order-history')->layout('components.profile-layout');
    }
}
