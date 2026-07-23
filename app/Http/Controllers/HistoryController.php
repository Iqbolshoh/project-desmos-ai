<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AiTutorSession;
use App\Models\SavedGraph;

class HistoryController extends Controller
{
    public function index()
    {
        $sessions = AiTutorSession::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);
            
        $savedGraphs = SavedGraph::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('history.index', compact('sessions', 'savedGraphs'));
    }

    public function showSession(AiTutorSession $session)
    {
        if ($session->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tutor.show', compact('session'));
    }
    
    public function saveGraph(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'expression' => 'required|string',
        ]);
        
        SavedGraph::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'state_data' => ['expression' => $request->expression],
        ]);
        
        return redirect()->back()->with('success', 'Grafik saqlandi!');
    }
}
