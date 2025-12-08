<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk; // Panggil Model Produk yang sudah kita buat

class ProdukSeeder extends Seeder
{
    public function run()
    {
        // 1. Produk: hhhh (Portofolio)
        Produk::create([
            'username'    => null,
            'nama_produk' => 'hhhh',
            'deskripsi'   => 'hhhh',
            'link_github' => 'https://github.com/Ryu9145/Phising.git',
            'harga'       => 676.00,
            'kategori'    => 'Portofolio',
            'gambar'      => '6933585cb66d1.jpg', // Pastikan nanti file ini ada di public folder
            'status'      => 'active',
            'is_featured' => 0,
        ]);

        // 2. Produk: hhhh (Admin)
        Produk::create([
            'username'    => 'BudiUser',
            'nama_produk' => 'hhhh',
            'deskripsi'   => 'ffff',
            'link_github' => 'https://github.com/Ryu9145/Phising.git',
            'harga'       => 9999.00,
            'kategori'    => 'Admin',
            'gambar'      => 'PROD-6933db7c3a288.jpeg',
            'status'      => 'active',
            'is_featured' => 0,
        ]);

        // 3. Produk: ambo
        Produk::create([
            'username'    => 'BudiUser',
            'nama_produk' => 'ambo',
            'deskripsi'   => 'ambo',
            'link_github' => 'https://github.com/Ryu9145/Phising.git',
            'harga'       => 120000.00,
            'kategori'    => 'Company Profile',
            'gambar'      => '6933e021efe08.jpeg',
            'status'      => 'active',
            'is_featured' => 0,
        ]);

        // 4. Produk: nagib nadir
        Produk::create([
            'username'    => 'BudiUser',
            'nama_produk' => 'nagib nadir',
            'deskripsi'   => 'nahi nagib',
            'link_github' => 'https://github.com/Ryu9145/Phising.git',
            'harga'       => 10000.00,
            'kategori'    => 'Admin',
            'gambar'      => '6933e19283593.jpeg',
            'status'      => 'active',
            'is_featured' => 0,
        ]);

        // 5. Produk: nayla
        Produk::create([
            'username'    => null,
            'nama_produk' => 'nayla',
            'deskripsi'   => 'sayang',
            'link_github' => 'https://github.com/Ryu9145/Phising.git',
            'harga'       => 10000.00,
            'kategori'    => 'Company Profile',
            'gambar'      => '6933f101e86e4.jpeg',
            'status'      => 'active',
            'is_featured' => 0,
        ]);

        // 6. Produk: techno celebes (Featured)
        Produk::create([
            'username'    => 'BudiUser',
            'nama_produk' => 'techno celebes',
            'deskripsi'   => 'saya la',
            'link_github' => 'https://github.com/Ryu9145/Phising.git',
            'harga'       => 1.00,
            'kategori'    => 'Company Profile',
            'gambar'      => '693422a071976.jpeg',
            'status'      => 'active',
            'is_featured' => 1, // Ini produk unggulan
        ]);
    }
}