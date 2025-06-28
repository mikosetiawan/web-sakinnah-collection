<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Pemesanan'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Transactions') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Message -->
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Transactions Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-[#d4af37] text-white">
                                    <th class="p-3 text-left">ID</th>
                                    <th class="p-3 text-left">User</th>
                                    <th class="p-3 text-left">Items</th>
                                    <th class="p-3 text-left">Total Price</th>
                                    <th class="p-3 text-left">Payment Type</th>
                                    <th class="p-3 text-left">Status</th>
                                    <th class="p-3 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="p-3">{{ $transaction->id }}</td>
                                        <td class="p-3">{{ $transaction->user->name }}</td>
                                        <td class="p-3">
                                            @foreach ($transaction->items as $item)
                                                {{ $item->jasa ? $item->jasa->name : $item->barang->name }}
                                                (Rp. {{ number_format($item->price, 2) }})<br>
                                            @endforeach
                                        </td>
                                        <td class="p-3">Rp. {{ number_format($transaction->total_price, 2) }}</td>
                                        <td class="p-3">
                                            {{ ucfirst($transaction->payment_type) }}
                                            @if ($transaction->isDownPayment())
                                                (Remaining: Rp. {{ number_format($transaction->remaining_amount, 2) }})
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <span class="inline-block px-2 py-1 text-sm font-semibold rounded 
                                                @if($transaction->status_color == 'yellow') bg-yellow-100 text-yellow-800
                                                @elseif($transaction->status_color == 'orange') bg-orange-100 text-orange-800
                                                @elseif($transaction->status_color == 'green') bg-green-100 text-green-800
                                                @elseif($transaction->status_color == 'red') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $transaction->status_label }}
                                            </span>
                                        </td>
                                        <td class="p-3 flex space-x-2">
                                            <!-- View Details -->
                                            <a href="{{ route('admin.transactions.show', $transaction) }}"
                                               class="bg-[#d4af37] text-white px-3 py-1 rounded hover:bg-[#b8972e] text-sm transition-colors">
                                                View
                                            </a>

                                            <!-- Approve Button (for Pending or Pending Remaining) -->
                                            @if ($transaction->isPending() || $transaction->isPendingRemaining())
                                                <form action="{{ route('admin.transactions.approve', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm transition-colors"
                                                            onclick="return confirm('Approve this {{ $transaction->isPendingRemaining() ? 'remaining payment' : 'transaction' }}?')">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Reject Button (for Pending or Pending Remaining) -->
                                            @if ($transaction->isPending() || $transaction->isPendingRemaining())
                                                <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm transition-colors"
                                                            onclick="return confirm('Reject this {{ $transaction->isPendingRemaining() ? 'remaining payment' : 'transaction' }}?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Upload Remaining Payment Proof (for Awaiting Remaining) -->
                                            @if ($transaction->needsRemainingPayment())
                                                <form action="{{ route('admin.transactions.uploadRemaining', $transaction) }}" method="POST" enctype="multipart/form-data" class="inline">
                                                    @csrf
                                                    <input type="file" name="remaining_payment_proof" accept="image/*" class="hidden" id="remaining_payment_proof_{{ $transaction->id }}"
                                                           onchange="this.form.submit()" required>
                                                    <label for="remaining_payment_proof_{{ $transaction->id }}"
                                                           class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm transition-colors cursor-pointer">
                                                        Upload Remaining
                                                    </label>
                                                </form>
                                            @endif

                                            <!-- Cancel Button (for Non-Completed/Non-Cancelled) -->
                                            @if (!$transaction->isCompleted() && !$transaction->isCancelled())
                                                <form action="{{ route('admin.transactions.cancel', $transaction) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600 text-sm transition-colors"
                                                            onclick="return confirm('Cancel this transaction?')">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="p-3 text-center text-gray-500">
                                            No transactions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>