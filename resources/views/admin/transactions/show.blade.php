<x-app-layout>
    <!-- Header -->
    @include('layouts.navigation', ['currentPage' => 'Pemesanan'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Details') }}
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

                    <!-- Transaction Details -->
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Transaction #{{ $transaction->id }}</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Left Column: Transaction Info -->
                        <div>
                            <p class="mb-2"><strong class="text-gray-700">User:</strong>
                                {{ $transaction->user->name }}</p>
                            <p class="mb-2"><strong class="text-gray-700">Email:</strong>
                                {{ $transaction->user->email }}</p>
                            <p class="mb-2"><strong class="text-gray-700">Payment Type:</strong>
                                {{ ucfirst($transaction->payment_type) }}</p>
                            <p class="mb-2"><strong class="text-gray-700">Total Price:</strong> Rp.
                                {{ number_format($transaction->total_price, 2) }}</p>
                            <p class="mb-2"><strong class="text-gray-700">Paid Amount:</strong> Rp.
                                {{ number_format($transaction->paid_amount, 2) }}</p>
                            @if ($transaction->isDownPayment())
                                <p class="mb-2"><strong class="text-gray-700">Remaining Amount:</strong> Rp.
                                    {{ number_format($transaction->remaining_amount, 2) }}</p>
                            @endif
                            <p class="mb-2"><strong class="text-gray-700">Status:</strong>
                                <span
                                    class="inline-block px-2 py-1 text-sm font-semibold rounded 
                                    @if ($transaction->status_color == 'yellow') bg-yellow-100 text-yellow-800
                                    @elseif($transaction->status_color == 'orange') bg-orange-100 text-orange-800
                                    @elseif($transaction->status_color == 'green') bg-green-100 text-green-800
                                    @elseif($transaction->status_color == 'red') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $transaction->status_label }}
                                </span>
                            </p>
                            <p class="mb-2"><strong class="text-gray-700">Created At:</strong>
                                {{ $transaction->created_at->format('d M Y, H:i') }}</p>
                            <p class="mb-2"><strong class="text-gray-700">Updated At:</strong>
                                {{ $transaction->updated_at->format('d M Y, H:i') }}</p>
                        </div>

                        <!-- Right Column: Items -->
                        <div>
                            <p class="mb-2"><strong class="text-gray-700">Items:</strong></p>
                            <ul class="list-disc pl-5 mb-4">
                                @foreach ($transaction->items as $item)
                                    <li class="text-gray-600">
                                        {{ $item->jasa ? $item->jasa->name : $item->barang->name }}
                                        - Rp. {{ number_format($item->price, 2) }}
                                        @if ($item->pickup_date)
                                            <br><span class="text-sm text-gray-500">Pickup:
                                                {{ \Carbon\Carbon::parse($item->pickup_date)->format('d-m-Y') }}</span>
                                        @endif
                                        @if ($item->event_date)
                                            <br><span class="text-sm text-gray-500">Event:
                                                {{ \Carbon\Carbon::parse($item->event_date)->format('d-m-Y') }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Payment Proofs -->
                    <div class="mt-6">
                        @if ($transaction->payment_proof)
                            <p class="mb-2"><strong class="text-gray-700">Initial Payment Proof:</strong></p>
                            <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $transaction->payment_proof) }}"
                                    alt="Initial Payment Proof"
                                    class="max-w-xs sm:max-w-sm mb-4 rounded-md shadow-sm hover:shadow-md transition-shadow">
                            </a>
                        @else
                            <p class="mb-2 text-gray-500">No initial payment proof uploaded.</p>
                        @endif

                        @if ($transaction->remaining_payment_proof)
                            <p class="mb-2"><strong class="text-gray-700">Remaining Payment Proof:</strong></p>
                            <a href="{{ asset('storage/' . $transaction->remaining_payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $transaction->remaining_payment_proof) }}"
                                    alt="Remaining Payment Proof"
                                    class="max-w-xs sm:max-w-sm mb-4 rounded-md shadow-sm hover:shadow-md transition-shadow">
                            </a>
                        @elseif ($transaction->isDownPayment() && $transaction->isAwaitingRemaining())
                            <p class="mb-2 text-gray-500">No remaining payment proof uploaded.</p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex space-x-4">
                        @if ($transaction->isPending())
                            <!-- Approve Button -->
                            <form action="{{ route('admin.transactions.approve', $transaction) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm transition-colors"
                                    onclick="return confirm('Approve this transaction?')">
                                    Approve
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <form action="{{ route('admin.transactions.reject', $transaction) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm transition-colors"
                                    onclick="return confirm('Reject this transaction?')">
                                    Reject
                                </button>
                            </form>
                        @endif

                        @if ($transaction->needsRemainingPayment() && $transaction->remaining_payment_proof)
                            <!-- Complete Button -->
                            <form action="{{ route('admin.transactions.complete', $transaction) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm transition-colors"
                                    onclick="return confirm('Mark this transaction as completed?')">
                                    Complete
                                </button>
                            </form>
                        @endif

                        @if (!$transaction->isCompleted() && !$transaction->isCancelled())
                            <!-- Cancel Button -->
                            <form action="{{ route('admin.transactions.cancel', $transaction) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm transition-colors"
                                    onclick="return confirm('Cancel this transaction?')">
                                    Cancel
                                </button>
                            </form>
                        @endif

                        <!-- Back to Transactions -->
                        <a href="{{ route('admin.transactions.index') }}"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm transition-colors">
                            Back to Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
