<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;
use Illuminate\Http\Request;

class IdeaController extends Controller
{
    public function index()
    {
        $ideas = Idea::all();

        return view('ideas.index', [
            'ideas' => $ideas,
        ]);
    }

    public function create()
    {
        return view('ideas.create');
    }

    public function store(StoreIdeaRequest $request)
    {
   
        Idea::create([
            'description' => $request->validated()['description'],
            'state' => 'pending',
        ]);

        return redirect('/ideas');
    }

    public function show(Idea $idea)
    {
        return view('ideas.show', [
            'idea' => $idea,
        ]);
    }

    public function edit(Idea $idea)
    {
        return view('ideas.edit', [
            'idea' => $idea,
        ]);
    }

    public function update(StoreIdeaRequest $request, Idea $idea)
    {
        $idea->update([
            'description' => $request->input('description'),
        ]);

        return redirect("/ideas/{$idea->id}");
    }

    public function destroy(Idea $idea)
    {
        $idea->delete();

        return redirect('/ideas');
    }
}