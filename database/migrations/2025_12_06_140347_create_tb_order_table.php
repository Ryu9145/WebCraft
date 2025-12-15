<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbOrderTable extends Migration
{
    public function up()
    {
        Schema::create('tb_order', function (Blueprint $table) {
            // [PERBAIKAN UTAMA] Gunakan bigIncrements agar ID otomatis terisi (1, 2, 3...)
            $table->bigIncrements('ID'); 
            
            $table->string('kode_order')->unique(); // Ini untuk kode unik seperti 'ORD-123'
            
            $table->string('nama_pemesan', 100)->default('Guest');
            $table->string('Nama_Produk', 255);
            
            // Ubah tipe data tanggal menjadi dateTime agar menyimpan JAM juga (bukan cuma tanggal)
            $table->dateTime('Tanggal_Pesan'); 
            
            $table->decimal('Total_Harga', 15, 2); // 15 digit agar aman untuk nominal besar
            
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            
            $table->enum('Status_Pesanan', ['Pending','Paid','Failed','Refunded','Dispute','Selesai','Diproses','Dibatalkan'])->default('Pending');
            $table->string('snap_token', 255)->nullable();
            
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('tb_order');
    }
}