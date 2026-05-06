<?php

use App\Models\Booking;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('booking:confirm-pending {bookingId? : ID booking yang ingin di-confirm} {--order-id= : Order ID booking yang ingin di-confirm} {--all : Confirm semua booking yang statusnya pending}', function (?int $bookingId = null) {
    $orderId = $this->option('order-id');
    $confirmAll = (bool) $this->option('all');

    if (!$bookingId && !$orderId && !$confirmAll) {
        $this->error('Gunakan salah satu target: bookingId, --order-id=ORDER_ID, atau --all');
        return self::FAILURE;
    }

    $query = Booking::query()->where('status', 'pending');

    if ($bookingId) {
        $query->whereKey($bookingId);
    }

    if ($orderId) {
        $query->where('order_id', $orderId);
    }

    $bookings = $query->get();

    if ($bookings->isEmpty()) {
        $this->warn('Tidak ada booking pending yang cocok dengan kriteria tersebut.');
        return self::SUCCESS;
    }

    $updated = 0;

    foreach ($bookings as $booking) {
        $booking->update([
            'status' => 'confirmed',
        ]);

        $updated++;
        $this->line("Booking {$booking->id} ({$booking->order_id}) diubah menjadi confirmed.");
    }

    $this->info("Selesai. Total booking yang diubah: {$updated}.");

    return self::SUCCESS;
})->purpose('Ubah status booking pending menjadi confirmed');
