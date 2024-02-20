<?php

namespace App\Livewire;

use App\Models\category;
use App\Models\Country;
use App\Models\discount;
use App\Models\product;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class ProductForm extends Component
{
    use WithFileUploads;
    public $title;
    #[Validate('required|string|min:3|max:255|unique:products,name')]
    public string $name;

    #[Validate('required|string|min:3|max:255')]
    public string $description;

    #[Validate('required|numeric')]
    public int $price;

    #[Validate('integer|exists:countries,id|nullable|required')]
    public int $country_id;
    #[Validate()]
    public $category_id;
    #[Validate('boolean|default:false')]
    public $is_available, $is_in_stock, $amount_in_stock, $modified_at;
    #[Validate('required')]
    public int $discount_id;
    public bool $editing = false;
    public array $categories = [];
    public array $listsForFields = [];
    public $image;
    protected function rules(): array
    {
        return [
                'name' => 'required|string|min:3|max:255|unique:products,name',
                'description' => 'required|string|min:3|max:255',
                'price' => 'required|numeric|default:0',
                'country_id' => 'required|integer|exists:countries,id|nullable',
                'categories' => 'required|array|required',
                'discount_id' => 'required|integer|exists:discounts,id|nullable',
                'image' => 'image|max:1024|mimes:png,jpg,jpeg,gif,svg|nullable',
                'category_id' => 'required|integer|exists:categories,id|nullable',
                'is_available' => 'boolean|default:false',
                'is_in_stock' => 'boolean|default:false',
                'amount_in_stock' => 'integer|default:0',
                'modified_at' => 'date|default:now()',
        ];
    }
    public function mount(): void
    {
        $this->initListsForFields();
        $this->editing = isset($this->id);
        $this->description = '';
        $this->price = 0;
        $this->country_id = 0;
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
        try {

            $product = product::updateOrCreate(['id' => $this->id], [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'country_id' => $this->country_id,
                'discount_id' => $this->discount_id,
                'is_available' => true,
                'is_active' => true,
                'is_in_stock' => true,
                'amount_in_stock' => 0,
                'modified_at' => now(),
                'image' => 'products/1.jpg'
            ]);
            dd($product);

            $product->name = $this->name;
            $product->description = strip_tags($this->description);
            $product->price = $this->price;
            $product->country_id = $this->country_id;
            $product->discount_id = $this->discount_id;

            $product->save();
            $product->categories()->attach($this->categories);
            dd($product);
            // Save product
            if ($this->image) {
                $imagePath = $this->image->store('products', 'public');
                // Assuming there's an 'image' column in your 'products' table
                $product->image = $imagePath;
                $product->save();
            }
            session()->flash('message', 'Product successfully created.');
            return redirect()->route('products.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong');
            Log::error($e);
        }
    }
}
