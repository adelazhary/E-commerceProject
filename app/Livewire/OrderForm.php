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
    public array $listsForFields = [],
        $orderProducts = [];

    public int $taxesPercent = 0,
        $user_id,
        $total = 0,
        $subtotal = 0,
        $taxes = 0;
    public string $order_date;
    public bool $editing = false;
    public function mount(Order $order): void
    {
        $this->initListsForFields();
        $this->order = $order;
        echo $this->order;
        if (!$this->order) {
            $this->order = $order;
            $this->editing = true;
            $this->user_id = $this->order->user_id;
            $this->order_date = $this->order->order_date;
            $this->subtotal = $this->order->subtotal;
            $this->taxes = $this->order->taxes;
            $this->total = $this->order->total;

            foreach ($this->order->products()->get() as $product) {
                $this->orderProducts[] = [
                    'product_id' => $product->id,
                    'quantity' => $product->pivot->quantity,
                    'product_name' => $product->name,
                    'product_price' => $product->pivot->price,
                    'is_saved' => true,
                ];
            }
        } else {
            $this->order_date = today();
            $this->taxesPercent = config('app.orders.taxes');
        }
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'order_date' => ['required', 'date'],
            'subtotal' => ['required', 'numeric'],
            'taxes' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
            'orderProducts' => ['array'],
        ];
    }
    #[Title('Order Form')]
    public function render()
    {
        $this->calculateTaxesAndTotal();
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
            'product_price' => 0,
        ];
    }
    private function validateOrderProducts()
    {
        $rules = [];
        foreach ($this->orderProducts as $index => $product) {
            // Define validation rules for each order product item
            $rules['orderProducts.' . $index . '.product_id'] = 'required|integer|exists:products,id';
            $rules['orderProducts.' . $index . '.quantity'] = 'required|integer|min:1';
        }
        $this->validate($rules);
    }
    public function saveOrderProducts(): void
    {
        // Validate all order products
        $this->validateOrderProducts();

        // Check if order exists (editing case)
        if (is_null($this->order)) {
            // Create a new order first
            $this->order = Order::create($this->only('user_id', 'order_date', 'subtotal', 'taxes', 'total'));
        }

        // Now you can safely save the order products with a valid order_id
        foreach ($this->orderProducts as $key => $product) {
            if (!$product['is_saved']) {
                $this->saveOrderProduct($key);
            }
        }
    }

    public function saveOrderProduct(int $index): void
    {
        // $this->validate([
        //     'orderProducts.' . $index . '.product_id' => ['required', 'integer', 'exists:products,id'],
        //     'orderProducts.' . $index . '.quantity' => ['required', 'integer', 'min:1'],
        // ]);

        // Get product details from allProducts
        $product = $this->allProducts->firstWhere('id', $this->orderProducts[$index]['product_id']);

        $this->orderProducts[$index]['is_saved'] = true;
        $this->orderProducts[$index]['product_name'] = $product->name;
        $this->orderProducts[$index]['product_price'] = $product->price;

        // Use the existing order object's ID
        $this->order->products()->attach($product->id, [
            'price' => $product->price,
            'quantity' => $this->orderProducts[$index]['quantity'],
        ]);
    }

    public function saveProduct($index): void
    {
        /* The commented out line `// ->validate([` is a method call to validate the data before
        saving it. In Laravel Livewire, the `validate()` method is used to validate the incoming
        data based on the defined validation rules in the `rules()` method of the component. */

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
    public function save(): void
    {
        $this->validate();

        $this->order_date = Carbon::parse($this->order_date)->format('Y-m-d');

        // Save or update the order first
        $this->order = Order::updateOrCreate($this->only('user_id', 'order_date', 'subtotal', 'taxes', 'total'));

        $products = [];

        foreach ($this->orderProducts as $product) {
            $products[$product['product_id']] = ['price' => $product['product_price'], 'quantity' => $product['quantity']];
        }

        $this->order->products()->sync($products);

        $this->redirect(route('orders.index'));
    }


    private function calculateTaxesAndTotal()
    {
        $this->order->subtotal = 0;
        foreach ($this->orderProducts as $orderProduct) {
            if ($orderProduct['is_saved'] && $orderProduct['product_price'] && $orderProduct['quantity']) {
                $this->order->subtotal += $orderProduct['product_price'] * $orderProduct['quantity'];
            }
        }
        $this->order->total = $this->order->subtotal * (1 + $this->taxesPercent / 100);
        $this->order->taxes = $this->order->total - $this->order->subtotal;
    }
}
