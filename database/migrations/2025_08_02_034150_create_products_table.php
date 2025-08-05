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
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->char('id_Produk', 36)->primary();
        $table->string('name');
        $table->char('category_id');
        $table->decimal('harga_sebelum', 12, 2);
        $table->decimal('harga_sesudah', 12, 2);
        $table->integer('stock');
        $table->string('image')->nullable();
        $table->timestamps();

        $table->foreign('category_id')->references('id_Kategori')->on('categories');
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
