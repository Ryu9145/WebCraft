<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbKategoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_kategori', function (Blueprint $table) {
            $table->id(); // int(11) AUTO_INCREMENT
            $table->string('nama_kategori', 100);
            $table->string('slug', 100);
            $table->integer('urutan')->default(0);
            
            // created_at (timestamp). Di SQL lama Anda tidak ada updated_at, 
            // tapi sebaiknya pakai timestamps() agar Laravel senang.
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
        Schema::dropIfExists('tb_kategori');
    }
}
