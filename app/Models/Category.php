<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
     protected $table = 'categories';
    protected $primaryKey = 'id_kategori';
    public $incrementing = false; // karena pakai UUID/char, bukan integer auto-increment
    protected $keyType = 'string';

    protected $fillable = [
        'id_kategori',
        'nama_kategori',
    ];

    public function getRouteKeyName(): string
    {
        return 'id_kategori';
    }
}
