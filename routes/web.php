<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketMessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('tickets.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Tickets
    Route::resource('tickets', TicketController::class);
    
    // Rotas de controle de tempo dos tickets
    Route::post('tickets/{ticket}/assume', [TicketController::class, 'assume'])->name('tickets.assume');
    Route::post('tickets/{ticket}/pause', [TicketController::class, 'pause'])->name('tickets.pause');
    Route::post('tickets/{ticket}/resume', [TicketController::class, 'resume'])->name('tickets.resume');
    Route::post('tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
    
    // Rotas de Mensagens de Tickets
    Route::post('tickets/{ticket}/messages', [TicketMessageController::class, 'store'])->name('ticket-messages.store');
    Route::delete('ticket-messages/{message}', [TicketMessageController::class, 'destroy'])->name('ticket-messages.destroy');
    
    // Rotas de Categorias (apenas para atendentes)
    Route::middleware(['can:manage,App\Models\Category'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });
    
    // Rotas de Usuários (apenas para atendentes)
    Route::middleware(['can:manage,App\Models\User'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__.'/auth.php';
