<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = [
            // Makanan
            [
                'nama' => 'Nasi Goreng Kampung',
                'deskripsi' => 'Nasi goreng khas kampung dengan bumbu tradisional, dilengkapi telur dan kerupuk',
                'harga' => 25000,
                'kategori' => 'makanan',
                'is_available' => true
            ],
            [
                'nama' => 'Mie Ayam Bakso',
                'deskripsi' => 'Mie ayam dengan bakso sapi, pangsit, dan sayuran segar',
                'harga' => 20000,
                'kategori' => 'makanan',
                'is_available' => true
            ],
            [
                'nama' => 'Ayam Bakar Madu',
                'deskripsi' => 'Ayam bakar dengan bumbu madu dan rempah-rempah pilihan',
                'harga' => 35000,
                'kategori' => 'makanan',
                'is_available' => true
            ],
            [
                'nama' => 'Soto Ayam',
                'deskripsi' => 'Soto ayam kuah bening dengan kentang, telur, dan kerupuk',
                'harga' => 18000,
                'kategori' => 'makanan',
                'is_available' => true
            ],
            [
                'nama' => 'Gado-Gado',
                'deskripsi' => 'Sayuran segar dengan bumbu kacang khas dan kerupuk',
                'harga' => 15000,
                'kategori' => 'makanan',
                'is_available' => true
            ],

            // Minuman
            [
                'nama' => 'Es Teh Manis',
                'deskripsi' => 'Es teh manis segar',
                'harga' => 5000,
                'kategori' => 'minuman',
                'is_available' => true
            ],
            [
                'nama' => 'Es Jeruk',
                'deskripsi' => 'Es jeruk peras segar',
                'harga' => 8000,
                'kategori' => 'minuman',
                'is_available' => true
            ],
            [
                'nama' => 'Kopi Tubruk',
                'deskripsi' => 'Kopi tubruk hitam atau dengan gula',
                'harga' => 7000,
                'kategori' => 'minuman',
                'is_available' => true
            ],
            [
                'nama' => 'Es Kopi Susu',
                'deskripsi' => 'Kopi susu dingin dengan es batu',
                'harga' => 12000,
                'kategori' => 'minuman',
                'is_available' => true
            ],
            [
                'nama' => 'Jus Alpukat',
                'deskripsi' => 'Jus alpukat segar dengan susu kental manis',
                'harga' => 15000,
                'kategori' => 'minuman',
                'is_available' => true
            ],
            [
                'nama' => 'Es Campur',
                'deskripsi' => 'Es campur dengan berbagai macam buah dan jelly',
                'harga' => 12000,
                'kategori' => 'minuman',
                'is_available' => true
            ],

            // Snack
            [
                'nama' => 'Pisang Goreng',
                'deskripsi' => 'Pisang goreng crispy dengan gula halus',
                'harga' => 10000,
                'kategori' => 'snack',
                'is_available' => true
            ],
            [
                'nama' => 'Tahu Isi',
                'deskripsi' => 'Tahu goreng isi sayuran dengan bumbu kacang',
                'harga' => 8000,
                'kategori' => 'snack',
                'is_available' => true
            ],
            [
                'nama' => 'Bakwan Jagung',
                'deskripsi' => 'Bakwan jagung renyah dengan cabe rawit',
                'harga' => 6000,
                'kategori' => 'snack',
                'is_available' => true
            ],
            [
                'nama' => 'Kerupuk Udang',
                'deskripsi' => 'Kerupuk udang renyah',
                'harga' => 5000,
                'kategori' => 'snack',
                'is_available' => true
            ],
            [
                'nama' => 'Tempe Mendoan',
                'deskripsi' => 'Tempe goreng tepung dengan bumbu khas',
                'harga' => 7000,
                'kategori' => 'snack',
                'is_available' => true
            ]
        ];

        foreach ($menu as $data) {
            Menu::create($data);
        }
    }
}
