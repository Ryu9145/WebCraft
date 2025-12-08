<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_produk', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->nullable(); // Relasi loose ke users
            $table->string('nama_produk', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('link_github', 255)->nullable();
            
            // Decimal(10,2) untuk harga
            $table->decimal('harga', 10, 2)->nullable();
            
            $table->string('kategori', 100)->nullable(); // Menyimpan nama kategori, bukan ID
            $table->string('gambar', 255)->nullable();
            
            $table->enum('status', ['active', 'pending', 'rejected'])->default('pending');
            $table->boolean('is_featured')->default(0); // tinyint(1) = boolean di Laravel
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_produk');
    }
}
