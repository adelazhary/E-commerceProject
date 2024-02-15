<?php

namespace App\Livewire;

use App\Exports\ProductsExport;
use App\Models\category;
use App\Models\Country;
use App\Models\product;
use Illuminate\Http\Response;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ProductsList extends Component
{
    use WithPagination;
    public array $categories = [], $countries = [];
    public string $sortColumn;
    public string $sortDirection;
    public array $selected = [];
    public function mount(): void
    {
        $this->categories = category::pluck('name', 'id')->toArray();
        $this->countries = Country::pluck('name', 'id')->toArray();
        $this->sortColumn = 'products.name';
        $this->sortDirection = 'asc';
        $this->searchColumns['price'] = ['', ''];
        $this->searchColumns['category_id'] = 0;
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
        // dd($productId);
        if (is_array($productId)) {
            $this->selected = $productId;
        } else {
            $this->selected = [$productId];
        }
        // dd($this->selected);
        $products = product::whereIn('id', $this->selected)->get();
        // dd($products);
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

        if ($column === 'products.countryName') {
            $this->sortColumn = 'countries.name'; // Update sortColumn to the correct country name column
        } else {
            $this->sortColumn = $column;
        }

        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
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
    public function export($format): BinaryFileResponse
    {
        abort_if(! in_array($format, ['csv', 'xlsx', 'pdf']), Response::HTTP_NOT_FOUND);

        return Excel::download(new ProductsExport($this->selected), 'products.' . $format);
    }
}
