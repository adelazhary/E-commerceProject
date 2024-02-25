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
    }

    public function render()
    {
        $contents = Storage::get('\livewire-tmp\5jf7iF1dhXytM8yopPrFUpf7axhVTu-metaMTU3MDMwNDEzXzEyODc2ODk4NTgxOTE1Nl85MTE0NDcyMjg1OTE2OTE0NzI1X24uanBn-.jpg');
        // dd($contents);
        return view('livewire.product-details', [
            'product' => $this->product,
        ]);
    }
}
