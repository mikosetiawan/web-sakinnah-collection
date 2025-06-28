<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'List Jasa'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Jasa') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <a href="{{ route('jasas.create') }}"
                        class="inline-block bg-[#d4af37] text-white px-6 py-2 rounded-lg hover:bg-[#b8972e] transition-colors mb-4">Add
                        New Jasa</a>
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-[#d4af37] text-white">
                                    <th class="p-3 text-left">Name</th>
                                    <th class="p-3 text-left">Price</th>
                                    <th class="p-3 text-left">Image</th>
                                    <th class="p-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jasas as $jasa)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="p-3">{{ $jasa->name }}</td>
                                        <td class="p-3">Rp. {{ number_format($jasa->price, 2) }}</td>
                                        <td class="p-3">
                                            @if ($jasa->image)
                                                <img src="{{ asset('storage/' . $jasa->image) }}"
                                                    alt="{{ $jasa->name }}" class="max-w-24">
                                            @else
                                                <span class="text-gray-600">No Image</span>
                                            @endif
                                        </td>
                                        <td class="p-3 flex space-x-2">
                                            <a href="{{ route('jasas.edit', $jasa) }}"
                                                class="bg-[#d4af37] text-white px-3 py-1 rounded hover:bg-[#b8972e] text-sm transition-colors">Edit</a>
                                            <form action="{{ route('jasas.destroy', $jasa) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm transition-colors"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
