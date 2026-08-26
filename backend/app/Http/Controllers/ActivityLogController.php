<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Get a paginated list of activity logs for the admin panel.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user:id,name,email')->orderBy('created_at', 'desc');

        if ($request->has('action_type') && $request->action_type !== 'all') {
            $query->where('action', 'like', '%' . $request->action_type . '%');
        }

        $logs = $query->paginate(20);

        return response()->json($logs);
    }
}
