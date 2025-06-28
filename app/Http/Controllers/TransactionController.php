<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Cart;
use App\Models\Jasa;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TransactionController extends Controller
{


    /**
     * Send WhatsApp notification using Twilio
     */
    private function sendWhatsAppNotification($to, $message)
    {
        try {
            $twilioSid = env(key: 'TWILIO_SID');
            $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
            $twilioWhatsAppFrom = env('TWILIO_WHATSAPP_FROM');

            $twilio = new Client($twilioSid, $twilioAuthToken);

            $twilio->messages->create($to, [
                'from' => $twilioWhatsAppFrom,
                'body' => $message,
            ]);

            Log::info('WhatsApp notification sent to ' . $to . ': ' . $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }


    public function checkout(Request $request)
    {
        $carts = Cart::where('user_id', auth()->id())->with(['jasa', 'barang'])->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        $request->validate([
            'payment_type' => 'required|in:dp,full',
            'pickup_date.*' => 'nullable|date|after:today',
            'event_date.*' => 'nullable|date|after:today',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total price
            $totalPrice = 0;
            foreach ($carts as $cart) {
                $item = $cart->jasa ?? $cart->barang;
                $totalPrice += $item->price;
            }

            // Determine payment details
            $paymentType = $request->payment_type;
            $paidAmount = $paymentType === Transaction::PAYMENT_TYPE_DP ? $totalPrice * 0.5 : $totalPrice;
            $status = $paymentType === Transaction::PAYMENT_TYPE_DP ?
                Transaction::STATUS_PENDING :
                Transaction::STATUS_PENDING; // Both DP and full start as pending

            // Store payment proof
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'total_price' => $totalPrice,
                'payment_type' => $paymentType,
                'paid_amount' => $paidAmount,
                'status' => $status,
                'payment_proof' => $paymentProofPath,
            ]);

            // Create transaction items
            foreach ($carts as $cart) {
                $item = $cart->jasa ?? $cart->barang;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'jasa_id' => $cart->jasa_id,
                    'barang_id' => $cart->barang_id,
                    'price' => $item->price,
                    'pickup_date' => $cart->barang ? $request->pickup_date[$cart->id] ?? null : null,
                    'event_date' => $cart->jasa ? $request->event_date[$cart->id] ?? null : null,
                ]);
            }

            // Send WhatsApp notifications
            $user = auth()->user();
            $userPhone = $user->phone_number ?? 'whatsapp:+6285779178942'; // Fallback to test number
            $adminPhone = 'whatsapp:+6285779178942'; // Admin number
            $transactionId = $transaction->id;
            $formattedTotal = number_format($totalPrice, 2, ',', '.');
            $formattedPaid = number_format($paidAmount, 2, ',', '.');

            // User notification
            $userMessage = $paymentType === Transaction::PAYMENT_TYPE_DP
                ? "Assalamualaikum {$user->name},\nTerima kasih telah melakukan transaksi (ID: {$transactionId}).\nPembayaran DP sebesar Rp {$formattedPaid} dari total Rp {$formattedTotal} telah diterima.\nStatus: Menunggu konfirmasi admin.\nKami akan memberitahu Anda setelah dikonfirmasi.\nTerima kasih atas kepercayaannya 🙏"
                : "Assalamualaikum {$user->name},\nTerima kasih telah melakukan transaksi (ID: {$transactionId}).\nPembayaran penuh sebesar Rp {$formattedPaid} telah diterima.\nStatus: Menunggu konfirmasi admin.\nKami akan memberitahu Anda setelah dikonfirmasi.\nTerima kasih atas kepercayaannya 🙏";
            $this->sendWhatsAppNotification($userPhone, $userMessage);

            // Admin notification
            $adminMessage = $paymentType === Transaction::PAYMENT_TYPE_DP
                ? "Notifikasi Transaksi Baru (ID: {$transactionId})\nUser: {$user->name}\nTipe: DP\nTotal: Rp {$formattedTotal}\nDibayar: Rp {$formattedPaid}\nStatus: Menunggu konfirmasi\nSilakan periksa bukti pembayaran di dashboard admin."
                : "Notifikasi Transaksi Baru (ID: {$transactionId})\nUser: {$user->name}\nTipe: Penuh\nTotal: Rp {$formattedTotal}\nDibayar: Rp {$formattedPaid}\nStatus: Menunggu konfirmasi\nSilakan periksa bukti pembayaran di dashboard admin.";
            $this->sendWhatsAppNotification($adminPhone, $adminMessage);


            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            $message = $paymentType === Transaction::PAYMENT_TYPE_DP ?
                'Transaction created successfully! Awaiting admin confirmation for down payment.' :
                'Transaction created successfully! Awaiting admin approval.';

            return redirect()->route('transactions.show', $transaction)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if transaction failed
            if (isset($paymentProofPath) && Storage::disk('public')->exists($paymentProofPath)) {
                Storage::disk('public')->delete($paymentProofPath);
            }

            // Log the error for debugging
            Log::error('Transaction checkout failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('cart.index')->with('error', 'Checkout failed. Please try again. If the problem persists, please contact support.');
        }
    }

    public function show(Transaction $transaction)
    {
        // Check authorization
        if (
            $transaction->user_id !== auth()->id() &&
            !in_array(auth()->user()->role ?? '', ['admin', 'superadmin'])
        ) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load(['items.jasa', 'items.barang', 'user']);

        return view('transactions.show', compact('transaction'));
    }

    public function uploadRemainingPaymentProof(Request $request, Transaction $transaction)
    {
        // Check authorization
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if remaining payment is required
        if (!$transaction->needsRemainingPayment()) {
            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'No remaining payment required or proof already uploaded.');
        }

        $request->validate([
            'remaining_payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $path = $request->file('remaining_payment_proof')->store('payment_proofs', 'public');

            $transaction->update([
                'remaining_payment_proof' => $path,
                'paid_amount' => $transaction->total_price,
                'status' => Transaction::STATUS_PENDING_REMAINING,
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Remaining payment proof uploaded successfully. Awaiting approval.');

        } catch (\Exception $e) {
            Log::error('Failed to upload remaining payment proof: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('transactions.show', $transaction)
                ->with('error', 'Failed to upload payment proof. Please try again.');
        }
    }

    public function history()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['items.jasa', 'items.barang'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.history', compact('transactions'));
    }
}