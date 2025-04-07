<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketControllerAPI;
use App\Http\Controllers\UserControllerAPI;
use App\Http\Controllers\SystemControllerAPI;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['agent'])->group(function () {
    Route::post('login', [UserControllerAPI::class, 'login'])->name('api.login');
    Route::get('tickets/{id}', [TicketControllerAPI::class, 'show'])->name('api.tickets.show');
    Route::post('tickets', [TicketControllerAPI::class, 'store'])->name('api.tickets.store');
    Route::post('users', [UserControllerAPI::class, 'store'])->name('api.users.store');
    Route::get('systems', [SystemControllerAPI::class, 'index'])->name('api.systems.index');
    Route::get('users', [UserControllerAPI::class, 'index'])->name('api.users.index');
});