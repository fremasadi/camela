<?php

namespace App\Http\Controllers;

use App\Services\BookingExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingExportExcelController extends Controller
{
    public function __invoke(Request $request, BookingExportService $bookingExportService): StreamedResponse
    {
        $bookings = $bookingExportService->getFilteredBookings($request);
        $context = $bookingExportService->getExportContext($request);
        $content = view('exports.bookings-excel', [
            'bookings' => $bookings,
            'tanggalDari' => $context['tanggalDari'],
            'tanggalSampai' => $context['tanggalSampai'],
            'search' => $context['search'],
            'totalPendapatan' => $bookings->sum('total_harga'),
        ])->render();

        return response()->streamDownload(
            static function () use ($content): void {
                echo $content;
            },
            $bookingExportService->makeFilename('xls'),
            [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Cache-Control' => 'max-age=0',
            ],
        );
    }
}
