<div class="w-full bg-white rounded-lg shadow-md overflow-hidden flex flex-col items-center justify-between p-4">
    <a href="{{ route('product.show', $product->id) }}">
        <img class="w-full h-48 object-cover rounded-t-lg"
            src="{{ storage_path('app/public/products/' . $product->image) }}" alt="{{ $product->title }}">
        {{ Storage::url('products\'' . $product->image) }}
    </a>
    <div class="flex flex-col justify-between p-4">
        <div class="text-lg font-semibold text-gray-800">{{ $product->title }}</div>
        <div class="flex items-center justify-between mt-2 text-gray-500">
            <span>{{ $product->created_at->diffForHumans() }}</span>
            <span class="text-green-500 font-semibold text-xl">{{ $product->price }}</span>
        </div>
        <a href="{{ route('cart.add', $product->id) }}"
            class="mt-4 inline-flex items-center px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-700">
            Add to Cart
            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 5a1 1 0 00-1 1v5h-2a1 1 0 00-1-1V4a1 1 0 00-1-1H7.5a1 1 0 000 2H9v1.5a2 2 0 002 2
          M12.5a1 1 0 000 2H15v1.5a1 1 0 001 1V9h1a1 1 0 001-1v-5a1 1 0 00-1-1h-2.5a1 1 0 00-1 1V5.5z"
                    clip-rule="evenodd"></path>
            </svg>
        </a>
    </div>
</div>
