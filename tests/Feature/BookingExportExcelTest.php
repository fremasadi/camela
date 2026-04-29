<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BookingExportExcelTest extends TestCase
{
    use RefreshDatabase;

    public function test_excel_export_downloads_filtered_confirmed_bookings(): void
    {
        $user = User::factory()->create([
            'name' => 'Salsa',
        ]);

        $pegawai = Pegawai::create([
            'name' => 'Mira',
            'no_telp' => '081234567890',
            'status' => 'aktif',
        ]);

        Booking::create([
            'order_id' => 'BOOK-001',
            'user_id' => $user->id,
            'pegawai_id' => $pegawai->id,
            'tanggal_booking' => '2026-04-29',
            'jam_booking' => '10:00',
            'jam_selesai' => '11:00',
            'status' => 'confirmed',
            'total_harga' => 150000,
            'jenis_pembayaran' => 'transfer',
            'total_pembayaran' => 150000,
        ]);

        Booking::create([
            'order_id' => 'BOOK-002',
            'user_id' => $user->id,
            'pegawai_id' => $pegawai->id,
            'tanggal_booking' => '2026-04-30',
            'jam_booking' => '12:00',
            'jam_selesai' => '13:00',
            'status' => 'pending',
            'total_harga' => 175000,
            'jenis_pembayaran' => 'cash',
            'total_pembayaran' => 0,
        ]);

        $url = URL::temporarySignedRoute(
            'bookings.export.excel',
            now()->addMinutes(5),
            [
                'tableSearch' => 'BOOK-001',
            ],
        );

        $response = $this->get($url);

        $response
            ->assertOk()
            ->assertDownload();

        $content = $response->streamedContent();

        $this->assertStringContainsString('Laporan Booking', $content);
        $this->assertStringContainsString('BOOK-001', $content);
        $this->assertStringNotContainsString('BOOK-002', $content);
    }
}
