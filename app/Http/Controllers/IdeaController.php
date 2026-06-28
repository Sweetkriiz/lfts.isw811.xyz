<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\IdeaRequest;

class IdeaController extends Controller
{
    public function index()
    {
      $ideas = Idea::query()->where('user_id', Auth::id())->get();

      $ideas = Auth::user()->ideas;

        return view('ideas.index', [
            'ideas' => $ideas,
        ]);
    }

    public function create()
    {
        return view('ideas.create');
    }

    public function store(IdeaRequest $request)
{
    Idea::create([
        'description' => $request->input('description'),
        'state' => 'pending',
        'user_id' => Auth::id(),
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

    public function update(IdeaRequest $request, Idea $idea)
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