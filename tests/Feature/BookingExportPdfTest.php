<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BookingExportPdfTest extends TestCase
{
    use RefreshDatabase;

    public function test_pdf_export_downloads_confirmed_bookings_as_pdf(): void
    {
        $user = User::factory()->create([
            'name' => 'Nadia',
        ]);

        $pegawai = Pegawai::create([
            'name' => 'Rani',
            'no_telp' => '081234567891',
            'status' => 'aktif',
        ]);

        Booking::create([
            'order_id' => 'BOOK-PDF-001',
            'user_id' => $user->id,
            'pegawai_id' => $pegawai->id,
            'tanggal_booking' => '2026-05-01',
            'jam_booking' => '09:00',
            'jam_selesai' => '10:00',
            'status' => 'confirmed',
            'total_harga' => 125000,
            'jenis_pembayaran' => 'transfer',
            'total_pembayaran' => 125000,
        ]);

        $url = URL::temporarySignedRoute(
            'bookings.export.pdf',
            now()->addMinutes(5),
            [
                'tableSearch' => 'BOOK-PDF-001',
            ],
        );

        $response = $this->get($url);

        $response
            ->assertOk()
            ->assertDownload();

        $this->assertSame('application/pdf', $response->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }
}
