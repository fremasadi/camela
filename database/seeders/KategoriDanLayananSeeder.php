<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriLayanan;
use App\Models\Layanan;

class KategoriDanLayananSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori Bundling belum ada
        $kategori = KategoriLayanan::firstOrCreate(
            ['name' => 'Bundling'],
            ['name' => 'Bundling']
        );

        // Daftar layanan dalam kategori Bundling
        $layananSamples = [
            [
                'name' => 'Bundling Hair & Nail Care',
                'deskripsi' => 'Paket kombinasi perawatan rambut dan kuku dengan harga hemat.',
                'harga' => 350000,
                'image' => ['layanans/01K9F6NNJ0EEFEPWNACRBXJR2F.jpg'],
                'estimasi_menit' => 120,
            ],
            [
                'name' => 'Bundling Facial & Body Spa',
                'deskripsi' => 'Nikmati relaksasi maksimal dengan perawatan wajah dan spa tubuh sekaligus.',
                'harga' => 400000,
                'image' => ['layanans/01K9F6NNJ0EEFEPWNACRBXJR2F.jpg'],
                'estimasi_menit' => 150,
            ],
            [
                'name' => 'Bundling Complete Care',
                'deskripsi' => 'Paket lengkap mulai dari hair, nail, facial hingga body spa dalam satu sesi.',
                'harga' => 650000,
                'image' => ['layanans/01K9F6NNJ0EEFEPWNACRBXJR2F.jpg'],
                'estimasi_menit' => 240,
            ],
        ];

        // Tambahkan layanan-layanan ke kategori Bundling
        foreach ($layananSamples as $layanan) {
            $kategori->layanan()->create($layanan);
        }

        $this->command->info('✅ Kategori Bundling dan layanan terkait berhasil di-seed.');
    }
}
