<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\ParentProfile;
use App\Models\Child;
use App\Models\MedicalReport;
use App\Models\SensorReading;
use App\Models\PanicEvent;
use App\Models\Notification;
use App\Models\RecommendationRule;
use App\Models\ComparisonRule;
use App\Models\Location; 

class ReportController extends Controller
{
    public function show(Request $request)
    {
        $period = $request->get('period', 'week'); //افتراضياً
        
        // 1. تحديث التقرير بالداتا الحقيقية
        $this->syncRealBraceletData($period);

        // 2. جلب التقرير 
        $report = $this->buildReportData($period);

        if (!$report) {
            return redirect()->route('parents.home')->with('error', 'No data to display the report.');
        }

        return view('parents.report', compact('report', 'period'));
    }

    public function downloadPdf(Request $request)
    {
        $period = $request->get('period', 'week');
        $this->syncRealBraceletData($period);
        $report = $this->buildReportData($period);

        if (!$report) {
            return back()->with('error', 'The report cannot be downloaded at this time.');
        }

        $pdf = Pdf::loadView('parents.pdf', compact('report', 'period'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * دالة سحب البيانات الحقيقية من الإسوارة وتوليد التقرير
     */
    //وظيفتها "تبني وتخزن" التقرير في الداتابيز
    private function syncRealBraceletData(string $period)
    {
        $parentProfile = ParentProfile::where('user_id', Auth::id())->first();
        if (!$parentProfile) return;

        $child = Child::where('parent_id', $parentProfile->id)->first();
        if (!$child) return;

        $startDate = $period === 'week' ? Carbon::now()->subDays(7) : Carbon::now()->subDays(30);
        //استخراج البيانات المستمرة
        $readings = SensorReading::where('child_id', $child->id)
            ->where('created_at', '>=', $startDate)
            ->get();
        //استخراج الأحداث الحرجة
        $episodes = PanicEvent::where('child_id', $child->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        $safeZoneBreaches = 0; 
        // التعديل الجديد: تحديث تقرير الشهر/الأسبوع الحالي فقط والاحتفاظ بالتاريخ القديم
        MedicalReport::where('child_id', $child->id)
            ->where('report_type', $period)
            ->where('report_month', now()->month)
            ->where('report_year', now()->year)
            ->delete();

        if ($readings->isEmpty() && $episodes->isEmpty()) return;
        //تستخدم دوال Collections في لارافل لاستخراج القيم الإحصائية (المتوسط، الأعلى، الأقل).
        $avgHeartRate = $readings->avg('heart_rate') ? round($readings->avg('heart_rate')) : 0;
        $peakHeartRate = $readings->max('heart_rate') ?? 0;
        $minHeartRate = $readings->where('heart_rate', '>', 0)->min('heart_rate') ?? 0;
        $avgSteps = $readings->avg('motion_level') ? round($readings->avg('motion_level') * 15) : 0;
        //عدد النوبات
        $parentProfile = \App\Models\ParentProfile::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
$childModel = \App\Models\Child::where('parent_id', $parentProfile->id)->first();
        // 1. تحديد بداية ونهاية الفترة السابقة (سواء كان التقرير أسبوعي أو شهري)
        if ($period === 'week') {
            // الأسبوع اللي فات
            $startOfPrev = \Carbon\Carbon::now()->subWeek()->startOfWeek();
            $endOfPrev   = \Carbon\Carbon::now()->subWeek()->endOfWeek();
        } else {
            // الشهر اللي فات
            $startOfPrev = \Carbon\Carbon::now()->subMonth()->startOfMonth();
            $endOfPrev   = \Carbon\Carbon::now()->subMonth()->endOfMonth();
        }

        // 2. حساب عدد النوبات الحقيقية في تلك الفترة من جدول نوبات الهلع
        $previousEpisodes = \App\Models\PanicEvent::where('child_id', $childModel->id)
            ->whereBetween('created_at', [$startOfPrev, $endOfPrev])
            ->count();

        $currentEpisodes = $episodes->count();
        
        if ($previousEpisodes > 0) {
            $comparisonPercentage = (($currentEpisodes - $previousEpisodes) / $previousEpisodes) * 100;
        } elseif ($currentEpisodes > 0) {
            $comparisonPercentage = 100; 
        } else {
            $comparisonPercentage = 0; 
        }
        //جلب قاعدة المقارنة
        $comparisonRule = ComparisonRule::where('is_active', true)
            ->where('min_value', '<=', $comparisonPercentage)
            ->where(function($query) use ($comparisonPercentage) {
                $query->where('max_value', '>=', $comparisonPercentage)
                      ->orWhereNull('max_value'); 
            })->first();

        $summaryText = $comparisonRule ? $comparisonRule->message_en : 'Report generated from actual physical device data.';
        $statusText = $comparisonRule ? $comparisonRule->status : 'Stable';

        // 4. إنشاء التقرير 
        $report = MedicalReport::create([
            'child_id' => $child->id,
            'report_type' => $period,
            'report_month' => now()->month,
            'report_year' => now()->year,
            'title' => $period === 'week' ? 'Weekly Health Summary' : 'Monthly Health Summary',
            'child_status' => $statusText,
            'total_episodes' => $currentEpisodes,
            'previous_period_episodes' => $previousEpisodes, 
            'average_heart_rate' => $avgHeartRate,
            'peak_heart_rate' => $peakHeartRate,
            'min_heart_rate' => $minHeartRate,
            'average_steps' => $avgSteps, 
            'safe_zone_exit_count' => $safeZoneBreaches,
            'comparison_percentage' => round($comparisonPercentage),
            'summary_text' => $summaryText,
            'avg_episode_duration_min' => 0, 
            'longest_episode_duration_min' => 0 
        ]);

        // 5. التوصيات الذكية
        $activeRules = RecommendationRule::where('is_active', true)->get()->keyBy('rule_key');
        $recommendationsToInsert = [];
        $sortOrder = 1;
        //"تحذير شديد"
        if ($comparisonPercentage >= 50 && isset($activeRules['high_episodes_increase'])) {
            $recommendationsToInsert[] = ['message' => $activeRules['high_episodes_increase']->message, 'priority_level' => $activeRules['high_episodes_increase']->priority, 'sort_order' => $sortOrder++];
        } 
        elseif ($comparisonPercentage >= 10 && isset($activeRules['noticeable_episodes_increase'])) {
            $recommendationsToInsert[] = ['message' => $activeRules['noticeable_episodes_increase']->message, 'priority_level' => $activeRules['noticeable_episodes_increase']->priority, 'sort_order' => $sortOrder++];
        }

        if ($peakHeartRate >= 140 && $avgSteps < 500 && isset($activeRules['high_hr_low_activity'])) {
            $recommendationsToInsert[] = ['message' => $activeRules['high_hr_low_activity']->message, 'priority_level' => $activeRules['high_hr_low_activity']->priority, 'sort_order' => $sortOrder++];
        }

        if ($safeZoneBreaches > 0 && isset($activeRules['safe_zone_exit'])) {
            $recommendationsToInsert[] = ['message' => $activeRules['safe_zone_exit']->message, 'priority_level' => $activeRules['safe_zone_exit']->priority, 'sort_order' => $sortOrder++];
        }

        if ($statusText === 'Needs Monitoring' && isset($activeRules['panic_status'])) {
            $recommendationsToInsert[] = ['message' => $activeRules['panic_status']->message, 'priority_level' => $activeRules['panic_status']->priority, 'sort_order' => $sortOrder++];
        }

        if (empty($recommendationsToInsert) && isset($activeRules['stable_condition'])) {
            $recommendationsToInsert[] = ['message' => $activeRules['stable_condition']->message, 'priority_level' => $activeRules['stable_condition']->priority, 'sort_order' => 1];
        }

        if (!empty($recommendationsToInsert)) {
            $report->recommendations()->createMany($recommendationsToInsert);
        }

        // ========================================================
        // 6. تفاصيل النوبات الحقيقية
        // ========================================================
        
        $allDurations = []; 

        foreach ($episodes as $ep) {
            
            $relatedReading = SensorReading::find($ep->sensor_reading_id);
            $actualHeartRate = $relatedReading ? $relatedReading->heart_rate : $peakHeartRate;

            $locationName = 'Tracked Location';
            if ($ep->location_id) {
                $loc = Location::find($ep->location_id);
                $locationName = $loc ? "Lat: {$loc->latitude}, Lng: {$loc->longitude}" : 'Unknown Location';
            } elseif ($relatedReading && $relatedReading->place_value) {
                $locationName = 'Coords: ' . $relatedReading->place_value;
            }

            $start = $ep->started_at ? Carbon::parse($ep->started_at) : Carbon::parse($ep->created_at);
            
            if ($ep->ended_at) {
                $end = Carbon::parse($ep->ended_at);
                $duration = $start->diffInMinutes($end);
            } else {
                $duration = 10; 
            }

            if ($duration < 1) {
                $duration = 1; 
            }

            $allDurations[] = $duration;
            $eventDate = $start->toDateTimeString(); //حولنا وقت البداية ($start) إلى "نص صريح ومفهوم" باش الداتابيز تقبله وتخزنه بدون أخطاء

            \App\Models\ReportEpisodeDetail::create([
                'report_id'      => $report->id, 
                'panic_event_id' => $ep->id,
                'episode_title'  => $ep->event_type ?? 'Panic Attack',
                'episode_date'   => $eventDate, 
                'location_name'  => $locationName,
                'duration_min'   => $duration, 
                'heart_rate'     => $actualHeartRate,
                'severity'       => $ep->severity ?? 'High'
            ]);
            if (!empty($allDurations)) {
            $calculatedAvgDuration = round(collect($allDurations)->avg(), 2);
            $calculatedMaxDuration = collect($allDurations)->max();

            // تحديث التقرير في الداتابيز بالأرقام الحقيقية
            $report->update([
                'avg_episode_duration_min' => $calculatedAvgDuration,
                'longest_episode_duration_min' => $calculatedMaxDuration
            ]);
        }
            // 9. إضافة التنبيهات (Alert History) للتقرير
            // كل نوبة هلع نحولوها لتنبيه باش تنعرض في واجهة التقرير
            $report->alertHistory()->create([
                'alert_text' => 'Panic Event Detected (' . ($ep->severity ?? 'High') . ') on ' . $start->format('M d, h:i A')
            ]);
        }
        
        // تنبيه في حالة الخروج من السيف زون
        if ($safeZoneBreaches > 0) {
            $report->alertHistory()->create([
                'alert_text' => 'Child exited the safe zone ' . $safeZoneBreaches . ' times.'
            ]);
        }

        // 8. الرسوم البيانية الحقيقية
        $groupedReadings = $readings->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('M d');
        });

        $groupedEpisodes = $episodes->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('M d');
        });

        $daysToIterate = $period === 'week' ? 7 : 30;
        for ($i = $daysToIterate; $i >= 0; $i--) {
            $dateStr = Carbon::now()->subDays($i)->format('M d');
            
            $report->heartRateTrends()->create([
                'point_label' => $dateStr,
                'point_order' => $daysToIterate - $i,
                'heart_rate_value' => $groupedReadings->has($dateStr) ? round($groupedReadings[$dateStr]->avg('heart_rate')) : 0
            ]);

            $report->episodeTrends()->create([
                'point_label' => $dateStr,
                'point_order' => $daysToIterate - $i,
                'episode_count' => $groupedEpisodes->has($dateStr) ? $groupedEpisodes[$dateStr]->count() : 0
            ]);
        }
    }

    /**
     * جلب التقرير الجاهز للواجهة 
     */
    //"حلقة الوصل" بين قاعدة البيانات وواجهة المستخدم
    private function buildReportData(string $period)
    {
        $parentProfile = ParentProfile::where('user_id', Auth::id())->first();
        if (!$parentProfile) return null;

        $childModel = Child::where('parent_id', $parentProfile->id)->first();
        if (!$childModel) return null;

        // تجهيز الاستعلام
        $query = MedicalReport::with(['recommendations', 'episodeDetails', 'alertHistory', 'episodeTrends', 'heartRateTrends'])
            ->where('child_id', $childModel->id)
            ->where('report_type', $period);

        // 🔥 التعديل: إجبار الواجهة تعرض تقرير الشهر والسنة الحالية فقط باش ما يطلعلناش الهستوري القديم
        if ($period === 'month') {
            $query->where('report_month', now()->month)
                  ->where('report_year', now()->year);
        }

        // جلب التقرير
        $dbReport = $query->latest()->first();
    //نمشوا لجدول قراءات الحساسات، ونجيبوا (آخر قراءة نبض وحركة جتها من الإسوارة) للطفل
        $latestReading = SensorReading::where('child_id', $childModel->id)->latest('created_at')->first();

        if (!$dbReport) {
            return $this->getEmptyReportFallback($childModel, $period, $latestReading);
        }
//المنظومة هنا تاخذ كل الداتا المعقدة اللي في الداتابيز، وترتبها في "صندوق واحد منظم" 
// باش تبعته لواجهة المستخدم  بطريقة نظيفة وسهلة القراءة.
        return [
            'period_label' => $period === 'week' ? 'Weekly Report' : 'Monthly Report',
            'child' => [
                'name' => $childModel->name,
                'age' => $childModel->birth_date ? Carbon::parse($childModel->birth_date)->age : 'N/A',
                'diagnosis' => $childModel->autism_level ? 'Autism (' . $childModel->autism_level . ')' : 'Autism Spectrum Disorder',
            ],
            'live_updated_at' => $latestReading ? Carbon::parse($latestReading->created_at)->diffForHumans() : 'No Recent Data',
            'live_status' => $dbReport->child_status ?? 'Stable',
            'episodes_count' => $dbReport->total_episodes,
            'previous_episodes_count' => $dbReport->previous_period_episodes,
            'episode_trend_text' => $dbReport->summary_text ?? 'Real device data loaded.',
            
            'avg_episode_duration' => $dbReport->avg_episode_duration_min ?? 0,
            'longest_episode_duration' => $dbReport->longest_episode_duration_min ?? 0,
            
            'medical_recommendations' => $dbReport->recommendations->sortBy('sort_order')->pluck('message')->toArray(),
            'avg_heart_rate' => $dbReport->average_heart_rate ?? 0,
            'peak_heart_rate' => $dbReport->peak_heart_rate ?? 0,
            'min_heart_rate' => $dbReport->min_heart_rate ?? 0,
            'avg_steps' => $dbReport->average_steps ?? 0,
            'safe_zone_breaches' => $dbReport->safe_zone_exit_count ?? 0,
            'chart_labels' => $dbReport->episodeTrends->sortBy('point_order')->pluck('point_label')->toArray(),
            'chart_episodes' => $dbReport->episodeTrends->sortBy('point_order')->pluck('episode_count')->toArray(),
            'chart_heart_rate' => $dbReport->heartRateTrends->sortBy('point_order')->pluck('heart_rate_value')->toArray(),
            
            'episodes' => $dbReport->episodeDetails->map(function($ep) {
                return [
                    'status' => $ep->episode_title,
                    'date' => Carbon::parse($ep->episode_date)->format('M d, Y'),
                    'time' => Carbon::parse($ep->episode_date)->format('h:i A'), 
                    'location' => $ep->location_name,
                    'duration' => $ep->duration_min . ' mins', 
                    'heart_rate' => $ep->heart_rate,
                    'trigger' => $ep->severity,
                ];
            })->toArray(),
            
            // توا التنبيهات حتنعرض صح لأننا حفظناها فوق
            'alerts' => $dbReport->alertHistory->pluck('alert_text')->toArray(),
            
            'trigger_insights' => [
                $dbReport->summary_text ?? 'Review recent changes in routine or environment.',
                'Sensory overload (noise/light) may be a contributing factor.'
            ],
        ];
    }

    private function getEmptyReportFallback($child, $period, $latestReading)
    {
        return [
            'period_label' => $period === 'week' ? 'Weekly Report' : 'Monthly Report',
            'child' => [
                'name' => $child->name,
                'age' => $child->birth_date ? Carbon::parse($child->birth_date)->age : '-',
                'diagnosis' => $child->autism_level ? 'Autism (' . $child->autism_level . ')' : '-',
            ],
            'live_updated_at' => $latestReading ? Carbon::parse($latestReading->created_at)->diffForHumans() : '-',
            'live_status' => 'Waiting for Bracelet...',
            'episodes_count' => 0, 'previous_episodes_count' => 0,
            'episode_trend_text' => 'Wear the bracelet to start generating reports.',
            'avg_episode_duration' => 0, 'longest_episode_duration' => 0,
            'medical_recommendations' => ['Waiting for real hardware data...'],
            'avg_heart_rate' => 0, 'peak_heart_rate' => 0, 'min_heart_rate' => 0,
            'avg_steps' => 0, 'safe_zone_breaches' => 0,
            'chart_labels' => [], 'chart_episodes' => [], 'chart_heart_rate' => [],
            'episodes' => [], 
            'alerts' => [], // تطلع فاضية في حال مافيش تقرير
            'trigger_insights' => ['Not enough data to determine triggers yet.']
        ];
    }
    //الدالة المسؤولة عن عرض صفحة "سجل التقارير" (History) في لوحة تحكم ولي الأمر.
    public function history()
    {
        $parentProfile = \App\Models\ParentProfile::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        
        $reports = collect();
        
        if ($parentProfile) {
            $child = \App\Models\Child::where('parent_id', $parentProfile->id)->first();
            
            if ($child) {
                $reports = \App\Models\MedicalReport::where('child_id', $child->id)
                    ->where('report_type', 'month') 
                    ->orderBy('report_year', 'desc')
                    ->orderBy('report_month', 'desc')
                    ->get();
            }
        }

        return view('reports-history', compact('reports'));
    }
    //هذه دالة "الحذف الجماعي" (Bulk Delete
    public function destroyMultiple(Request $request)
    {
        $reportIds = $request->input('report_ids', []);

        if (!empty($reportIds)) {
            \App\Models\MedicalReport::whereIn('id', $reportIds)->delete();
        }

        return back()->with('success', 'Reports deleted successfully.');
    }
}