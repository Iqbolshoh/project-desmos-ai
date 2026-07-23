<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roadmap;
use App\Services\Roadmap\RoadmapService;

class RoadmapController extends Controller
{
    public function show()
    {
        $roadmap = Roadmap::where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest('generated_at')
            ->first();

        return view('roadmap.show', compact('roadmap'));
    }

    public function generate(RoadmapService $roadmapService)
    {
        $roadmapService->generateForUser(auth()->user());
        return redirect()->route('roadmap.show')->with('success', 'Yangi reja tuzildi!');
    }

    public function toggleTask(Request $request, Roadmap $roadmap)
    {
        if ($roadmap->user_id !== auth()->id()) {
            abort(403);
        }

        $taskId = $request->input('task_id');
        $weeks = $roadmap->weekly_plan;

        foreach ($weeks as &$week) {
            foreach ($week['tasks'] as &$task) {
                if ($task['id'] === $taskId) {
                    $task['completed'] = !$task['completed'];
                }
            }
        }

        $roadmap->weekly_plan = $weeks;

        // Recalculate progress
        $total = 0;
        $completed = 0;
        foreach ($weeks as $week) {
            foreach ($week['tasks'] as $task) {
                $total++;
                if ($task['completed']) $completed++;
            }
        }

        $roadmap->completion_percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
        $roadmap->save();

        return back();
    }
}
