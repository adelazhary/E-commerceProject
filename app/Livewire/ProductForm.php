<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Country;
use App\Models\Discount;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductForm extends Component
{
    use WithFileUploads;

    public string $name;
    public string $description;
    public int $price;
    public int $country_id;
    public array $categories = []; // Removed category_id property
    public int $discount_id;
    public bool $is_available = false;
    public bool $is_in_stock = false;
    public int $amount_in_stock = 0;
    public ?Carbon $modified_at = null;
    public bool $editing = false;
    public array $listsForFields = [];
    public $image;

    protected $rules = [
        'name' => 'required|string|min:3|max:255|unique:products,name',
        'description' => 'required|string|min:3|max:255',
        'price' => 'required|numeric|min:0',
        'country_id' => 'required|integer|exists:countries,id',
        'categories' => 'required|array|min:1', // Updated validation rule
        'discount_id' => 'nullable|integer|exists:discounts,id',
        'image' => 'nullable|image|max:1024|mimes:png,jpg,jpeg,gif,svg',
    ];

    public function mount(): void
    {
        $this->initListsForFields();
        $this->editing = isset($this->id);
        $this->fill([
            'description' => '',
            'price' => 0,
            'country_id' => 0,
            'categories' => [],
            'name' => '',
            'discount_id' => 0,
        ]);

        if ($this->editing) {
            $this->categories = $this->product->categories->pluck('id')->toArray();
        }
    }

    # [Title('Product Form')]
    public function render()
    {
        return view('livewire.product-form');
    }

    public function initListsForFields(): void
    {
        $this->listsForFields['categories'] = Category::active()->pluck('name', 'id')->toArray();
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();
        $this->listsForFields['discounts'] = Discount::pluck('discount_percent', 'id')->toArray();
    }

    public function save()
    {
        $this->validate();

        $product = $this->editing ? Product::find($this->id) : new Product();
        $product->fill([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price * 100, // Convert to cents for storage
            'country_id' => $this->country_id,
            'discount_id' => $this->discount_id,
            'is_available' => $this->is_available,
            'is_in_stock' => $this->is_in_stock,
            'amount_in_stock' => $this->amount_in_stock,
            'modified_at' => Carbon::now(),
        ]);

        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            $product->image = $imagePath;
        }

        $product->save();
        $product->categories()->sync($this->categories); // Sync categories with product

        session()->flash('message', 'Product successfully saved.');
        return redirect()->route('products.index');
    }
}
