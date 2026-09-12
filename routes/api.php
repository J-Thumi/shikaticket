<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/bitika', [\App\Http\Controllers\Web\BitikaWebhookController::class, 'handle'])->name('webhooks.bitika');