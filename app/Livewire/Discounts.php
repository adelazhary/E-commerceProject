<?php

namespace App\Livewire;

use App\Models\discount;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
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

    #[Title('Discounts')]
    public function render()
    {
        $discounts = discount::all();
        return view('livewire.discounts', compact('discounts'));
    }
    public function save()
    {
        $this->validate([
            'name' => 'required',
            'discount_percent' => 'required|numeric',
            'active' => 'required',
            'discription' => 'required',
        ]);
        discount::create([
            'name' => $this->name,
            'discount_percent' => $this->discount_percent,
            'active' => $this->active,
            'discription' => $this->discription,
        ]);
        $this->reset();
    }
}
