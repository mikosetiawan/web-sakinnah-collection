<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Reports Transaction'])

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1 class="text-3xl font-bold mb-6 text-center" style="color: #d4af37;">Transaction Report</h1>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-end mb-4 space-x-2">
                        <a href="{{ route('admin.reports.print') }}" target="_blank"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                            style="background-color: #d4af37;">Print</a>
                        <a href="{{ route('admin.reports.exportExcel') }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Export
                            Excel</a>
                        <a href="{{ route('admin.reports.exportPDF') }}"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Export PDF</a>
                    </div>

                    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4" style="color: #d4af37;">Summary</h2>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-gray-100 p-4 rounded">
                                <p class="text-gray-700">Total Users</p>
                                <p class="text-2xl font-bold">{{ $users }}</p>
                            </div>
                            <div class="bg-gray-100 p-4 rounded">
                                <p class="text-gray-700">Total Barangs</p>
                                <p class="text-2xl font-bold">{{ $barangs }}</p>
                            </div>
                            <div class="bg-gray-100 p-4 rounded">
                                <p class="text-gray-700">Total Jasas</p>
                                <p class="text-2xl font-bold">{{ $jasas }}</p>
                            </div>
                            <div class="bg-gray-100 p-4 rounded">
                                <p class="text-gray-700">Total Revenue</p>
                                <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4" style="color: #d4af37;">Transaction Trends</h2>
                        <canvas id="transactionChart" height="100"></canvas>
                        <script>
                            const ctx = document.getElementById('transactionChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: @json($chartData->pluck('month')),
                                    datasets: [{
                                            label: 'Transactions',
                                            data: @json($chartData->pluck('count')),
                                            borderColor: '#d4af37',
                                            fill: false,
                                        },
                                        {
                                            label: 'Revenue (Rp)',
                                            data: @json($chartData->pluck('revenue')),
                                            borderColor: '#1f2937',
                                            fill: false,
                                        }
                                    ]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        </script>
                    </div>

                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="py-3 px-6 text-left">Transaction ID</th>
                                    <th class="py-3 px-6 text-left">User</th>
                                    <th class="py-3 px-6 text-left">Items</th>
                                    <th class="py-3 px-6 text-left">Total Price</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr class="border-b">
                                        <td class="py-3 px-6">{{ $transaction->id }}</td>
                                        <td class="py-3 px-6">
                                            {{ $transaction->user ? $transaction->user->name : 'N/A' }}</td>
                                        <td class="py-3 px-6">
                                            @foreach ($transaction->items as $item)
                                                {{ $item->barang ? $item->barang->name : ($item->jasa ? $item->jasa->name : 'N/A') }}
                                                (Qty: {{ $item->quantity }})<br>
                                            @endforeach
                                        </td>
                                        <td class="py-3 px-6">Rp {{ number_format($transaction->total_price, 2) }}</td>
                                        <td class="py-3 px-6">{{ $transaction->status }}</td>
                                        <td class="py-3 px-6">{{ $transaction->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
