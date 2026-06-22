<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $ideas = session()->get('ideas',[]);

    return view('ideas',[
    'ideas' => $ideas,
    ]);
});

Route::post('/ideas', function () {
    $idea = request('idea'); // Captura la idea del formulario
    session()->push('ideas', $idea); // Almacena la idea en la sesión
    return redirect('/'); // Redirige de vuelta a la página principal
});

//temporal
Route::get('/delete-ideas', function () {

    session()->forget('ideas');

    return redirect('/');
});
