<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TinkerCommand;

class TinkerCommandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->view == 'trash') {
            $query = TinkerCommand::onlyTrashed();
        } else {
            $query = TinkerCommand::query();
        }

        if ($request->filled('search')) {
            $search = str_replace('\\', '\\\\', $request->search);
            $query->where('command', 'like', "%{$search}%");
        }

        if ($request->filled('favorite')) {
            $query->where('is_favorite', 1);
        }

        $commands = $query->orderBy('created_at', 'desc')->paginate(10);
        $commands->appends($request->all());

        return view('tinker.index', compact('commands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'command' => 'required|string'
        ]);

        $command = $request->command;
        $result = null;

        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            ob_start();
            $result = eval ("return " . $command . ";");
            $output = ob_get_clean();

            if ($output) {
                $result = $output;
            }
        } catch (\Throwable $e) {
            $result = "Error: " . $e->getMessage();
        }

        $executionTime = round(microtime(true) - $startTime, 4) . 's';
        $memoryUsage = round((memory_get_usage() - $startMemory) / 1024, 2) . ' KB';

        TinkerCommand::create([
            'command' => $command,
            'result' => json_encode($result),
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage,
        ]);

        return back()->with('success', '✅ Command executed in ' . $executionTime);
    }

    public function favorite($id)
    {
        $cmd = TinkerCommand::findOrFail($id);
        $cmd->update([
            'is_favorite' => !$cmd->is_favorite
        ]);

        return back()->with('success', '⭐ Favorite updated!');
    }

    public function delete($id)
    {
        TinkerCommand::findOrFail($id)->delete();

        return back()->with('success', '🗑 Moved to trash!');
    }

    public function restore($id)
    {
        $cmd = TinkerCommand::onlyTrashed()->find($id);

        if (!$cmd) {
            return back()->with('error', '❌ Not found in trash!');
        }

        $cmd->restore();

        return back()->with('success', '♻ Restored successfully!');
    }
}