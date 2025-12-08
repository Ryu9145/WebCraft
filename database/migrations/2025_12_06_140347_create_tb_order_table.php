<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_order', function (Blueprint $table) {
            // Karena ID bukan auto increment integer, kita definisikan manual
            $table->string('ID', 50)->primary(); 
            
            $table->string('nama_pemesan', 100)->default('Guest');
            $table->string('Nama_Produk', 255); // Sesuai SQL lama
            $table->date('Tanggal_Pesan');      // Sesuai SQL lama
            $table->decimal('Total_Harga', 10, 2);
            
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            
            $table->enum('Status_Pesanan', ['Pending','Paid','Failed','Refunded','Dispute','Selesai','Diproses','Dibatalkan'])->default('Pending');
            $table->string('snap_token', 255)->nullable();
            
            // Di SQL lama nama kolomnya Created_At & Updated_At (Huruf besar)
            // Kita pakai timestamps() standar Laravel saja (created_at), nanti dimapping di Model.
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
        Schema::dropIfExists('tb_order');
    }
}
