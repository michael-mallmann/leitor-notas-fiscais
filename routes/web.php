<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Storage;

Route::get('/invoice/view/{id}', [InvoiceController::class, 'viewFile'])->name('invoice.view');
Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
Route::post('/process', [InvoiceController::class, 'process'])->name('invoice.process');