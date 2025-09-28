<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Meja;
use Illuminate\Support\Str;

class MejaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $meja = [
            ['nomor_meja' => '01', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '02', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '03', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '04', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '05', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '06', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '07', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '08', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '09', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
            ['nomor_meja' => '10', 'qr_code' => Str::uuid()->toString(), 'is_active' => true],
        ];

        foreach ($meja as $data) {
            Meja::create($data);
        }
    }
}
