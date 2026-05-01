<?php

namespace App\Http\Controllers;

use App\Services\BookingExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingExportPdfController extends Controller
{
    public function __invoke(Request $request, BookingExportService $bookingExportService): Response
    {
        $bookings = $bookingExportService->getFilteredBookings($request);
        $context = $bookingExportService->getExportContext($request);

        $pdf = Pdf::loadView('exports.bookings-pdf', [
            'bookings' => $bookings,
            'tanggalDari' => $context['tanggalDari'],
            'tanggalSampai' => $context['tanggalSampai'],
            'search' => $context['search'],
            'totalPendapatan' => $bookings->sum('total_harga'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download($bookingExportService->makeFilename('pdf'));
    }
}
