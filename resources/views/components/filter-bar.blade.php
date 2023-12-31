{{-- resources/views/components/filter-bar.blade.php --}}

<div class="bg-gray-800 px-4 py-3 rounded-md">
    <div class="flex items-center justify-between">
        <div class="flex gap-2">
            <input type="text" placeholder="Search..." class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">

            <select class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                <option>Type</option>
                <!-- Add options here -->
            </select>

            <select class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                <option>Genre</option>
                <!-- Add options here -->
            </select>

            <select class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                <option>Country</option>
                <!-- Add options here -->
            </select>

            <select class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                <option>Year</option>
                <!-- Add options here -->
            </select>

            <select class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                <option>Rating</option>
                <!-- Add options here -->
            </select>

            <select class="rounded-md bg-gray-700 text-white focus:ring-2 focus:ring-blue-500">
                <option>Quality</option>
                <!-- Add options here -->
            </select>
        </div>

        <button class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500">
            Filter
        </button>
    </div>
</div>
