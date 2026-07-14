<?php

namespace App\Http\Controllers;

use App\Actions\CreateIdea;
use App\Actions\UpdateIdea;
use App\IdeaStatus;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\IdeaRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $ideas = $user
            ->ideas()
            ->when(
                in_array($request->status, IdeaStatus::values(), true),
                fn ($query) => $query->where('status', $request->status)
            )
            ->latest()
            ->get();

        return view('idea.index', [
            'ideas' => $ideas,
            'statusCounts' => Idea::statusCounts($user),
        ]);
    }

    public function store(Request $request, CreateIdea $action)
    {
        $action->handle($request->validated());

        return to_route('ideas.index')
            ->with('success', 'Idea created!');
    }

    public function show(Idea $idea)
    {
        Gate::authorize('workWith', $idea);

        return view('idea.show', [
            'idea' => $idea,
        ]);
    }

    public function update(
        IdeaRequest $request,
        Idea $idea,
        UpdateIdea $action
    ) {
        Gate::authorize('workWith', $idea);

        $action->handle($request->validated(), $idea);

        return back()->with('success', 'Idea updated!');
    }

    public function destroy(Idea $idea)
    {
        Gate::authorize('workWith', $idea);

        if ($idea->image_path) {
            Storage::disk('public')->delete($idea->image_path);
        }

        $idea->delete();

        return to_route('ideas.index')
            ->with('success', 'Idea deleted!');
    }
}
