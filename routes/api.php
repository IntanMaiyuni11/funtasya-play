<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController; 

// Route ini otomatis punya prefix 'api', jadi URL-nya: /api/midtrans-callback
Route::post('/midtrans-callback', [PaymentController::class, 'handleNotification']);

// Endpoint untuk provinsi
Route::get('/provinces', [AddressController::class, 'getProvinces']);

// Endpoint untuk kota berdasarkan provinsi
Route::get('/cities/{provinceId}', [AddressController::class, 'getCities']);

// Endpoint untuk kecamatan berdasarkan kota
Route::get('/districts/{cityId}', [AddressController::class, 'getDistricts']);

// Endpoint untuk kode pos berdasarkan kecamatan
Route::get('/postalcodes', [AddressController::class, 'getPostalCodes']);