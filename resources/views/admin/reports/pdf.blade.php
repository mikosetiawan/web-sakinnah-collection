<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Print PDF'])

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto p-6">
                    <h1>Transaction Report</h1>

                    <div class="summary">
                        <div>
                            <p>Total Users</p>
                            <p class="value">{{ $users }}</p>
                        </div>
                        <div>
                            <p>Total Barangs</p>
                            <p class="value">{{ $barangs }}</p>
                        </div>
                        <div>
                            <p>Total Jasas</p>
                            <p class="value">{{ $jasas }}</p>
                        </div>
                        <div>
                            <p>Total Revenue</p>
                            <p class="value">Rp {{ number_format($totalRevenue, 2) }}</p>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User</th>
                                <th>Items</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->user ? $transaction->user->name : 'N/A' }}</td>
                                    <td>
                                        @foreach ($transaction->items as $item)
                                            {{ $item->barang ? $item->barang->name : ($item->jasa ? $item->jasa->name : 'N/A') }}
                                            (Qty: {{ $item->quantity }})<br>
                                        @endforeach
                                    </td>
                                    <td>Rp {{ number_format($transaction->total_price, 2) }}</td>
                                    <td>{{ $transaction->status }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
