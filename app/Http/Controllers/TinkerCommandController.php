<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TinkerCommand;

class TinkerCommandController extends Controller
{
    // =========================
    // LIST + SEARCH + FILTER + TRASH VIEW
    // =========================
    public function index(Request $request)
    {
        // 🗑 TRASH MODE
        if ($request->view == 'trash') {
            $query = TinkerCommand::onlyTrashed();
        } else {
            $query = TinkerCommand::query();
        }

        // 🔍 SEARCH (FIXED FOR \ BACKSLASH ISSUE)
        if ($request->filled('search')) {
            $search = $request->search;

            // FIX: escape backslashes properly
            $search = str_replace('\\', '\\\\', $search);

            $query->where('command', 'like', "%{$search}%");
        }

        // ⭐ FAVORITE FILTER
        if ($request->filled('favorite')) {
            $query->where('is_favorite', 1);
        }

        // 📊 ORDER BY ASC
        $commands = $query->orderBy('created_at', 'asc')->paginate(10);

        // 🔁 KEEP QUERY IN PAGINATION
        $commands->appends($request->all());

        return view('tinker.index', compact('commands'));
    }

    // =========================
    // EXECUTE COMMAND
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'command' => 'required|string'
        ]);

        $command = $request->command;
        $result = null;

        try {
            ob_start();

            // ⚠️ DANGER: eval only for dev
            $result = eval ("return " . $command . ";");

            $output = ob_get_clean();

            if ($output) {
                $result = $output;
            }

        } catch (\Throwable $e) {
            $result = "Error: " . $e->getMessage();
        }

        TinkerCommand::create([
            'command' => $command,
            'result' => json_encode($result),
        ]);

        return back()->with('success', '✅ Command executed successfully!');
    }

    // =========================
    // TOGGLE FAVORITE
    // =========================
    public function favorite($id)
    {
        $cmd = TinkerCommand::findOrFail($id);

        $cmd->update([
            'is_favorite' => !$cmd->is_favorite
        ]);

        return back()->with('success', '⭐ Favorite updated!');
    }

    // =========================
    // SOFT DELETE (TRASH)
    // =========================
    public function delete($id)
    {
        TinkerCommand::findOrFail($id)->delete();

        return back()->with('success', '🗑 Moved to trash!');
    }

    // =========================
    // RESTORE FROM TRASH
    // =========================
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