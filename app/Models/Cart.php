<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jasa_id',
        'barang_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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