<?php

namespace App\Livewire;

use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];

    public function render()
    {
        return view('livewire.cart');
    }
    public function addToCart($productId, $quantity = 1)
    {
        $cart = session()->get('cart', []);

        if (array_key_exists($productId, $cart)) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'quantity' => $quantity,
                // Add other product details like name, price, etc.
            ];
        }

        session()->put('cart', $cart);

        $this->emit('cartUpdated'); // Emit event for potential updates
    }
    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]);
        }

        session()->put('cart', $cart);

        $this->emit('cartUpdated'); // Emit event for potential updates
    }

    public function updateCartQuantity($productId, $quantity)
    {
        $cart = session()->get('cart', []);

        if (array_key_exists($productId, $cart)) {
            $cart[$productId]['quantity'] = max(0, $quantity); // Ensure non-negative quantity
        }

        session()->put('cart', $cart);

        $this->emit('cartUpdated'); // Emit event for potential updates
    }
    public function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            // Calculate total based on product price and quantity
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
