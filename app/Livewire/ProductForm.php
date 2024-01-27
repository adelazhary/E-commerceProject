<?php

namespace App\Livewire;

use App\Models\category;
use App\Models\Country;
use App\Models\discount;
use App\Models\inventory;
use App\Models\product;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

class ProductForm extends Component
{
    public $title ;
    #[Validate('required|string|min:3|max:255|unique:products,name')]
    public string $name;

    #[Validate('required|string|min:3|max:255')]
    public string $description;

    #[Validate('required|numeric')]
    public int $price;

    #[Validate('integer|exists:countries,id|nullable|required')]
    public int $country_id;

    #[Validate('required')]
    public int $inventory_id;
    #[Validate()]
    public $category_id;
    #[Validate('required')]
    public int $discount_id;
    public bool $editing = false;
    public array $categories = [];
    public array $listsForFields = [];
    public function mount(): void
    {
        $this->initListsForFields();
        $this->editing = isset($this->id);
        $this->description = '';
        $this->price = 0;
        $this->country_id = 0;
        $this->inventory_id = 0;
        $this->categories = [];
        $this->name = '';
        $this->discount_id = 0;

        if ($this->editing) {
            $this->category_id = $this->category_id ? $this->category_id : null;
            $this->country_id = $this->country_id ? $this->country_id : null;
            $this->categories = $this->categories()->pluck('id')->toArray();
        }
    }
    #[Title('Product Form')]
    public function render()
    {
        return view('livewire.product-form');
    }
    public function initListsForFields(): void
    {
        $this->listsForFields['categories'] = category::active()->pluck('name', 'id')->toArray();
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();
        $this->listsForFields['inventories'] = inventory::pluck('qantity', 'id')->toArray();
        $this->listsForFields['discounts'] = discount::pluck('discount_percent', 'id')->toArray();
    }
    public function updatedCategoryId($value): void
    {
        $this->categories = category::where('id', $value)->pluck('id')->toArray();
    }

    public function updatedCountryId($value): void
    {
        $this->categories = Country::where('id', $value)->pluck('id')->toArray();
    }

    public function updatedProductPrice($value): void
    {
        $this->price = $value * 100;
    }
    public function updatedProductName($value): void
    {
        $this->name = $value;
    }
    public function updatedProductDescription($value): void
    {
        $this->description = $value;
    }
    public function save()
    {
        product::create([
            $this->validate([
                'name' => 'required|string|min:3|max:255|unique:products,name',
                'description' => 'required|string|min:3|max:255',
                'price' => 'required|numeric',
                'country_id' => 'required|integer|exists:countries,id|nullable',
                'categories' => 'required|array',
                'discount_id' => 'required|integer|exists:discounts,id|nullable',
                'inventory_id' => 'required|integer|exists:inventories,id|nullable'
            ]),
            'inventory_id' => 1,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'country_id' => $this->country_id,
            'categories' => $this->categories,
            'discount_id' => 1,
        ]);
        session()->flash('message', 'Product successfully created.');
        return redirect()->route('products.index');
    }
}
