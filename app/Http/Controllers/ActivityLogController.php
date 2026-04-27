<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->startDate ?? Carbon::now('Asia/Manila')->format('Y-m-d');
        $endDate = $request->endDate ?? Carbon::now('Asia/Manila')->format('Y-m-d');

        $moduleNames = [
            1 => 'Authentications',
            3 => 'Account',
            7 => 'User',
            'Documents' => 'Documents',
            'Department' => 'Departments',
            'Document Type' => 'Document Types',
            'Students' => 'Students',
        ];

        $moduleFilters = [
            'authentications' => 1,
            'accounts' => 3,
            'users' => 7,
            'documents' => 'Documents',
            'departments' => 'Department',
            'document_types' => 'Document Type',
            'students' => 'Students',
        ];

        $query = ActivityLog::query()->latest();

        $moduleKey = $request->module;
        if ($moduleKey && isset($moduleFilters[$moduleKey])) {
            $query->where('module', $moduleFilters[$moduleKey]);
        }

        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw('CONVERT_TZ(created_at, "+00:00", "+08:00")'), [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);
        }

        $logs = $query->get()->transform(function ($log) use ($moduleNames) {
            $log->module_name = $moduleNames[$log->module] ?? 'Unknown';
            $log->created_at_formatted = $log->created_at
                ->copy()
                ->timezone('Asia/Manila')
                ->format('F j, Y g:i A');
            return $log;
        });

        return view('admin.activity_logs', [
            'logs' => $logs,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}