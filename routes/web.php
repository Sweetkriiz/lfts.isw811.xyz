
<?php

use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\SessionsController;
use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'home marketing page');

Route::get('/', fn () => 'Placeholder for the home page');

Route::middleware('auth')->group(function () {
    // index
    Route::get('/ideas', [IdeaController::class, 'index']);
    // create
    Route::get('/ideas/create', [IdeaController::class, 'create']);
    // store
    Route::post('/ideas', [IdeaController::class, 'store']);
    // show
    Route::get('/ideas/{idea}', [IdeaController::class, 'show']);
    // edit
    Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit']);
    // update
    Route::patch('/ideas/{idea}', [IdeaController::class, 'update']);
    // destroy
    Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy']);

    Route::delete('/logout', [SessionsController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterUserController::class, 'create'])->middleware('guest');
    Route::post('/register', [RegisterUserController::class, 'store'])->middleware('guest');

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);

});
