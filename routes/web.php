<?php

use App\Http\Controllers\BookingExportPdfController;
use Illuminate\Support\Facades\Route;

Route::middleware('signed')->get('/bookings/export/pdf', BookingExportPdfController::class)
    ->name('bookings.export.pdf');

Route::get('/', function () {
    return view('welcome');
});
