<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Transaction';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_Transaction',
        'user_id',
        'id_PaymentMethod',
        'total',
        'paid',
        'change',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    // Relasi ke PaymentMethod
    public function paymentMethod()
    {
        return $this->belongsTo(\App\Models\PaymentMethod::class, 'id_PaymentMethod', 'id_PaymentMethod');
    }

    // Nanti bisa tambahkan relasi ke detail transaksi (items), kalau perlu
}
