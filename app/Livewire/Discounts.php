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
    public bool $active;
    public $discription;
    public $modified_at;
    public $showModal = false;
    public function mount()
    {
        $this->name = 'Discount 1';
        $this->discount_percent = 0;
        $this->discription = 'This is a discount';
        $this->modified_at = now();
        $this->showModal = false;
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
            'modified_at' => 'date',
        ]);
        discount::create([
            'name' => $this->name,
            'discount_percent' => $this->discount_percent,
            'active' => $this->active,
            'discription' => $this->discription,
            'modified_at' => $this->modified_at,
        ]);
        $this->reset();
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function edit($id)
    {
        $discount = discount::find($id);
        $this->name = $discount->name;
        $this->discount_percent = $discount->discount_percent;
        $this->active = $discount->active;
        $this->discription = $discount->discription;
        $this->modified_at = $discount->modified_at;
        // $this->showModal = true;
    }
    public function toggleIsActive($id)
    {
        $this->active = discount::where('id', $id)->update([
            'active' => !$this->active,
        ]);
    }
    public function delete($id)
    {
        discount::where('id', $id)->delete();
    }
    public function create()
    {

        $this->reset();
    }
}
