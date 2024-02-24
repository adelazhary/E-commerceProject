<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Laravel Livewire Image Upload Test</h1>

        <div class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-xl font-medium mb-4">Upload Image</h2>

            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="flex flex-col mb-4">
                    <label for="image" class="text-sm font-medium mb-2">Select Image:</label>
                    <input type="file" wire:model="image" class="rounded-md border border-gray-300 p-2">
                    @error('image')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
</div>
