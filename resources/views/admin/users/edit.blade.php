<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Edit User'])

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #d4af37;">Edit User</h1>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-white shadow-md rounded-lg p-6">
                        <form action="{{ route('admin.users.update', $user) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                                <input type="text" name="name" id="name"
                                    class="w-full border rounded py-2 px-3 text-gray-700"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                                <input type="email" name="email" id="email"
                                    class="w-full border rounded py-2 px-3 text-gray-700"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="block text-gray-700 font-bold mb-2">Password (Leave blank
                                    to keep unchanged)</label>
                                <input type="password" name="password" id="password"
                                    class="w-full border rounded py-2 px-3 text-gray-700">
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm
                                    Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full border rounded py-2 px-3 text-gray-700">
                            </div>
                            <div class="mb-4">
                                <label for="role" class="block text-gray-700 font-bold mb-2">Role</label>
                                <select name="role" id="role"
                                    class="w-full border rounded py-2 px-3 text-gray-700" required>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                        User</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="foto" class="block text-gray-700 font-bold mb-2">Profile Photo</label>
                                @if ($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}"
                                        class="w-24 h-24 rounded-full object-cover mb-2">
                                @endif
                                <input type="file" name="foto" id="foto"
                                    class="w-full border rounded py-2 px-3 text-gray-700">
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('admin.users.index') }}"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancel</a>
                                <button type="submit"
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                    style="background-color: #d4af37;">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
