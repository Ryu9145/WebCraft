<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_kategori' => 'Portofolio', 'slug' => 'portofolio', 'urutan' => 1],
            ['nama_kategori' => 'Admin', 'slug' => 'admin', 'urutan' => 2],
            ['nama_kategori' => 'Shop', 'slug' => 'shop', 'urutan' => 3],
            ['nama_kategori' => 'Company Profile', 'slug' => 'company-profile', 'urutan' => 4],
            ['nama_kategori' => 'E-Commerce', 'slug' => 'e-commerce', 'urutan' => 5],
            ['nama_kategori' => 'Medical', 'slug' => 'medical', 'urutan' => 6],
        ];

        foreach ($data as $item) {
            Kategori::create($item);
        }
    }
}