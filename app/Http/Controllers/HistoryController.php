<?php

namespace App\Http\Controllers;

use App\Models\AiTutorSession;
use App\Models\SavedGraph;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoryController extends Controller
{
    /**
     * Display a paginated listing of AI sessions and saved graphs.
     */
    public function index(): View
    {
        $userId = auth()->id();

        $sessions = AiTutorSession::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'sessions_page');

        $savedGraphs = SavedGraph::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'graphs_page');

        return view('history.index', compact('sessions', 'savedGraphs'));
    }

    /**
     * Save a Desmos graph expression to history.
     */
    public function saveGraph(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'expression' => 'required|string',
        ]);

        SavedGraph::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'desmos_state' => ['expression' => $request->input('expression')],
        ]);

        return redirect()->back()->with('success', 'Graph saved successfully!');
    }
}
