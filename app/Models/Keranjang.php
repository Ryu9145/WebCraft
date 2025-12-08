<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'tb_keranjang';
    protected $fillable = ['username', 'produk_id'];

    public function produk() {
    return $this->belongsTo(Produk::class, 'produk_id', 'id');
}

}
