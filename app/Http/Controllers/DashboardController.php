<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', 'daily');

        $documentsData = collect();
        $labels = collect();

        $query = Document::query();

        switch ($range) {
            case 'weekly':
                $start = Carbon::now('Asia/Manila')->subWeeks(4)->startOfWeek();
                for ($i = 0; $i < 5; $i++) {
                    $weekStart = $start->copy()->addWeeks($i);
                    $weekEnd = $weekStart->copy()->endOfWeek();

                    $count = (clone $query)
                        ->whereBetween('created_at', [$weekStart, $weekEnd])
                        ->count();

                    $documentsData->push($count);
                    $labels->push('Week ' . $weekStart->format('W'));
                }
                break;

            case 'monthly':
                $start = Carbon::now('Asia/Manila')->subMonths(5)->startOfMonth();
                for ($i = 0; $i < 6; $i++) {
                    $month = $start->copy()->addMonths($i);

                    $count = (clone $query)
                        ->whereYear('created_at', $month->year)
                        ->whereMonth('created_at', $month->month)
                        ->count();

                    $documentsData->push($count);
                    $labels->push($month->format('M Y'));
                }
                break;

            case 'yearly':
                $start = Carbon::now('Asia/Manila')->subYears(9)->startOfYear();
                for ($i = 0; $i < 10; $i++) {
                    $year = $start->copy()->addYears($i);

                    $count = (clone $query)
                        ->whereYear('created_at', $year->year)
                        ->count();

                    $documentsData->push($count);
                    $labels->push($year->format('Y'));
                }
                break;

            case 'daily':
            default:
                $start = Carbon::now('Asia/Manila')->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $start->copy()->addDays($i)->toDateString();

                    $count = (clone $query)
                        ->whereDate('created_at', $date)
                        ->count();

                    $documentsData->push($count);
                    $labels->push(Carbon::parse($date)->format('l'));
                }
                break;
        }

        return view('admin.index', [
            'documentsCount' => Document::count(),
            'pendingDocuments' => Document::where('status', 'Submitted')->count(),
            'processingDocuments' => Document::where('status', 'Processing')->count(),
            'readyDocuments' => Document::where('status', 'Ready for claiming')->count(),
            'claimedDocuments' => Document::where('status', 'Claimed')->count(),
            'users' => User::count(),
            'accessLogs' => ActivityLog::latest()->take(10)->get(),
            'documentsGraphData' => [
                'counts' => $documentsData,
                'dates' => $labels,
            ],
            'range' => $range,
        ]);
    }

    /**
     * AJAX refresh for charts
     */
    public function getDashboardData(Request $request)
    {
        $range = $request->get('range', 'daily');

        $documentsData = collect();
        $labels = collect();

        $query = Document::query();

        switch ($range) {
            case 'daily':
                $start = Carbon::now('Asia/Manila')->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $start->copy()->addDays($i)->toDateString();
                    $documentsData->push(
                        (clone $query)->whereDate('created_at', $date)->count()
                    );
                    $labels->push(Carbon::parse($date)->format('l'));
                }
                break;

            case 'weekly':
                $start = Carbon::now('Asia/Manila')->startOfMonth();
                $end = $start->copy()->endOfMonth();
                $current = $start->copy()->startOfWeek();

                while ($current->lte($end)) {
                    $weekStart = $current->copy();
                    $weekEnd = $current->copy()->endOfWeek();

                    $documentsData->push(
                        (clone $query)->whereBetween('created_at', [$weekStart, $weekEnd])->count()
                    );
                    $labels->push('Week ' . $weekStart->format('W'));

                    $current->addWeek();
                }
                break;

            case 'monthly':
                $year = $request->get('year', Carbon::now()->year);
                for ($m = 1; $m <= 12; $m++) {
                    $documentsData->push(
                        (clone $query)
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $m)
                            ->count()
                    );
                    $labels->push(Carbon::create()->month($m)->format('M'));
                }
                break;

            case 'yearly':
            default:
                $start = Carbon::now('Asia/Manila')->subYears(9)->startOfYear();
                for ($i = 0; $i < 10; $i++) {
                    $year = $start->copy()->addYears($i);
                    $documentsData->push(
                        (clone $query)->whereYear('created_at', $year->year)->count()
                    );
                    $labels->push($year->format('Y'));
                }
                break;
        }

        return response()->json([
            'documentsGraphData' => [
                'counts' => $documentsData,
                'dates' => $labels,
            ]
        ]);
    }
}
