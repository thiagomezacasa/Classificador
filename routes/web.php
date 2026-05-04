<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LeadDashboard;
use App\Http\Controllers\WebhookController;

Route::get('/', LeadDashboard::class)->name('dashboard');
Route::get('/test', function () {
    return view('test');
});
Route::get('/webhook/whatsapp', [WebhookController::class, 'verify']);
Route::post('/webhook/whatsapp', [WebhookController::class, 'receive'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('webhook.whatsapp');
