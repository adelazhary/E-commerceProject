<div>
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <img class="w-full h-48 object-cover" src="{{ $product->image }}" alt="{{ $product->name }}" />
    <div class="p-4">
      <h2 class="text-xl font-semibold text-gray-700">{{ $product->name }}</h2>
      <p class="text-base text-gray-500 mt-2">{{ $product->description }}</p>
      <div class="flex items-center mt-4">
        <span class="text-base font-semibold text-green-500">{{ $product->price }}</span>
        <button class="ml-4 px-3 py-2 rounded-md bg-green-500 text-white text-base font-semibold hover:bg-green-700 focus:outline-none">Add to Cart</button>
      </div>
    </div>
  </div>

</div>
