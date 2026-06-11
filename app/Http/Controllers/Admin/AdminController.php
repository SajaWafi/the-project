<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Child;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;
use App\Models\DoctorRequest;
use App\Models\Complaint;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Users count
        $usersCount = User::count();

        // Doctors & parents count by role
        $doctorsCount = User::where('role', 'doctor')->count();
        $parentsCount = User::where('role', 'parent')->count();

        // Children count
        $childrenCount = Child::count();

        // Pending doctor requests
        $pendingRequestsCount = DoctorRequest::where('status', 'pending')->count();

        // Children count by autism level
        $mild = Child::where('autism_level', 'Mild')->count();
        $moderate = Child::where('autism_level', 'Moderate')->count();
        $severe = Child::where('autism_level', 'Severe')->count();

        // --- مصفوفات إحصائيات آخر 7 أيام ---
        $complaintsData = [];
        $doctorRegistrationsData = [];
        $appointmentsChartData = [];
        $daysLabels = []; // نعرفها مرة واحدة فقط

        // Loop واحد يجمع بيانات الشكاوى، التسجيلات، والمواعيد مع بعض لتسريع الكود
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->format('D'); 

            $daysLabels[] = $dayName;
            
            // الشكاوى
            $complaintsData[] = Complaint::whereDate('created_at', $date)->count();
            
            // حساب عدد الأطباء الذين سجلوا في هذا اليوم
            $doctorRegistrationsData[] = DoctorProfile::whereDate('created_at', $date)->count();
            
            // حساب المواعيد التي تم حجزها في هذا اليوم
            $appointmentsChartData[] = Appointment::whereDate('date', $date)->count();
        }

        // doctor approval stats
        $approvalStats = DoctorProfile::select('approval_status', DB::raw('count(*) as total'))
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status') //دالة سحرية في لارافيل، تفلتر النتيجة المعقدة وتحولها لمصفوفة بسيطة جد
            ->toArray();

        // Send data to view
        return view('admin.dashboard', compact(
            'usersCount',
            'doctorsCount',
            'parentsCount',
            'childrenCount',
            'pendingRequestsCount',
            'mild',
            'moderate',
            'severe',
            'complaintsData',
            'approvalStats',
            'doctorRegistrationsData',
            'appointmentsChartData', // تمرير بيانات رسم المواعيد
            'daysLabels' // ممررة مرة واحدة فقط وتخدم كل الرسوم البيانية
        ));
    }
}