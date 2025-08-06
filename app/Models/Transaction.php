<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;




class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Transaction';
    public $incrementing = true;
    protected $keyType = 'integer';

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
//     protected static function boot()
// {
//     parent::boot();
//     static::creating(function ($model) {
//         if (empty($model->id_Transaction)) {
//             $model->id_Transaction = (string) Str::uuid();
//         }
//     });
// }
  public function items()
    {
        return $this->hasMany(\App\Models\TransactionItem::class, 'id_Transaction', 'id_Transaction');
    }
    

    // Nanti bisa tambahkan relasi ke detail transaksi (items), kalau perlu
}
