<?php

use App\Http\Controllers\BookingExportExcelController;
use App\Http\Controllers\BookingExportPdfController;
use Illuminate\Support\Facades\Route;

Route::middleware('signed')->get('/bookings/export/excel', BookingExportExcelController::class)
    ->name('bookings.export.excel');

Route::middleware('signed')->get('/bookings/export/pdf', BookingExportPdfController::class)
    ->name('bookings.export.pdf');

Route::get('/', function () {
    return view('welcome');
});
