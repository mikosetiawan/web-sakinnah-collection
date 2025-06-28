<x-app-layout>
    @include('layouts.navigation', ['currentPage' => 'Transaction History'])
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">{{ session('error') }}</div>
                    @endif

                    @if ($transactions->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500 text-lg">No transactions found.</p>
                            <a href="{{ url('/') }}" class="mt-4 inline-block px-6 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors">
                                Start Shopping
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-3 px-4">Transaction ID</th>
                                        <th class="text-left py-3 px-4">Date</th>
                                        <th class="text-left py-3 px-4">Total Price</th>
                                        <th class="text-left py-3 px-4">Payment Type</th>
                                        <th class="text-left py-3 px-4">Paid Amount</th>
                                        <th class="text-left py-3 px-4">Status</th>
                                        <th class="text-left py-3 px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">#{{ $transaction->id }}</td>
                                            <td class="py-3 px-4">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="py-3 px-4">Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                                    {{ $transaction->payment_type === 'dp' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $transaction->payment_type === 'dp' ? 'Down Payment' : 'Full Payment' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">Rp. {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-{{ $transaction->status_color }}-100 text-{{ $transaction->status_color }}-800">
                                                    {{ $transaction->status_label }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('transactions.show', $transaction) }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-blue-800">Total Transactions</h3>
                                <p class="text-2xl font-bold text-blue-900">{{ $transactions->count() }}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-green-800">Completed</h3>
                                <p class="text-2xl font-bold text-green-900">{{ $transactions->where('status', 'completed')->count() }}</p>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-yellow-800">Pending</h3>
                                <p class="text-2xl font-bold text-yellow-900">{{ $transactions->whereIn('status', ['pending', 'awaiting_remaining'])->count() }}</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h3 class="text-sm font-medium text-purple-800">Total Spent</h3>
                                <p class="text-lg font-bold text-purple-900">Rp. {{ number_format($transactions->where('status', 'completed')->sum('total_price'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>