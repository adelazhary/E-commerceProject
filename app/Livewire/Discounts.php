<?php

namespace App\Livewire;

use Livewire\Component;

class Discounts extends Component
{
    public $name;
    public $discount_percent;
    public $active;
    public $discription;
    public $modified_at;
    public function mount()
    {
        $this->name = 'Discount 1';
        $this->discount_percent = 10;
        $this->active = true;
        $this->discription = 'This is a discount';
        $this->modified_at = null;
    }
    public function render()
    {
        return view('livewire.discounts');
    }
}
