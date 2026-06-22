<?php

use Illuminate\Support\Facades\Route;
use App\Models\Idea;

// index
Route::get('/ideas', function () {
    $ideas = Idea::all();

    return view('ideas.index', [
        'ideas' => $ideas,
    ]);
});

// show
Route::get('/ideas/{idea}', function (Idea $idea) {
    return view('ideas.show', [
        'idea' => $idea,
    ]);
});

// edit
Route::get('/ideas/{idea}/edit', function (Idea $idea) {
    return view('ideas.edit', [
        'idea' => $idea,
    ]);
});

// update
Route::patch('/ideas/{idea}', function (Idea $idea) {
    $idea->update([
        'description' => request('idea'),
    ]);

    return redirect("/ideas/{$idea->id}");
});

// store
Route::post('/ideas', function () {
    $idea = Idea::create([
        'description' => request('idea'),
        'state' => 'pending',
    ]);

    return redirect("/ideas/{$idea->id}");
});

//destroy
Route::delete('/ideas/{idea}', function (Idea $idea) {
    $idea->delete();

    return redirect('/ideas');
});