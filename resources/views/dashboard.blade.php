<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Dashboard'])

    <main class="p-4 sm:p-6 flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Welcome, {{ auth()->user()->name }}</h2>

            <!-- Summary Cards -->
            {{-- pus --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
                <!-- Total Pemesanan -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border flex flex-col">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Total Pemesanan</h3>
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">Total Transactions: {{ number_format($totalTransactions) }}</p>
                    <p class="text-gray-600 text-sm sm:text-base">Active Carts: {{ number_format($activeCarts) }}</p>
                    <a href="{{ route('admin.transactions.index') }}"
                       class="mt-4 bg-[#d4af37] text-white px-4 py-2 rounded hover:bg-[#b8972e] text-sm sm:text-base w-full sm:w-auto text-center">
                        View Transactions
                    </a>
                </div>
                <!-- Total Paid -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border flex flex-col">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Total Paid</h3>
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">This Month: Rp {{ number_format($currentMonthRevenue, 2) }}</p>
                    <p class="text-gray-600 text-sm sm:text-base">Growth: {{ $revenueGrowth }}%</p>
                    <a href="{{ route('admin.reports.index') }}"
                       class="mt-4 bg-[#d4af37] text-white px-4 py-2 rounded hover:bg-[#b8972e] text-sm sm:text-base w-full sm:w-auto text-center">
                        View Report
                    </a>
                </div>
                <!-- Total Pending -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border flex flex-col">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Total Pending</h3>
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">Pending: {{ number_format($pendingTransactions) }}</p>
                    <p class="text-gray-600 text-sm sm:text-base">Completed: {{ number_format($completedTransactions) }}</p>
                    <a href="{{ route('admin.transactions.index') }}"
                       class="mt-4 bg-[#d4af37] text-white px-4 py-2 rounded hover:bg-[#b8972e] text-sm sm:text-base w-full sm:w-auto text-center">
                        Manage Transactions
                    </a>
                </div>
            </div>

            <!-- Role-Based Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-4 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row">
                        @if (auth()->user()->role === 'user')
                            <a href="{{ route('cart.index') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                View Cart
                            </a>
                            <a href="{{ route('transactions.history') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                View Transaction History
                            </a>
                        @elseif (in_array(auth()->user()->role, ['admin', 'superadmin']))
                            <a href="{{ route('jasas.index') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                Manage Jasa
                            </a>
                            <a href="{{ route('barangs.index') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                Manage Barang
                            </a>
                            <a href="{{ route('admin.transactions.index') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                Manage Transactions
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                Manage Users
                            </a>
                            <a href="{{ route('admin.reports.index') }}"
                               class="inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors text-center">
                                View Reports
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>