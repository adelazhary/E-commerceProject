{{-- resources/views/components/product-card.blade.php --}}

<div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
    <img class="w-full" src="{{ $product->image }}" alt="{{ $product->title }}">
    <div class="p-4">
        <h3 class="font-bold text-xl mb-2">{{ $product->description }}</h3>
        <p class="text-gray-400 text-base">{{ $product->created_at }} - {{ $product->duration }} min</p>
        <div class="flex items-center mt-2">
            <span class="bg-green-500 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $product->price }}</span>
            <span class="text-gray-400 text-xs">{{ $product->type }}</span>
        </div>
    </div>
</div>
