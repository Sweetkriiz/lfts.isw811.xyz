<?php
use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\SessionsController;


// index
Route::get('/ideas',[IdeaController::class, 'index']);

// create
Route::get('/ideas/create', [IdeaController::class, 'create']);

// store
Route::post('/ideas', [IdeaController::class, 'store']);

// show
Route::get('/ideas/{idea}', [IdeaController::class, 'show']);

// edit
Route::get('/ideas/{idea}/edit',[IdeaController::class, 'edit']);

// update
Route::patch('/ideas/{idea}', [IdeaController::class, 'update']);

//destroy
Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy']);


Route::get('/register', [RegisterUserController::class, 'create']);
Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/login', [SessionsController::class, 'create']);
Route::post('/login', [SessionsController::class, 'store']);
Route::delete('/logout', [SessionsController::class, 'destroy']);
    