<div>
    <h3 class="text-lg font-bold mb-3">Informasi Booking</h3>

    <div class="grid grid-cols-2 gap-4">
        <div><strong>Order ID:</strong> {{ $booking->order_id }}</div>
        <div><strong>Tanggal:</strong> {{ optional($booking->tanggal_booking)->format('d-m-Y') ?? $booking->tanggal_booking }}</div>
        <div><strong>Jam:</strong> {{ substr((string) $booking->jam_booking, 0, 5) }}</div>
        <div><strong>Status:</strong> {{ $booking->status }}</div>
        <div><strong>Total Harga:</strong> Rp{{ number_format($booking->total_harga,0,',','.') }}</div>
    </div>

    <hr class="my-4">

    <h3 class="text-lg font-bold mb-3">Detail Layanan</h3>

    <table class="w-full text-sm border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Layanan</th>
                <th class="p-2 border">Harga</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($details as $d)
                <tr>
                    <td class="p-2 border">{{ $d->layanan?->name ?? '-' }}</td>
                    <td class="p-2 border">Rp{{ number_format($d->harga,0,',','.') }}</td>
                    <td class="p-2 border">{{ $d->qty }}</td>
                    <td class="p-2 border">Rp{{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-3 border text-center text-gray-500">
                        Detail layanan belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
