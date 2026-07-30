<?php

use App\Http\Controllers\Payment\FlutterwaveController;
use App\Http\Controllers\Payment\MpesaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;


Route::get('/ping', function () {
    return response()->json(['status' => 'active', 'time' => now()]);
});

Route::post('payments/callback', [MpesaController::class, 'callback'])->name('api.mpesa.callback');
Route::post('payments/mpesa/validate', [MpesaController::class, 'validate'])->name('api.mpesa.validate');
Route::post('payments/result', [MpesaController::class, 'b2cResult'])->name('api.mpesa.b2c.result');
Route::post('payments/timeout', [MpesaController::class, 'b2cTimeout'])->name('api.mpesa.b2c.timeout');
Route::post('payments/flutterwave/webhook', [FlutterwaveController::class, 'webhook'])->name('api.flutterwave.webhook');

Route::get('campaigns', [CampaignController::class, 'apiIndex']);
Route::get('campaigns/{campaign}', [CampaignController::class, 'apiShow']);
Route::post('campaigns/{campaign}/donate', [DonationController::class, 'store']);
Route::get('donations/{donation}/status', [DonationController::class, 'status']);