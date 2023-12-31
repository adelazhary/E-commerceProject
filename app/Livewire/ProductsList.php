<?php

namespace App\Livewire;

use App\Models\category;
use App\Models\Country;
use App\Models\product;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
class ProductsList extends Component
{
    use WithPagination;
    public array $categories = [], $countries = [];
    public string $sortColumn, $sortDirection;
    public array $selected = [];
    public function mount(): void
    {
        $this->categories = category::pluck('name', 'id')->toArray();
        $this->countries = Country::pluck('name', 'id')->toArray();
        $this->sortColumn = 'products.name';
        $this->sortDirection = 'asc';
    }
    public array $searchColumns = [
        'name' => '',
        'price' => ['', ''],
        'description' => '',
        'category_id' => 0,
        'country_id' => 0,
    ];
    public function updatingSearchColumns(): void
    {
        $this->resetPage();
    }

    public function deleteProduct($productId): void
    {
        if (is_array($productId)) {
            $this->selected = $productId;
        } else {
            $this->selected = [$productId];
        }

        $products = product::whereIn('id', $this->selected)->get();
        if ($products == null || $products->isEmpty()) {
            session()->flash('message', 'There are No Selected Product found!');
        } else {
            $products->each->delete();
            $this->reset('selected');
            session()->flash('message', 'Product deleted successfully!');
        }
    }
    public function sortByColumn($column): void
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->reset('sortDirection');
            $this->sortColumn = $column;
        }
    }
    public function getSelectedCountProperty(): int
    {
        return count($this->selected);
    }
    public function deleteSelected(): void
    {
        $products = Product::with('orders')->whereIn('id', $this->selected)->get();
        foreach ($products as $product) {
            if ($product->orders()->exists()) {
                $this->addError("orderexist", "Product <span class='font-bold'>{$product->name}</span> cannot be deleted, it already has orders");
                return;
            }
        }
        $products->each->delete();

        // if ($products->orders()->exists()) {
        //     $this->addError('orderexist', 'This product cannot be deleted, it already has orders');
        //     return;

        // $this->reset('selected');
    }
    protected $queryString = [
        'sortColumn' => [
            'except' => 'products.name'
        ],
        'sortDirection' => [
            'except' => 'asc',
        ],
    ];

    #[Title('Products List')]
    public function render()
    {
        $products = Product::query()
            ->select(['products.*', 'countries.id as countryId', 'countries.name as countryName',])
            ->join('countries', 'countries.id', '=', 'products.country_id')
            ->with('categories');

        foreach ($this->searchColumns as $column => $value) {
            if (!empty($value)) {
                $products->when($column == 'price', function ($products) use ($value) {
                    if (is_numeric($value[0])) {
                        $products->where('products.price', '>=', $value[0] * 10000);
                    }
                    if (is_numeric($value[1])) {
                        $products->where('products.price', '<=', $value[1] * 10000);
                    }
                })
                    ->when($column == 'category_id', fn ($products) => $products->whereRelation('categories', 'id', $value))
                    ->when($column == 'country_id', fn ($products) => $products->whereRelation('country', 'id', $value))
                    ->when($column == 'name', fn ($products) => $products->where('products.' . $column, 'LIKE', '%' . $value . '%'));
            }
        }
        $products->orderBy($this->sortColumn, $this->sortDirection);
        return view('livewire.products-list', [
            'products' => $products->paginate(10),
        ]);
    }
}
