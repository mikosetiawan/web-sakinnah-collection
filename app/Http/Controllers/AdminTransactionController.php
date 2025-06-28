<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['user', 'items.jasa', 'items.barang'])
            ->latest()
            ->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.jasa', 'items.barang']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function approve(Request $request, Transaction $transaction)
    {
        try {
            if ($transaction->isPending()) {
                $transaction->status = $transaction->isDownPayment()
                    ? Transaction::STATUS_AWAITING_REMAINING
                    : Transaction::STATUS_COMPLETED;
                $transaction->save();

                return redirect()->route('admin.transactions.show', $transaction)
                    ->with('success', $transaction->isDownPayment() ? 'Down payment and order confirmed.' : 'Transaction approved successfully.');
            } elseif ($transaction->isPendingRemaining()) {
                $transaction->status = Transaction::STATUS_COMPLETED;
                $transaction->save();

                return redirect()->route('admin.transactions.show', $transaction)
                    ->with('success', 'Remaining payment approved. Transaction completed.');
            }

            return redirect()->back()->with('error', 'Transaction cannot be approved.');
        } catch (\Exception $e) {
            Log::error('Error approving transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve transaction.');
        }
    }

    public function reject(Request $request, Transaction $transaction)
    {
        try {
            if ($transaction->isPending() || $transaction->isPendingRemaining()) {
                $transaction->status = Transaction::STATUS_REJECTED;
                $transaction->save();

                return redirect()->route('admin.transactions.show', $transaction)
                    ->with('success', 'Transaction rejected successfully.');
            }

            return redirect()->back()->with('error', 'Transaction cannot be rejected.');
        } catch (\Exception $e) {
            Log::error('Error rejecting transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject transaction.');
        }
    }

    public function uploadRemainingPaymentProof(Request $request, Transaction $transaction)
    {
        // Check if transaction needs remaining payment
        if (!$transaction->needsRemainingPayment()) {
            return redirect()->route('admin.transactions.show', $transaction)
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

            return redirect()->route('admin.transactions.show', $transaction)
                           ->with('success', 'Remaining payment proof uploaded successfully. Awaiting approval.');

        } catch (\Exception $e) {
            Log::error('Failed to upload remaining payment proof: ' . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.transactions.show', $transaction)
                           ->with('error', 'Failed to upload payment proof. Please try again.');
        }
    }

    public function cancel(Request $request, Transaction $transaction)
    {
        try {
            if (!$transaction->isCompleted() && !$transaction->isCancelled()) {
                $transaction->status = Transaction::STATUS_CANCELLED;
                $transaction->save();

                return redirect()->route('admin.transactions.show', $transaction)
                    ->with('success', 'Transaction cancelled successfully.');
            }

            return redirect()->back()->with('error', 'Transaction cannot be cancelled.');
        } catch (\Exception $e) {
            Log::error('Error cancelling transaction: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to cancel transaction.');
        }
    }
}