<div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="relative">
            <img class="w-full h-48 object-cover" src="{{ asset('images/DefaultProductImage.jpg') }}" alt="{{ $product->name }}" />
            <div class="absolute top-0 right-0 p-2 bg-gray-800 bg-opacity-50">
                <span class="text-white
                    text-sm font-semibold">{{ $product->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <div class="p-4">
            <h2 class="text-xl font-semibold text-gray-700">{{ $product->name }}</h2>
            <p class="text-base text-gray-500 mt-2">{{ $product->description }}</p>
            <div class="flex items-center mt-4">
                <span class="text-base font-semibold text-green-500">{{ $product->price }}</span>
                <span class="text-sm font-semibold text-gray-500 line-through ml-2">{{ $product->old_price }}</span>
                <span class="text-sm font-semibold text-green-500 ml-2">{{ $product->discount }}</span>
                <span class="text-sm font-semibold text-gray-500 ml-2">{{ $product->discount_type }}</span>
            </div>
            <div class="flex items-center justify-between mt-4">
                <div class="flex items-center">
                    <button
                        class="px-3 py-2 rounded-md bg-gray-200 text-gray-700 text-base font-semibold focus:outline-none">-</button>
                    <span class="px-3 py-2 rounded-md bg-gray-200 text-gray-700 text-base font-semibold">1</span>
                    <button
                        class="px-3 py-2 rounded-md bg-gray-200 text-gray-700 text-base font-semibold focus:outline-none">+</button>
                </div>

                <button
                    class="ml-4 px-3 py-2 rounded-md bg-green-500 text-white text-base font-semibold hover:bg-green-700 focus:outline-none">Add
                    to Cart</button>
            </div>
        </div>
    </div>
</div>
