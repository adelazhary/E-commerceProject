<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\product;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Volt\Compilers\Mount;

class OrderForm extends Component
{
    public Order $order;
    public Collection $allProducts;
    public array $listsForFields = [];
    public int $taxesPercent = 0;
    public bool $editing = false;
    public array $orderProducts = [];
    public function mount():void
    {
        if ($this->exists) {
            $this->editing = true;
            // $this->orderProducts = $this->order->products->pluck('id')->toArray();
            foreach ($this->products()->get() as $product) {
                $this->orderProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $product->pivot->quantity,
                    'product_name' => $product->name,
                    'product_price' => $product->pivot->price,
                    'is_saved' => true,
                ];
            }
        }else{
            $this->order_date = today();
        }
        $this->initListsForFields();
        $this->taxesPercent = config('app.orders.taxes');

    }

    public function rules(): array
    {
        return [
            'order.user_id' => ['required', 'integer', 'exists:users,id'],
            'order.order_date' => ['required', 'date'],
            'order.subtotal' => ['required', 'numeric'],
            'order.taxes' => ['required', 'numeric'],
            'order.total' => ['required', 'numeric'],
            'orderProducts' => ['array']
        ];
    }
    #[Title('Order Form')]
    public function render()
    {
        $this->order->subtotal = 0;

        foreach ($this->orderProducts as $orderProduct) {
            if ($orderProduct['is_saved'] && $orderProduct['product_price'] && $orderProduct['quantity']) {
                $this->order->subtotal += $orderProduct['product_price'] * $orderProduct['quantity'];
            }
        }

        $this->order->total = $this->order->subtotal * (1 + $this->taxesPercent / 100);
        $this->order->taxes = $this->order->total - $this->order->subtotal;
        return view('livewire.order-form');
    }
    protected function initListsForFields(): void
    {
        $this->listsForFields['users'] = User::pluck('name', 'id')->toArray();

        $this->allProducts = product::all();
    }
    public function addProduct(): void
    {
        foreach ($this->orderProducts as $key => $product) {
            if (!$product['is_saved']) {
                $this->addError('orderProducts.' . $key, 'This line must be saved before creating a new one.');
                return;
            }
        }

        $this->orderProducts[] = [
            'product_id' => '',
            'quantity' => 1,
            'is_saved' => false,
            'product_name' => '',
            'product_price' => 0
        ];
    }
    public function saveOrderProducts(): void
    {
        foreach ($this->orderProducts as $key => $product) {
            if (!$product['is_saved']) {
                $this->saveOrderProduct($key);
            }
        }
    }
    public function saveOrderProduct(int $index): void
    {
        $this->validate([
            'orderProducts.' . $index . '.product_id' => ['required', 'integer', 'exists:products,id'],
            'orderProducts.' . $index . '.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = $this->allProducts->firstWhere('id', $this->orderProducts[$index]['product_id']);

        $this->orderProducts[$index]['is_saved'] = true;
        $this->orderProducts[$index]['product_name'] = $product->name;
        $this->orderProducts[$index]['product_price'] = $product->price;
    }
    public function saveProduct($index): void
    {
        $this->resetErrorBag();
        $product = $this->allProducts->find($this->orderProducts[$index]['product_id']);
        $this->orderProducts[$index]['product_name'] = $product->name;
        $this->orderProducts[$index]['product_price'] = $product->price;
        $this->orderProducts[$index]['is_saved'] = true;
    }
    public function editProduct($index): void
    {
        foreach ($this->orderProducts as $key => $invoiceProduct) {
            if (!$invoiceProduct['is_saved']) {
                $this->addError('$this->orderProducts.' . $key, 'This line must be saved before editing another.');
                return;
            }
        }

        $this->orderProducts[$index]['is_saved'] = false;
    }
    public function removeProduct($index): void
    {
        unset($this->orderProducts[$index]);
        $this->orderProducts = array_values($this->orderProducts);
    }
    public function save()
    {
        $this->validate();

        $this->order->order_date = Carbon::parse($this->order->order_date)->format('Y-m-d');

        $this->order->save();

        $products = [];

        foreach ($this->orderProducts as $product) {
            $products[$product['product_id']] = ['price' => $product['product_price'], 'quantity' => $product['quantity']];
        }

        $this->order->products()->sync($products);

        return redirect()->route('orders.index');
    }
}
