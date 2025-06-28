<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'List Kelola Users'])

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #d4af37;">Manage Users</h1>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('admin.users.create') }}"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                            style="background-color: #d4af37;">
                            Add New User
                        </a>
                    </div>

                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="py-3 px-6 text-left">Photo</th>
                                    <th class="py-3 px-6 text-left">Name</th>
                                    <th class="py-3 px-6 text-left">Email</th>
                                    <th class="py-3 px-6 text-left">Role</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="border-b">
                                        <td class="py-3 px-6">
                                            @if ($user->foto)
                                                <img src="{{ asset('storage/' . $user->foto) }}"
                                                    alt="{{ $user->name }}"
                                                    class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div
                                                    class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6">{{ $user->name }}</td>
                                        <td class="py-3 px-6">{{ $user->email }}</td>
                                        <td class="py-3 px-6">{{ $user->role }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline ml-4"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
