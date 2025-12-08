<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'tb_produk';
    protected $fillable = [
        'username', 'nama_produk', 'deskripsi', 'link_github', 
        'harga', 'kategori', 'gambar', 'status', 'is_featured'
    ];
}
