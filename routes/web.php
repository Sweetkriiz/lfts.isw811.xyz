<?php

declare(strict_types=1);

use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\IdeaImageController;
use App\Http\Controllers\ProfileController;


Route::redirect('/', '/ideas');

Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index')->middleware('auth');
Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store')->middleware('auth');
Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('ideas.show')->middleware('auth');

Route::patch('/ideas/{idea}', [ideaController::class, 'update'])->name('idea.update')->middleware('auth');
Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('ideas.destroy')->middleware('auth');
Route::delete('/ideas/{idea}/image', [IdeaImageController::class, 'destroy'])->name('ideas.image.destroy')->middleware('auth');

Route::patch('/steps/{step}', [StepController::class, 'update'])->name('step.update')->middleware('auth');

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [SessionsController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [SessionsController::class, 'store'])->middleware('guest');

Route::post('/logout', [SessionsController::class, 'destroy'])->middleware('auth');

Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
