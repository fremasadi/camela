<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\JadwalOperasional;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingSlotTersediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_slot_tersedia_only_returns_available_slots(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $pegawai = Pegawai::create([
            'name' => 'Pegawai 1',
            'no_telp' => '08123456789',
            'status' => 'aktif',
        ]);

        $kategori = KategoriLayanan::create([
            'name' => 'Hair Treatment',
        ]);

        $layanan = Layanan::create([
            'kategori_id' => $kategori->id,
            'name' => 'Creambath',
            'deskripsi' => 'Perawatan rambut.',
            'harga' => 75000,
            'image' => [],
            'estimasi_menit' => 60,
        ]);

        JadwalOperasional::create([
            'jam_buka' => '09:00:00',
            'jam_tutup' => '13:00:00',
            'status' => 'buka',
            'keterangan' => null,
        ]);

        Booking::create([
            'order_id' => 'BOOKING-TEST1',
            'user_id' => $user->id,
            'pegawai_id' => $pegawai->id,
            'tanggal_booking' => now()->addDay()->toDateString(),
            'jam_booking' => '09:30',
            'jam_selesai' => '10:30',
            'status' => 'pending',
            'total_harga' => 75000,
            'jenis_pembayaran' => 'lunas',
            'total_pembayaran' => 75000,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/bookings/slot-tersedia?tanggal=' . now()->addDay()->toDateString() . '&layanan_ids[]=' . $layanan->id);

        $response
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonMissing([
                'jam_mulai' => '09:30',
            ])
            ->assertJsonPath('data.next_available.jam_mulai', '10:30');
    }

    public function test_slot_tersedia_hides_past_slots_for_today(): void
    {
        $this->travelTo(now()->setTime(10, 20));

        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        Pegawai::create([
            'name' => 'Pegawai 1',
            'no_telp' => '08123456789',
            'status' => 'aktif',
        ]);

        $kategori = KategoriLayanan::create([
            'name' => 'Hair Treatment',
        ]);

        $layanan = Layanan::create([
            'kategori_id' => $kategori->id,
            'name' => 'Creambath',
            'deskripsi' => 'Perawatan rambut.',
            'harga' => 75000,
            'image' => [],
            'estimasi_menit' => 60,
        ]);

        JadwalOperasional::create([
            'jam_buka' => '09:00:00',
            'jam_tutup' => '13:00:00',
            'status' => 'buka',
            'keterangan' => null,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/bookings/slot-tersedia?tanggal=' . now()->toDateString() . '&layanan_ids[]=' . $layanan->id);

        $response
            ->assertOk()
            ->assertJsonMissing([
                'jam_mulai' => '09:00',
            ])
            ->assertJsonMissing([
                'jam_mulai' => '10:00',
            ])
            ->assertJsonPath('data.next_available.jam_mulai', '10:30');
    }
}
