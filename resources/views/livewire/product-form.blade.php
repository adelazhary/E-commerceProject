<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $editing ? 'Edit ' . $product->name : 'Create Product' }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="alert alert-error"> <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="mr-2" viewBox="0 0 1792 1792">
                                <path
                                    d="M896 1376l-480-480q-14-14-14-39v-64q0-15 14-39t39-14v-80q0-15 14-39t39-14h64q15 0 39 14t39 14v80q0 15 14 39t14 39v64q0 25-14 39t-39 14l-480 480q-14 14-39 14t-39-14zM1024 256q128 0 224 96t96 224v1024q0 128-96 224t-224 96h-1024q-128 0-224-96t-96-224v-1024q0-128 96-224t224-96h704z" />
                            </svg>
                            <ul class="list-disc pl-4 text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit.prevent="save" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />

                            <x-text-input wire:model.live.defer="name" id="name" class="block mt-1 w-full"
                                type="text" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="image" :value="__('Image')" />
                            <button type="submit">Upload Image</button>
                            <input wire:model="image" type="file" id="image" name="image"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                multiple />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>
                        @if ($image)
                            <div wire:loading wire:target="photo">Uploading...</div>
                            <div class="flex items-center mt-4">
                                @foreach ($image as $index => $item)
                                    <img src="{{ $item->temporaryUrl() }}" alt="image"
                                        class="w-20 h-20 rounded-full mr-4"
                                        style="{{ $index > 0 ? 'transform: translateX(-8px)' : '' }}">
                                @endforeach
                            </div>
                        @endif
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />

                            <div wire:ignore>
                                {{-- <textarea wire:model.defer="description" id="description"></textarea> --}}
                                <textarea wire:model.live.defer="description" data-description="@this" id="description"
                                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">hi</textarea>
                            </div>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Price')" />

                            <x-text-input wire:model.defer="price" type="number" min="0" step="0.01"
                                class="block mt-1 w-full" id="price" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label class="mb-1" for="categories" :value="__('Categories')" />

                            <x-select2 class="mt-1" id="categories" name="categories" :options="$this->listsForFields['categories']"
                                wire:model="categories" multiple />
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label class="mb-1" for="country" :value="__('Country')" />

                            <x-select2 class="mt-1" id="country" name="country" :options="$this->listsForFields['countries']"
                                wire:model="country_id" />
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label class="mb-1" for="discount" :value="__('discount')" />

                            <x-select2 class="mt-1" id="discount" name="discount" :options="$this->listsForFields['discounts']"
                                wire:model="discount_id" multiple />
                            <x-input-error :messages="$errors->get('discount')" class="mt-2" />
                        </div>


                        <div class="mt-4">
                            <x-primary-button type="submit">
                                Save
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script>
        var ready = (callback) => {
            if (document.readyState != "loading") callback();
            else document.addEventListener("DOMContentLoaded", callback);
        }
        ready(() => {
            ClassicEditor
                .create(document.querySelector('#description'))
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        @this.set('description', editor.getData());
                    })
                    Livewire.on('reinit', () => {
                        editor.setData('', '')
                    })
                })
                .catch(error => {
                    console.error(error);
                });
        })
    </script>
@endpush
