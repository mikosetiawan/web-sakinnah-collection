<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Define constants for statuses
    const STATUS_PENDING = 'pending';
    const STATUS_AWAITING_REMAINING = 'awaiting_remaining';
    const STATUS_PENDING_REMAINING = 'pending_remaining';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';

    // Define constants for payment types
    const PAYMENT_TYPE_DP = 'dp';
    const PAYMENT_TYPE_FULL = 'full';

    protected $fillable = [
        'user_id',
        'total_price',
        'payment_type',
        'paid_amount',
        'status',
        'payment_proof',
        'remaining_payment_proof',
    ];

    protected $appends = ['status_label', 'status_color', 'remaining_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Check if the transaction is a down payment.
     *
     * @return bool
     */
    public function isDownPayment()
    {
        return $this->payment_type === self::PAYMENT_TYPE_DP;
    }

    /**
     * Check if the transaction is in pending status.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if the transaction is awaiting remaining payment.
     *
     * @return bool
     */
    public function isAwaitingRemaining()
    {
        return $this->status === self::STATUS_AWAITING_REMAINING;
    }

    /**
     * Check if the transaction is pending remaining payment approval.
     *
     * @return bool
     */
    public function isPendingRemaining()
    {
        return $this->status === self::STATUS_PENDING_REMAINING;
    }

    /**
     * Check if the transaction needs remaining payment.
     *
     * @return bool
     */
    public function needsRemainingPayment()
    {
        return $this->isDownPayment() && is_null($this->remaining_payment_proof) && $this->isAwaitingRemaining();
    }

    /**
     * Check if the transaction is completed.
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the transaction is cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Get the display label for the status.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending Confirmation',
            self::STATUS_AWAITING_REMAINING => 'Awaiting Remaining Payment',
            self::STATUS_PENDING_REMAINING => 'Pending Remaining Approval',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Get the color class for the status.
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_AWAITING_REMAINING => 'orange',
            self::STATUS_PENDING_REMAINING => 'orange',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_CANCELLED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get the remaining amount for down payment transactions.
     *
     * @return float
     */
    public function getRemainingAmountAttribute()
    {
        if ($this->isDownPayment()) {
            return $this->total_price - $this->paid_amount;
        }
        return 0;
    }
}