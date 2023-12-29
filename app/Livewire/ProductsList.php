<?php

namespace App\Livewire;

use App\Models\category;
use App\Models\Country;
use App\Models\product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsList extends Component
{
    use WithPagination;
    public array $categories = [], $countries = [];
    public function mount(): void
    {
        $this->categories = category::pluck('name', 'id')->toArray();
        $this->countries = Country::pluck('name', 'id')->toArray();
    }

    #[Computed('products')]
    public function getProductsProperty()
    {
        return;
    }
    public array $searchColumns = [
        'name' => '',
        'price' => ['', ''],
        'description' => '',
        'category_id' => 0,
        'country_id' => 0,
    ];
    public function render()
    {
        $products = Product::query()
            ->select(['products.*', 'countries.id as countryId', 'countries.name as countryName',])
            ->join('countries', 'countries.id', '=', 'products.country_id')
            ->with('categories');
            // dd($products->categories);
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
                    ->when($column == 'category_id', fn($products) => $products->whereRelation('categories', 'id', $value))
                    ->when($column == 'country_id', fn($products) => $products->whereRelation('country', 'id', $value))
                    ->when($column == 'name', fn($products) => $products->where('products.' . $column, 'LIKE', '%' . $value . '%'));
                }
            }
        return view('livewire.products-list', [
            'products' => $products->paginate(10),
        ]);
    }
}
