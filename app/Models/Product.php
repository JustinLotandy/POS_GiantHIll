<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_Produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_Produk',
        'name',
        'barcode',     
        'category_id',
        'harga_sebelum',
        'harga_sesudah',
        'stock',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_kategori');
    }
}
