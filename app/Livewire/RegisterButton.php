<?php

namespace App\Livewire;

use Livewire\Component;

class RegisterButton extends Component
{
    public function redirectToRegistration()
    {
        return redirect()->route('register');
    }
    public function render()
    {
        return view('livewire.register-button');
    }
}
