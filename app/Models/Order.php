<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'tb_order';
    
    // BERITAHU LARAVEL BAHWA PRIMARY KEY ADALAH 'ID' (Kapital)
    protected $primaryKey = 'ID'; 

    protected $fillable = [
        'kode_order',
        'nama_pemesan',
        'Nama_Produk',
        'Total_Harga',
        'Tanggal_Pesan',
        'snap_token',
        'Status_Pesanan'
    ];
}