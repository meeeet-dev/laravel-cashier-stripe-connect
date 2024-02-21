<?php

use Illuminate\Support\Facades\Route;
use MeeeetDev\CashierConnect\Http\Controllers;

Route::post('/connectWebhook', [Controllers\WebhookController::class, 'handleWebhook'])->name('stripeConnect.webhook');
