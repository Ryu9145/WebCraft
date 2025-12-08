<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'tb_kategori'; // Beritahu nama tabel
    protected $fillable = ['nama_kategori', 'slug', 'urutan'];
}
