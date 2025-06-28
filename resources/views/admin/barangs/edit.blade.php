<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Edit Barang/Produk'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Barang') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('barangs.update', $barang) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text"
                                class="block w-full sm:w-96 border-gray-300 rounded-md shadow-sm focus:ring-[#d4af37] focus:border-[#d4af37]"
                                id="name" name="name" value="{{ $barang->name }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea class="block w-full sm:w-96 border-gray-300 rounded-md shadow-sm focus:ring-[#d4af37] focus:border-[#d4af37]"
                                id="description" name="description" rows="4">{{ $barang->description }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price
                                (Rp)</label>
                            <input type="number"
                                class="block w-full sm:w-96 border-gray-300 rounded-md shadow-sm focus:ring-[#d4af37] focus:border-[#d4af37]"
                                id="price" name="price" value="{{ $barang->price }}" step="0.01" required>
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <input type="file"
                                class="block w-full sm:w-96 border-gray-300 rounded-md shadow-sm focus:ring-[#d4af37] focus:border-[#d4af37]"
                                id="image" name="image" accept="image/*">
                            @if ($barang->image)
                                <img src="{{ asset('storage/' . $barang->image) }}" alt="{{ $barang->name }}"
                                    class="mt-2 max-w-24">
                            @endif
                        </div>
                        <button type="submit"
                            class="bg-[#d4af37] text-white px-6 py-2 rounded-lg hover:bg-[#b8972e] transition-colors">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
