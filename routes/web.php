<?php

use Illuminate\Support\Facades\Route;
use App\Models\Idea;

Route::get('/', function () {

    $ideas = Idea::query()
        ->when(request('state'), function ($query, $state) {
            dd($state);
        });

    return view('ideas', [
        'ideas' => $ideas,
    ]);
});

Route::post('/ideas', function () {

    Idea::created([

        'description' => request('idea'),
        'state' => 'pending',
    ]);

    return redirect('/'); // Redirige de vuelta a la página principal
});

//temporal
Route::get('/delete-ideas', function () {

    session()->forget('ideas');

    return redirect('/');
});
