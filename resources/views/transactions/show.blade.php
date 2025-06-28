<x-app-layout>
    @include('layouts.navigation', ['currentPage' => 'Detail Transaksi'])
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction Details') }}
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

                    <!-- Transaction Header -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-bold text-gray-900">Transaction #{{ $transaction->id }}</h3>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                bg-{{ $transaction->status_color }}-100 text-{{ $transaction->status_color }}-800">
                                {{ $transaction->status_label }}
                            </span>
                        </div>
                        <p class="text-gray-600">Created on {{ $transaction->created_at->format('F d, Y \a\t H:i') }}</p>
                    </div>

                    <!-- Transaction Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3">Payment Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Price:</span>
                                    <span class="font-semibold">Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Payment Type:</span>
                                    <span class="font-medium">
                                        {{ $transaction->isDownPayment() ? 'Down Payment (50%)' : 'Full Payment' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Paid Amount:</span>
                                    <span class="font-semibold text-green-600">Rp. {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                                </div>
                                @if($transaction->remaining_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Remaining Amount:</span>
                                    <span class="font-semibold text-red-600">Rp. {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-3">Customer Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">{{ $transaction->user->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $transaction->user->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Items -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Ordered Items</h4>
                        <div class="bg-white border rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Item</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Price</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($transaction->items as $item)
                                        <tr>
                                            <td class="py-3 px-4">
                                                <div>
                                                    <p class="font-medium text-gray-900">
                                                        {{ $item->jasa ? $item->jasa->name : $item->barang->name }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $item->jasa ? 'Service' : 'Product' }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 font-medium">
                                                Rp. {{ number_format($item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 px-4">
                                                @if ($item->pickup_date)
                                                    <div class="text-sm">
                                                        <span class="text-gray-600">Pickup:</span><br>
                                                        <span class="font-medium">{{ \Carbon\Carbon::parse($item->pickup_date)->format('F d, Y') }}</span>
                                                    </div>
                                                @elseif ($item->event_date)
                                                    <div class="text-sm">
                                                        <span class="text-gray-600">Event:</span><br>
                                                        <span class="font-medium">{{ \Carbon\Carbon::parse($item->event_date)->format('F d, Y') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">No date specified</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Proofs -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Payment Proofs</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($transaction->payment_proof)
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Initial Payment Proof</h5>
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $transaction->payment_proof) }}" 
                                             alt="Payment Proof" 
                                             class="w-full h-48 object-cover">
                                    </div>
                                </div>
                            @endif

                            @if ($transaction->remaining_payment_proof)
                                <div>
                                    <h5 class="font-medium text-gray-700 mb-2">Remaining Payment Proof</h5>
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $transaction->remaining_payment_proof) }}" 
                                             alt="Remaining Payment Proof" 
                                             class="w-full h-48 object-cover">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Remaining Payment Upload Section -->
                    @if ($transaction->needsRemainingPayment())
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="text-lg font-medium text-yellow-800 mb-2">Remaining Payment Required</h4>
                                    <p class="text-yellow-700 mb-4">
                                        Please transfer the remaining amount of <strong>Rp. {{ number_format($transaction->remaining_amount, 0, ',', '.') }}</strong> to complete your order.
                                    </p>
                                    
                                    <!-- Bank Account Information -->
                                    <div class="bg-white rounded-lg p-4 mb-4">
                                        <h5 class="font-semibold text-gray-800 mb-2">Bank Transfer Details:</h5>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600">Bank:</span><br>
                                                <span class="font-medium">Bank Sakinah</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Account Number:</span><br>
                                                <span class="font-medium">1234-5678-9012-3456</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">Account Name:</span><br>
                                                <span class="font-medium">Sakinah Collection</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Upload Form -->
                                    <form action="{{ route('transactions.uploadRemaining', $transaction) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="remaining_payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                                                Upload Payment Proof
                                            </label>
                                            <input type="file" 
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#d4af37] file:text-white hover:file:bg-[#b8972e]" 
                                                   id="remaining_payment_proof" 
                                                   name="remaining_payment_proof" 
                                                   accept="image/*" 
                                                   required>
                                            @error('remaining_payment_proof')
                                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-[#d4af37] text-white font-semibold rounded-lg hover:bg-[#b8972e] transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            Upload Payment Proof
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t">
                        <a href="{{ route('transactions.history') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to History
                        </a>

                        @if($transaction->isCompleted())
                            <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Transaction Completed
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>