<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    // Pastikan nama tabel benar (jika tidak standar plural)
    protected $table = 'roles';

    // Izinkan kolom ini diisi
    protected $fillable = ['nama_role'];

    // Relasi ke User (One to Many)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}