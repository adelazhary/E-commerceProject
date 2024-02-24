<?php

namespace App\Livewire;

use App\Models\product;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductDetails extends Component
{

    public $product;
    public $image = 'app/DefaultProductImage.png';

    public function mount($id)
    {
        $this->product = product::where('id', $id)->first();

        if (!$this->product) {
            abort(404);
        }

        $this->image = Storage::get(storage_path('app') . 'DefaultProductImage.jpg');
        // $this->image = $this->product->image ?? Storage::disk('local')->url('app/DefaultProductImage.jpg');
    }

    public function render()
    {
        return view('livewire.product-details', [
            'product' => $this->product,
        ]);
    }
}
