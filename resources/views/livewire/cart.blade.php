<div>
    <div>
        @if (count($cartItems) > 0)
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Price</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item['name'] }}</td>
                            <td class="px-4 py-2">
                                <input wire:model="cartItems.{{ $item['id'] }}.quantity" type="number" min="1"
                                    class="w-20">
                            </td>
                            <td class="px-4 py-2">{{ $item['price'] }}</td>
                            <td class="px-4 py-2">
                                <button wire:click="removeFromCart({{ $item['id'] }})"
                                    class="text-red-500 hover:text-red-700">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="flex justify-end mt-4">
                <p class="text-right">Total: ${{ $this->getCartTotal() }}</p>
            </div>
        @else
            <p>Your cart is empty.</p>
        @endif
    </div>
    <div class="mt-4">
        <a href="{{ route('checkout') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Checkout
        </a>
    </div>
</div>
{{-- Close your eyes. Count to one. That is how long forever feels. --}}
</div>
