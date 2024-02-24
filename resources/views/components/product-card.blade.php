{{-- resources/views/components/product-card.blade.php --}}
<div class="group relative overflow-hidden rounded-lg shadow-md">
    <img class="w-full h-48 object-cover transition duration-300 group-hover:scale-105" src="{{ $product->image }}" alt="{{ $product->title }}">
    <div class="absolute inset-0 bg-gradient-to-r from-gray-100 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
    <div class="p-4 flex flex-col justify-between text-center">
      <div class="text-lg font-semibold text-gray-800 group-hover:text-white">{{ $product->title }}</div>
      <div class="flex items-center justify-center mt-2">
        <span class="text-green-500 font-semibold text-xl">{{ $product->price }}</span>
        <a href="{{ route('cart.add', $product->id) }}" class="ml-4 inline-flex items-center px-3 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-700">
          Add to Cart
          <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M10 5a1 1 0 00-1 1v5h-2a1 1 0 00-1-1V4a1 1 0 00-1-1H7.5a1 1 0 000 2H9v1.5a2 2 0 002 2
            M12.5a1 1 0 000 2H15v1.5a1 1 0 001 1V9h1a1 1 0 001-1v-5a1 1 0 00-1-1h-2.5a1 1 0 00-1 1V5.5z" clip-rule="evenodd"></path>
          </svg>
        </a>
      </div>
    </div>
  </div>
