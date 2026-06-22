public function index()
{
    $ideas = session('ideas', []);
    return view('ideas', compact('ideas'));
}

public function store(Request $request)
{
    $idea = $request->input('idea');
    session()->push('ideas', $idea);
    return redirect('/ideas');
}