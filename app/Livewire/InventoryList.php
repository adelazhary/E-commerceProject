<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class InventoryList extends Component
{
    #[Title('Inventory List')]
    public function render()
    {
        return view('livewire.inventory-list');
    }
}
