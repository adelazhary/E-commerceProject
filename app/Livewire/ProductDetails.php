<?php

namespace App\Livewire;

use App\Models\product;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductDetails extends Component
{

    public $product;

    public function mount($id)
    {
        $this->product = product::where('id', $id)->first();

        if (!$this->product) {
            abort(404);
        }

    }

    public function render()
    {
        return view('livewire.product-details', [
            'product' => $this->product,
        ]);
    }
}
