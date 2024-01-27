<?php

namespace App\Livewire;

use App\Models\discount;
use Livewire\Attributes\Title;
use Livewire\Component;

class DiscountForm extends Component
{
    public $editing;
    public $name;
    public $discount_percent;
    public bool $active;
    public $discription;
    public $modified_at;
    public function mount()
    {
        $this->name = 'Discount 1';
        $this->discount_percent = 0;
        $this->discription = 'This is a discount';
        $this->modified_at = now();
    }
    public function rule(){
        return [
            'name' => 'required',
            'discount_percent' => 'required|numeric',
            'discription' => 'required',
        ];
    }
    public function save()
    {
        $this->validate([
            'name' => 'required',
            'discount_percent' => 'required|numeric',
            'discription' => 'required',
        ]);
        discount::create([
            'name' => $this->name,
            'discount_percent' => $this->discount_percent,
            'discription' => $this->discription,
        ]);
        $this->reset();
    }

    #[Title('Discount Form')]
    public function render()
    {
        return view('livewire.discount-form');
    }
}
