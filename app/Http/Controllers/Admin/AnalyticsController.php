<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Download;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    use AdminAccessMiddleware;

    public function __construct()
    {
        $this->middleware($this->makeIsAdminMiddleware())->except('index');
    }

    public function index(Request $request)
    {
        $range = (int) $request->get('range', 7);
        if (!in_array($range, [7, 30, 90], true)) {
            $range = 7;
        }

        // If visits table is missing, return empty dataset with notice
        if (!Schema::hasTable('visits')) {
            $dates = [];
            $dailyVisitors = [];
            for ($i = $range - 1; $i >= 0; $i--) {
                $dates[] = Carbon::now()->subDays($i)->format('M d');
                $dailyVisitors[] = 0;
            }

            session()->flash('message', [
                'type' => 'error',
                'content' => 'Analytics is not initialized yet. Please run database migrations to create the visits table.'
            ]);

            return view('admin.analytics', [
                'range' => $range,
                'dates' => $dates,
                'dailyVisitors' => $dailyVisitors,
                'dailyDownloads' => array_fill(0, $range, 0),
                'totalVisitors' => 0,
                'uniqueVisitors' => 0,
                'newUsers' => 0,
                'returningUsers' => 0,
                'countries' => [],
                'deviceBreakdown' => ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0],
                'browserBreakdown' => [],
            ]);
        }

        $startDate = Carbon::now()->startOfDay()->subDays($range - 1);
        $endDate = Carbon::now()->endOfDay();

        $visitsQuery = Visit::query()
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Daily visitors
        $rawDaily = $visitsQuery
            ->clone()
            ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->all();

        $dates = [];
        $dailyVisitors = [];
        for ($i = 0; $i < $range; $i++) {
            $day = $startDate->copy()->addDays($i);
            $key = $day->toDateString();
            $dates[] = $day->format('M d');
            $dailyVisitors[] = $rawDaily[$key] ?? 0;
        }

        $totalVisitors = array_sum($dailyVisitors);
        $dailyDownloads = array_fill(0, $range, 0);
        if (Schema::hasTable('downloads')) {
            $downloadsTrend = Download::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day')
                ->all();
            for ($i = 0; $i < $range; $i++) {
                $day = $startDate->copy()->addDays($i)->toDateString();
                $dailyDownloads[$i] = $downloadsTrend[$day] ?? 0;
            }
        }

        // Unique visitors approximated by unique visitor_id within range
        $uniqueVisitors = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('visitor_id')
            ->count('visitor_id');

        // New vs Returning users within the selected range
        $newUsers = Visit::select('visitor_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('visitor_id')
            ->havingRaw('MIN(created_at) BETWEEN ? AND ?', [$startDate, $endDate])
            ->get()
            ->count();
        $returningUsers = max($uniqueVisitors - $newUsers, 0);

        // Top countries
        $countries = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(country_code, "XX") as code, COALESCE(country_name, "Unknown") as name, COUNT(*) as visitors')
            ->groupBy('code', 'name')
            ->orderByDesc('visitors')
            ->limit(5)
            ->get()
            ->map(fn($r) => ['code' => $r->code, 'name' => $r->name, 'visitors' => (int) $r->visitors])
            ->all();

        // Devices
        $deviceCounts = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('device_type, COUNT(*) as total')
            ->groupBy('device_type')
            ->pluck('total', 'device_type')
            ->all();
        $deviceTotal = array_sum($deviceCounts);
        $deviceBreakdown = [
            'Desktop' => $deviceTotal ? round(($deviceCounts['desktop'] ?? 0) / $deviceTotal * 100) : 0,
            'Mobile' => $deviceTotal ? round(($deviceCounts['mobile'] ?? 0) / $deviceTotal * 100) : 0,
            'Tablet' => $deviceTotal ? round(($deviceCounts['tablet'] ?? 0) / $deviceTotal * 100) : 0,
        ];

        // Browsers
        $browserCounts = Visit::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(browser, "Other") as browser, COUNT(*) as total')
            ->groupBy('browser')
            ->pluck('total', 'browser')
            ->all();
        $browserTotal = array_sum($browserCounts);
        $browserBreakdown = [];
        foreach ($browserCounts as $name => $count) {
            $browserBreakdown[$name] = $browserTotal ? round($count / $browserTotal * 100) : 0;
        }

        return view('admin.analytics', [
            'range' => $range,
            'dates' => $dates,
            'dailyVisitors' => $dailyVisitors,
            'dailyDownloads' => $dailyDownloads,
            'totalVisitors' => $totalVisitors,
            'uniqueVisitors' => $uniqueVisitors,
            'newUsers' => $newUsers,
            'returningUsers' => $returningUsers,
            'countries' => $countries,
            'deviceBreakdown' => $deviceBreakdown,
            'browserBreakdown' => $browserBreakdown,
        ]);
    }
}


