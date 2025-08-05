<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $primaryKey = 'id_PaymentMethod';
    public $incrementing = false;         // Agar ID bisa diisi manual
    protected $keyType = 'string';

    protected $fillable = [
        'id_PaymentMethod',
        'name_payment',
    ];
}
