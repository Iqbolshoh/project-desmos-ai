<?php

namespace App\Http\Controllers;

use App\Models\Roadmap;
use App\Services\Roadmap\RoadmapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoadmapController extends Controller
{
    /**
     * Display the active study roadmap for the authenticated student.
     */
    public function show(): View
    {
        $roadmap = Roadmap::where('user_id', auth()->id())
            ->where('status', 'active')
            ->latest('generated_at')
            ->first();

        return view('roadmap.show', compact('roadmap'));
    }

    /**
     * Generate a new study roadmap for the student.
     */
    public function generate(RoadmapService $roadmapService): RedirectResponse
    {
        $user = auth()->user();
        if ($user) {
            $roadmapService->generateForUser($user);
        }

        return redirect()->route('roadmap.show')->with('success', 'New study roadmap generated successfully!');
    }

    /**
     * Toggle completion status of a task within the roadmap.
     */
    public function toggleTask(Request $request, Roadmap $roadmap): RedirectResponse
    {
        if ($roadmap->user_id !== auth()->id()) {
            abort(403);
        }

        $taskId = $request->input('task_id');
        $weeks = $roadmap->weekly_plan ?? [];

        foreach ($weeks as &$week) {
            if (isset($week['tasks']) && is_array($week['tasks'])) {
                foreach ($week['tasks'] as &$task) {
                    if (isset($task['id']) && $task['id'] === $taskId) {
                        $task['completed'] = !($task['completed'] ?? false);
                    }
                }
            }
        }

        $roadmap->weekly_plan = $weeks;

        // Recalculate completion percentage
        $total = 0;
        $completed = 0;
        foreach ($weeks as $week) {
            if (isset($week['tasks']) && is_array($week['tasks'])) {
                foreach ($week['tasks'] as $task) {
                    $total++;
                    if (!empty($task['completed'])) {
                        $completed++;
                    }
                }
            }
        }

        $roadmap->completion_percent = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
        $roadmap->save();

        return back();
    }
}
