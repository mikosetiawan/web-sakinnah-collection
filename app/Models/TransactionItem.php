<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'jasa_id',
        'barang_id',
        'price',
        'pickup_date',
        'event_date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function jasa()
    {
        return $this->belongsTo(Jasa::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}