<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    protected $primaryKey = 'id_PaymentMethod';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_PaymentMethod',
        'name_payment',
    ];

 
}
