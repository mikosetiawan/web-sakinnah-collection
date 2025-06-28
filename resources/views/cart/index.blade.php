<x-app-layout>
    @include('layouts.navigation', ['currentPage' => 'Cart'])
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200" role="alert">
                            {{ session('error') }}</div>
                    @endif
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200"
                            role="alert">{{ session('success') }}</div>
                    @endif

                    @if ($carts->isEmpty())
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="mt-2 text-gray-600">Your cart is empty.</p>
                            <a href="{{ url('/') }}"
                                class="inline-block mt-4 px-8 py-3 bg-[#d4af37] text-white font-semibold rounded-lg shadow-md hover:bg-[#b8972e] transition-colors"
                                aria-label="Start shopping">Start to Buy!</a>
                        </div>
                    @else
                        <!-- Cart Items Table -->
                        <div class="mb-6">
                            <div class="overflow-x-auto">
                                <table class="w-full mb-4 border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="text-left p-3 font-semibold text-gray-700">Item</th>
                                            <th class="text-left p-3 font-semibold text-gray-700">Price</th>
                                            <th class="text-left p-3 font-semibold text-gray-700">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carts as $cart)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="p-3">
                                                    {{ $cart->jasa ? $cart->jasa->name : $cart->barang->name }}</td>
                                                <td class="p-3">Rp.
                                                    {{ number_format(($cart->jasa ?? $cart->barang)->price, 2) }}</td>
                                                <td class="p-3">
                                                    <form action="{{ route('cart.remove', $cart) }}" method="POST"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-500 hover:underline focus:outline-none focus:ring-2 focus:ring-red-500 rounded"
                                                            onclick="return confirm('Are you sure you want to remove this item?')"
                                                            aria-label="Remove {{ $cart->jasa ? $cart->jasa->name : $cart->barang->name }} from cart">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p class="text-right font-semibold">Total: Rp.
                                {{ number_format($carts->sum(fn($cart) => ($cart->jasa ?? $cart->barang)->price), 2) }}
                            </p>
                        </div>

                        <!-- Checkout Form -->
                        <form action="{{ route('transactions.checkout') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Date Selection for Items -->
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold mb-3">Set Dates for Items:</h4>
                                @foreach ($carts as $cart)
                                    <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                                        <p class="font-medium">
                                            {{ $cart->jasa ? $cart->jasa->name : $cart->barang->name }}</p>
                                        @if ($cart->jasa)
                                            <label for="event_date_{{ $cart->id }}"
                                                class="block text-sm text-gray-600 mt-2">Event Date:</label>
                                            <input type="date" name="event_date[{{ $cart->id }}]"
                                                id="event_date_{{ $cart->id }}"
                                                class="border rounded p-2 w-full focus:ring-2 focus:ring-[#d4af37]"
                                                required min="{{ now()->addDay()->format('Y-m-d') }}"
                                                max="{{ now()->addYear()->format('Y-m-d') }}"
                                                aria-describedby="event_date_error_{{ $cart->id }}">
                                            @error("event_date.$cart->id")
                                                <div id="event_date_error_{{ $cart->id }}"
                                                    class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                            @enderror
                                        @else
                                            <label for="pickup_date_{{ $cart->id }}"
                                                class="block text-sm text-gray-600 mt-2">Pickup Date:</label>
                                            <input type="date" name="pickup_date[{{ $cart->id }}]"
                                                id="pickup_date_{{ $cart->id }}"
                                                class="border rounded p-2 w-full focus:ring-2 focus:ring-[#d4af37]"
                                                required min="{{ now()->addDay()->format('Y-m-d') }}"
                                                max="{{ now()->addYear()->format('Y-m-d') }}"
                                                aria-describedby="pickup_date_error_{{ $cart->id }}">
                                            @error("pickup_date.$cart->id")
                                                <div id="pickup_date_error_{{ $cart->id }}"
                                                    class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Payment Type Selection -->
                            <div class="mb-6">
                                <label for="payment_type" class="block text-sm font-medium text-gray-700">Payment
                                    Type</label>
                                <select name="payment_type" id="payment_type"
                                    class="border rounded p-2 w-full focus:ring-2 focus:ring-[#d4af37]" required
                                    aria-describedby="payment_type_error">
                                    <option value="dp">Down Payment (50% - Mandatory)</option>
                                    <option value="full">Full Payment</option>
                                </select>
                                @error('payment_type')
                                    <div id="payment_type_error" class="text-red-500 text-sm mt-1">{{ $message }}
                                    </div>
                                @enderror

                                <!-- Display DP Amount -->
                                <div id="dp_amount_container"
                                    class="mt-2 p-2 rounded-lg bg-[#d4af37]/10 border border-[#d4af37]/20"
                                    style="display: none;">
                                    <span id="dp_amount" class="font-semibold text-[#1f2937]"></span>
                                </div>
                            </div>


                            <!-- Payment Proof Upload -->
                            <div class="mb-6">
                                <label for="payment_proof" class="block text-sm font-medium text-gray-700">Upload
                                    Payment Proof</label>
                                <input type="file"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#d4af37] file:text-white hover:file:bg-[#b8972e]"
                                    id="payment_proof" name="payment_proof" accept="image/*" required
                                    aria-describedby="payment_proof_error">
                                @error('payment_proof')
                                    <div id="payment_proof_error" class="text-red-500 text-sm mt-1">{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Bank Account Information -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <h4 class="font-semibold mb-2">Bank Account Information:</h4>
                                <ul class="list-disc pl-5 text-gray-600">
                                    <li><strong>Bank:</strong> Bank Sakinah</li>
                                    <li><strong>Account Number:</strong> 1234-5678-9012-3456</li>
                                    <li><strong>Account Name:</strong> Sakinah Collection</li>
                                </ul>
                            </div>

                            <button type="submit"
                                class="inline-block px-8 py-3 bg-[#d4af37] text-white font-semibold rounded-lg shadow-md hover:bg-[#b8972e] transition-colors focus:ring-2 focus:ring-[#d4af37]"
                                aria-label="Proceed to checkout">Checkout</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (!$carts->isEmpty())
        <script>
            // Existing payment proof validation
            document.getElementById('payment_proof').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Please upload an image file.');
                        e.target.value = '';
                    } else if (file.size > maxSize) {
                        alert('File size exceeds 2MB. Please upload a smaller file.');
                        e.target.value = '';
                    }
                }
            });

            // DP calculation logic
            document.getElementById('payment_type').addEventListener('change', function(e) {
                const paymentType = e.target.value;
                const dpContainer = document.getElementById('dp_amount_container');
                const dpAmount = document.getElementById('dp_amount');
                const totalPrice = {{ $carts->sum(fn($cart) => ($cart->jasa ?? $cart->barang)->price) }};

                if (paymentType === 'dp') {
                    const dpValue = totalPrice * 0.5;
                    const formattedDp = 'Rp. ' + dpValue.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                    dpAmount.textContent = `Down Payment Amount (50%): ${formattedDp}`;
                    dpContainer.style.display = 'block';
                } else {
                    dpContainer.style.display = 'none';
                }
            });

            // Trigger change event on page load to handle default selection
            document.getElementById('payment_type').dispatchEvent(new Event('change'));
        </script>
    @endif

</x-app-layout>
