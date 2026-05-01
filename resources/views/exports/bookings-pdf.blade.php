<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Booking</title>
    <style>
        @page {
            margin: 24px;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 0;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 24px;
        }

        .meta {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .summary {
            margin-bottom: 20px;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Laporan Booking</h1>

    <div class="meta">
        <div>Periode: {{ $tanggalDari ? \Carbon\Carbon::parse($tanggalDari)->format('d/m/Y') : '-' }} s/d {{ $tanggalSampai ? \Carbon\Carbon::parse($tanggalSampai)->format('d/m/Y') : '-' }}</div>
        <div>Pencarian: {{ filled($search) ? $search : '-' }}</div>
        <div>Tanggal export: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <strong>Total booking:</strong> {{ $bookings->count() }}<br>
        <strong>Total pendapatan:</strong> Rp {{ number_format((float) $totalPendapatan, 0, ',', '.') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Pegawai</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th class="text-right">Total Harga</th>
                <th class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $booking->order_id }}</td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                    <td>{{ $booking->pegawai->name ?? '-' }}</td>
                    <td>{{ optional($booking->tanggal_booking)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->jam_booking)->format('H:i') }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>{{ $booking->jenis_pembayaran }}</td>
                    <td class="text-right">Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format((float) $booking->total_pembayaran, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Data booking tidak ada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
