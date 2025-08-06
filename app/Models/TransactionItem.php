<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $table = 'transaction_items';
    protected $primaryKey = 'id_TransactionItem';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_TransactionItem',
        'id_Transaction',
        'id_Produk',
        'quantity',
        'price',
        'subtotal',
    ];

    // Relasi ke Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_Transaction', 'id_Transaction');
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_Produk', 'id_Produk');
    }
}
