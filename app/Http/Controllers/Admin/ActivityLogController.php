<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'action' => ['nullable', Rule::in(array_keys(ActivityLog::ACTION_LABELS))],
            'type' => ['nullable', 'string', 'max:40'],
        ]);

        $logs = ActivityLog::with('user')
            ->when($filters['action'] ?? null, fn ($q, $a) => $q->where('action', $a))
            ->when($filters['type'] ?? null, fn ($q, $t) => $q->where('model_type', $t))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        $types = ActivityLog::select('model_type')->distinct()->orderBy('model_type')->pluck('model_type');

        return view('admin.activity-logs.index', compact('logs', 'types', 'filters'));
    }
}
