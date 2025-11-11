<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->whereIn('action', ['view', 'create', 'update', 'delete'])
            ->orderBy('created_at', 'desc')
            ->select('activity_logs.*')
            ->get();

        return view('admin.logs.index', compact('logs'));
    }

    public function delete(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            ActivityLog::whereIn('id', $ids)->delete();

            return response()->json(['success' => 'Logs deleted successfully.']);
        }

        return response()->json(['error' => 'No logs selected for deletion.'], 400);
    }
}
