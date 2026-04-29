<?php

namespace App\Http\Controllers;

use App\Services\BookingExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingExportPdfController extends Controller
{
    public function __invoke(Request $request, BookingExportService $bookingExportService): View
    {
        $bookings = $bookingExportService->getFilteredBookings($request);
        $context = $bookingExportService->getExportContext($request);

        return view('exports.bookings-pdf', [
            'bookings' => $bookings,
            'tanggalDari' => $context['tanggalDari'],
            'tanggalSampai' => $context['tanggalSampai'],
            'search' => $context['search'],
            'totalPendapatan' => $bookings->sum('total_harga'),
        ]);
    }
}
