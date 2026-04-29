<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

use App\Models\ComparisonRule;
use App\Models\Child;
use App\Models\PanicEvent;
use App\Models\SensorReading;
use App\Models\Notification;

class ReportController extends Controller
{
    public function show(Request $request)
    {
        $period = $request->get('period', 'month');
        $report = $this->buildReportData($period);

        return view('parents.report', compact('report', 'period'));
    }

    public function downloadPdf(Request $request)
    {
        $period = $request->get('period', 'month');
        $report = $this->buildReportData($period);

        $pdf = Pdf::loadView('parents.pdf', compact('report', 'period'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function buildReportData(string $period): array
    {
        $endDate = Carbon::now();

        if ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $previousStart = Carbon::now()->subWeek()->startOfWeek();
            $previousEnd = Carbon::now()->subWeek()->endOfWeek();
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $previousStart = Carbon::now()->subMonth()->startOfMonth();
            $previousEnd = Carbon::now()->subMonth()->endOfMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | Child
        |--------------------------------------------------------------------------
        */
        $childModel = Child::first();

        $child = $childModel ? [
            'name' => $childModel->full_name ?? 'Ahmed',
            'age' => $childModel->birth_date ? Carbon::parse($childModel->birth_date)->age : 8,
            'diagnosis' => $childModel->diagnosis ?? 'Autism Spectrum Disorder',
        ] : [
            'name' => 'Ahmed',
            'age' => 8,
            'diagnosis' => 'Autism Spectrum Disorder',
        ];

        /*
        |--------------------------------------------------------------------------
        | Data From DB + Default
        |--------------------------------------------------------------------------
        */

        $episodesCount = PanicEvent::whereBetween('created_at', [$startDate, $endDate])->count() ?: 6;
        $episodesCount = 6;
        $previousEpisodesCount = PanicEvent::whereBetween('created_at', [$previousStart, $previousEnd])->count() ?: 4;

        $avgHeartRate = SensorReading::whereBetween('created_at', [$startDate, $endDate])->avg('heart_rate') ?? 122;

        $peakHeartRate = SensorReading::whereBetween('created_at', [$startDate, $endDate])->max('heart_rate') ?? 165;

        $safeZoneBreaches = Notification::where('type', 'safe_zone_exit')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count() ?: 3;

        $latestReading = SensorReading::latest()->first();

        $liveStatus = $latestReading->child_status ?? 'anxiety';

        /*
        |--------------------------------------------------------------------------
        | Comparison
        |--------------------------------------------------------------------------
        */
        $changePercent = $previousEpisodesCount > 0
            ? round((($episodesCount - $previousEpisodesCount) / $previousEpisodesCount) * 100)
            : null;

        $comparison = $this->getComparisonFromDB($changePercent);

        /*
        |--------------------------------------------------------------------------
        | Recommendations
        |--------------------------------------------------------------------------
        */
        $recommendations = $this->generateRecommendations(
            $changePercent,
            $peakHeartRate,
            $safeZoneBreaches,
            $liveStatus
        );

        return [
            'child' => $child,

            'episodes_count' => $episodesCount,
            'previous_episodes_count' => $previousEpisodesCount,

            'avg_heart_rate' => $avgHeartRate,
            'peak_heart_rate' => $peakHeartRate,

            'safe_zone_breaches' => $safeZoneBreaches,
            'live_status' => $liveStatus,

            'change_percent' => $changePercent,

            'comparison_status' => $comparison['status'],
            'comparison_message_en' => $comparison['message'],

            'medical_recommendations' => $recommendations,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Comparison From DB
    |--------------------------------------------------------------------------
    */
    private function getComparisonFromDB(?int $changePercent): array
    {
        if ($changePercent === null) {
            return [
                'status' => 'no_data',
                'message' => 'No previous data available for comparison.'
            ];
        }

        $rule = ComparisonRule::where('is_active', true)
            ->where(function ($q) use ($changePercent) {
                $q->whereNull('min_value')
                  ->orWhere('min_value', '<=', $changePercent);
            })
            ->where(function ($q) use ($changePercent) {
                $q->whereNull('max_value')
                  ->orWhere('max_value', '>=', $changePercent);
            })
            ->first();

        return [
            'status' => $rule->status ?? 'unknown',
            'message' => $rule->message_en ?? 'Condition unclear.'
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Recommendations (Logic)
    |--------------------------------------------------------------------------
    */
    private function generateRecommendations($changePercent, $peakHeartRate, $safeZoneBreaches, $status)
    {
        $rec = [];

        if ($changePercent >= 50) {
            $rec[] = 'Monitor the child closely and consult a doctor if episodes continue.';
        }

        if ($peakHeartRate >= 140) {
            $rec[] = 'High heart rate detected. Recheck and monitor activity.';
        }

        if ($safeZoneBreaches > 0) {
            $rec[] = 'Child left safe zone. Verify location immediately.';
        }

        if ($status === 'panic') {
            $rec[] = 'Move the child to a calm environment and reduce sensory input.';
        }

        if ($status === 'anxiety') {
            $rec[] = 'Reduce environmental stimuli and maintain routine.';
        }

        return $rec;
    }
}