<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Filament\Resources\Resource;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->char('id_Produk')->primary();
            $table->string('namaProduk');
            $table->integer('stok');
            $table->decimal('hargaBelil', 10, 2);
            $table->decimal('hargaJual', 10, 2);
            $table->char('id_kategori', 36);
            $table->string('image')->nullable(); // untuk upload gambar
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
