<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Total Revenue: Last 7 Days') }}
        </h2>
    </x-slot> --}}

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @livewire('total-revenue-chart')
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $products = \App\Models\Product::all();
            @endphp
                @foreach ($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div> --}}
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Our Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
          @php
            $products = \App\Models\Product::all();
          @endphp
          @foreach ($products as $product)
            @include('components.product-card', ['product' => $product])
          @endforeach
        </div>
      </div>

</x-app-layout>
