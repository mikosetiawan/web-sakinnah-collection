@props(['currentPage'])

<header class="bg-white shadow p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div class="flex items-center space-x-4 w-full sm:w-auto">
        <button id="open-sidebar" class="md:hidden text-gray-800 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="text-lg sm:text-xl font-semibold text-gray-800 truncate">Halo! 🙌 {{ auth()->user()->name }}</div>
    </div>
    <div class="flex items-center space-x-4 w-full sm:w-auto">
        <div class="relative w-full sm:w-64">
            <input type="text" id="search-menu" placeholder="Search Menu..."
                   class="border border-gray-300 rounded p-2 focus:outline-none focus:border-[#d4af37] w-full text-sm sm:text-base"
                   style="border-color: #d4af37;">
            <div id="search-results" class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-60 overflow-y-auto">
                <!-- Search results will be populated here -->
            </div>
        </div>
        <div class="relative">
            <a href="{{ route('cart.index') }}"
               class="flex items-center space-x-1 text-gray-700 hover:text-[#d4af37] focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                @php
                    $cartCount = App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                @if ($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
        <div class="relative">
            <input type="checkbox" id="profile-toggle" class="hidden peer">
            <label for="profile-toggle"
                   class="flex items-center space-x-2 cursor-pointer focus:outline-none">
                @if (auth()->user()->foto)
                    <img class="w-8 h-8 rounded-full" src="{{ Storage::url(auth()->user()->foto) }}" alt="User">
                @else
                    <img class="w-8 h-8 rounded-full" src="{{ asset('sakinah_gallery/user.png') }}" alt="User">
                @endif
                <span class="text-gray-700 text-sm sm:text-base hidden sm:inline">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </label>
            <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden peer-checked:block">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-gray-700 hover:bg-[#d4af37] hover:text-white text-sm">My Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-[#d4af37] hover:text-white text-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<nav class="bg-white border-t shadow p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <ol class="flex items-center space-x-2">
        <li>
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
        </li>
        <li class="flex items-center">
            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-500">{{ $currentPage ?? '-' }}</span>
        </li>
    </ol>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-menu');
        const searchResults = document.getElementById('search-results');
        const profileBtn = document.getElementById('profile-btn');
        const profileDropdown = document.getElementById('profile-dropdown');

        // Menu items based on user role
        const menuItems = [
            { name: 'Dashboard', route: '{{ route('dashboard') }}' },
            @if (auth()->user()->role === 'user')
                { name: 'View Cart', route: '{{ route('cart.index') }}' },
                { name: 'View Transaction History', route: '{{ route('transactions.history') }}' },
            @elseif (in_array(auth()->user()->role, ['admin', 'superadmin']))
                { name: 'Manage Jasa', route: '{{ route('jasas.index') }}' },
                { name: 'Manage Barang', route: '{{ route('barangs.index') }}' },
                { name: 'Manage Transactions', route: '{{ route('admin.transactions.index') }}' },
                { name: 'Manage Users', route: '{{ route('admin.users.index') }}' },
                { name: 'View Reports', route: '{{ route('admin.reports.index') }}' },
            @endif
        ];

        // Debug: Log menu items to ensure they are populated
        console.log('Menu Items:', menuItems);

        // Search functionality
        searchInput.addEventListener('input', function (e) {
            e.preventDefault(); // Prevent any default behavior
            const query = this.value.trim().toLowerCase();
            console.log('Search Query:', query); // Debug: Log search query
            searchResults.innerHTML = ''; // Clear previous results

            if (query.length > 0) {
                const filteredItems = menuItems.filter(item => item.name.toLowerCase().includes(query));
                console.log('Filtered Items:', filteredItems); // Debug: Log filtered items

                if (filteredItems.length > 0) {
                    searchResults.classList.remove('hidden');
                    filteredItems.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2 text-gray-700 hover:bg-[#d4af37] hover:text-white text-sm cursor-pointer';
                        const link = document.createElement('a');
                        link.href = item.route;
                        link.className = 'block';
                        link.textContent = item.name;
                        div.appendChild(link);
                        searchResults.appendChild(div);
                    });
                } else {
                    searchResults.classList.add('hidden');
                }
            } else {
                searchResults.classList.add('hidden');
            }
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
                console.log('Clicked outside, hiding search results'); // Debug: Log outside click
            }
        });

        // Toggle profile dropdown (unchanged)
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        // Hide profile dropdown when clicking outside (unchanged)
        document.addEventListener('click', function (e) {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    });
</script>