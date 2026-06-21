<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'greeting' => 'hello',
        'person' => request('person', 'world')
    ]);
});