<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\IdeaRequest;
use App\Models\User;


class IdeaController extends Controller
{
    public function index()
    {
        return view('ideas.index', [
            'ideas' => Idea::where('user_id', Auth::id())->get(),
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Idea::class);

        return view('ideas.create');
    }

    public function store(IdeaRequest $request)
    {
        $idea = new Idea;
        $idea->description = $request->input('description');
        $idea->state = 'pending';
        $idea->user_id = Auth::id();
        $idea->save();

        return redirect('/ideas');
    }

    public function show(Idea $idea)
    {   
        Gate::authorize('update',$idea);

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
