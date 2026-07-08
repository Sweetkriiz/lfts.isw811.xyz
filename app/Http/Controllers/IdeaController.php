<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Idea;
use Illuminate\Http\Request;
use App\IdeaStatus;
use Illuminate\Validation\Rule;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $user = Auth::user();

        $status =$request->status;

        if (! in_array($status, array_column(IdeaStatus::cases(), 'value'))) {
            $status = null;
        }
        // Use the Idea model directly to avoid undefined relation on the Auth user instance
        $ideasQuery = Idea::query();

        if ($user) {
            $ideasQuery->where('user_id', $user->id);
        }

        if ($status) {
            $ideasQuery->where('status', $status);
        }

        $ideas = $ideasQuery->get();


        return view('idea.index', [
            'ideas' => $ideas,
            'statusCounts' => Idea::statusCounts($user),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        return view('idea.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();

        return to_route('ideas.index');
    }
}
