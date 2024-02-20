{{-- resources/views/components/product-card.blade.php --}}

<div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
    <img class="w-full" src="{{ $product->image }}" alt="{{ $product->title }}">
    <div class="p-4">
        <hr class="my-2">
        <div class="flex items-center mt-2">
            <a href="{{ route('product.show', $product->id ) }}" class="text-lg font-semibold text-gray-100">{{ $product->description }}</a>
            <span class="bg-green-500 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">{{ $product->price }}</span>
            <span class="text-gray-400">{{ $product->created_at->diffForHumans() }}</span>
        </div>
        <hr class="my-2">
    </div>
</div>
