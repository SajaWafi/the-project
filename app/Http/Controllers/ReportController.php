<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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

        return $pdf->download('taif-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function buildReportData(string $period): array
    {
        $endDate = Carbon::now()->endOfDay();

        if ($period === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $previousStart = Carbon::now()->subWeek()->startOfWeek();
            $previousEnd = Carbon::now()->subWeek()->endOfWeek();
            $periodLabel = 'This Week';

            $chartLabels = ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
            $chartEpisodes = [1, 0, 1, 2, 0, 1, 1];
            $chartHeartRate = [110, 118, 121, 130, 125, 140, 122];
        } else {
            $startDate = Carbon::now()->startOfMonth();
            $previousStart = Carbon::now()->subMonth()->startOfMonth();
            $previousEnd = Carbon::now()->subMonth()->endOfMonth();
            $periodLabel = 'This Month';

            $chartLabels = ['1', '5', '10', '15', '20', '25', '30'];
            $chartEpisodes = [0, 1, 3, 2, 4, 1, 2];
            $chartHeartRate = [105, 120, 118, 132, 145, 128, 122];
        }

        $child = [
            'name' => 'Ahmed',
            'age' => 8,
            'diagnosis' => 'Autism Spectrum Disorder',
        ];

        $episodesCount = 6;
        $previousEpisodesCount = 4;
        $avgHeartRate = 122;
        $peakHeartRate = 165;
        $minHeartRate = 90;
        $avgSteps = 6500;
        $safeZoneBreaches = 3;
        $avgEpisodeDuration = 7;
        $longestEpisodeDuration = 12;
        $liveStatus = 'Anxiety';
        $liveUpdatedAt = now()->subSeconds(12)->format('h:i A');

        $changePercent = $previousEpisodesCount > 0
            ? round((($episodesCount - $previousEpisodesCount) / $previousEpisodesCount) * 100)
            : 100;

        $episodeTrendText = $changePercent >= 0
            ? 'Increase of ' . abs($changePercent) . '% compared with the previous period'
            : 'Decrease of ' . abs($changePercent) . '% compared with the previous period';

        return [
            'child' => $child,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'previous_start' => $previousStart->format('Y-m-d'),
            'previous_end' => $previousEnd->format('Y-m-d'),
            'period_label' => $periodLabel,
            'episodes_count' => $episodesCount,
            'previous_episodes_count' => $previousEpisodesCount,
            'avg_heart_rate' => $avgHeartRate,
            'peak_heart_rate' => $peakHeartRate,
            'min_heart_rate' => $minHeartRate,
            'avg_steps' => $avgSteps,
            'safe_zone_breaches' => $safeZoneBreaches,
            'avg_episode_duration' => $avgEpisodeDuration,
            'longest_episode_duration' => $longestEpisodeDuration,
            'live_status' => $liveStatus,
            'live_updated_at' => $liveUpdatedAt,
            'episode_trend_text' => $episodeTrendText,
            'trigger_insights' => [
                'Episodes are more frequent during school hours.',
                'Higher heart rate appears to be associated with noisy environments.',
                'Episodes occur more often during the morning period.',
            ],
            'medical_recommendations' => [
                'Reduce exposure to loud or overstimulating environments.',
                'Monitor the child more closely during school hours and mornings.',
                'Contact the doctor if heart rate remains elevated or episode frequency increases.',
            ],
            'episodes' => [
                [
                    'date' => '2026-03-12',
                    'time' => '10:30 AM',
                    'location' => 'School',
                    'duration' => '7 minutes',
                    'heart_rate' => 150,
                    'trigger' => 'Loud noise',
                    'status' => 'Panic Episode',
                ],
                [
                    'date' => '2026-03-18',
                    'time' => '06:20 PM',
                    'location' => 'Park',
                    'duration' => '12 minutes',
                    'heart_rate' => 165,
                    'trigger' => 'Sensory overload',
                    'status' => 'High Anxiety',
                ],
            ],
            'alerts' => [
                'March 12 - Panic episode detected',
                'March 18 - Abnormally high heart rate',
                'March 22 - Safe-zone exit detected',
            ],
            'chart_labels' => $chartLabels,
            'chart_episodes' => $chartEpisodes,
            'chart_heart_rate' => $chartHeartRate,
        ];
    }
}