<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use App\Http\Controllers\AppointmentConfirmationController;

// WhatsApp Webhook — No CSRF protection here
Route::match(['GET', 'POST'], '/appointment-confirmation/whatsapp-webhook', 
    [AppointmentConfirmationController::class, 'whatsapp_webhook_response']
)->name('appointment.confirmation.meta');

Route::get('/test', function () {
    return 'test';
})->withoutMiddleware(['auth', 'auth:api', 'auth:web']);
