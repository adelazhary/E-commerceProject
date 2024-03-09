<?php

namespace App\Livewire;

use App\Models\category;
use App\Models\Country;
use App\Models\discount;
use App\Models\product;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;
    public string $name = '', $description = '';
    public $image = [];
    public ?float $price;
    public ?int $country_id;

    public array $categories = [], $listsForFields = [], $selected = [];
    public ?int $discount_id = 0,
        $amount_in_stock = 0,
        $id;

    public bool $is_available = false,
        $editing = false,
        $is_in_stock = false;
    public ?Carbon $modified_at = null;
    public ?Product $product = null;


    public function mount(product $product): void
    {
        $this->id = $product->id ?? null;
        $this->initListsForFields();
        $this->editing = isset($this->id);

        if (!is_null($this->product)) {
            $this->product = $product;
            $this->editing = true;

            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->price = $this->product->price;
            $this->country_id = $this->product->country_id;

            $this->categories = $this->product->categories()->pluck('id')->toArray();
        }
    }
    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }
    public function render()
    {
        $previewUrls = [];
        if (isset($this->photos)) {
            foreach ($this->photos as $index => $photo) {
                $previewUrls[$index] = $photo->temporaryUrl();
            }
        }
        return view('livewire.product-form', compact('previewUrls'));
    }
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required'],
            'country_id' => ['required', 'integer', 'exists:countries,id'],
            'price' => ['required'],
            'categories' => ['required', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'discount_id' => ['nullable', 'integer', 'exists:discounts,id'],
            'amount_in_stock' => ['required', 'integer'],
            'image.*' => ['nullable', 'image', 'max:1024'],
        ];
    }
    #[On('deleteSelected')]
    public function deleteSelected(): void
    {
        dd($this->selected);
        $products = Product::whereIn('id', $this->selected)->get();

        $products->each->delete();

        $this->reset('selected');
    }
    protected function initListsForFields(): void
    {
        $this->listsForFields['countries'] = Country::pluck('name', 'id')->toArray();
        $this->listsForFields['categories'] = category::active()->pluck('name', 'id')->toArray();
        $this->listsForFields['discounts'] = discount::pluck('discount_percent', 'id')->toArray();
    }
    #[On('delete')]
    public function delete(int $id): void
    {
        $product = Product::findOrFail($id);

        $product->delete();
    }
    public function save()
    {
        $this->validate();

        $product = $this->editing ? Product::findOrFail($this->id) : new Product();
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
            foreach ($this->image as $image) {
                $imagePath = $image->store('products', 'public');
                $product->image = $imagePath;
            }
        }

        $product->save();
        $product->categories()->sync($this->categories); // Sync categories with product

        session()->flash('message', 'Product successfully saved.');
        return redirect()->route('products.index');
    }
}
