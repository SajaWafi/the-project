<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;
use App\Models\Appointment;
use App\Models\Workplace;
use Illuminate\Support\Facades\DB;
use App\Models\DoctorRequest;

class DoctorController extends Controller
{   //عرض جميع الأطباء المرتبطين بأطفال الأب.
    public function index()
    {
        $parent = ParentProfile::with(['children.doctors.user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }
        //باش نجمع فيها الأطباء.
        $doctors = collect();

        foreach ($parent->children as $child) {
            foreach ($child->doctors as $doctor) {
                $doctorName = trim(
                    ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
                );

                if ($doctorName === '') {
                    $doctorName = 'No Name';
                }
            //تخزين بيانات الدكتور
                $doctors->push([
                    'id' => $doctor->id,
                    'name' => $doctorName,
                    'specialty' => $doctor->specialization ?? 'No Specialty',
                    'image' => $doctor->user->profile_image ?? null,
                ]);
            }
        }

        $doctors = $doctors->unique('id')->values();

        return view('parents.doctors', compact('doctors'));
    }
    //عرض الملف الشخصي لدكتور معين.
    public function show($id)
    {
        $parent = ParentProfile::with(['children.doctors.user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $doctor = DoctorProfile::with('user')->findOrFail($id);

        $isLinked = $parent->children->contains(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$isLinked) {
            abort(404);
        }
  
        $doctorName = trim(
            ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
        );

        if ($doctorName === '') {
            $doctorName = 'No Name';
        }
        //تجهيز البيانات
        $data = [
            'id' => $doctor->id,
            'name' => $doctorName,
            'specialty' => $doctor->specialization ?? 'No Specialty',
            'image' => $doctor->user->profile_image ?? null,
            'phone' => $doctor->user->phone ?? 'No Phone',
            'bio' => $doctor->bio ?? 'No bio available',
        ];

        $child = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });
        //جلب المواعيد
        $appointments = collect();

        if ($child) {
            $appointments = Appointment::with(['child', 'doctor.user'])
                ->where('parent_id', $parent->id)
                ->where('doctor_id', $doctor->id)
                ->where('child_id', $child->id)
                ->whereDate('date', '>=', now()->toDateString()) //المواعيد القادمة فقط.
                ->orderBy('date')
                ->orderByRaw("
                    CASE 
                        WHEN from_period = 'AM' AND from_hour = 12 THEN 0
                        WHEN from_period = 'AM' THEN from_hour
                        WHEN from_period = 'PM' AND from_hour = 12 THEN 12
                        ELSE from_hour + 12
                    END
                ")
                ->orderBy('from_minute')
                ->get();
        }
       // جلب أماكن العمل
        $workplaces = Workplace::where('doctor_id', $doctor->id)
            ->latest()
            ->get();

        return view('parents.doctor-profile', [
            'doctor' => $data,
            'appointments' => $appointments,
            'child' => $child,
            'workplaces' => $workplaces,
        ]);
    }
    //فتح صفحة المحادثة
    public function chat($id)
    {
        $parent = ParentProfile::with(['children.doctors.user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $doctor = DoctorProfile::with('user')->findOrFail($id);

        $isLinked = $parent->children->contains(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$isLinked) {
            abort(404);
        }

        $doctorName = trim(
            ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
        );

        if ($doctorName === '') {
            $doctorName = 'Doctor';
        }

        $data = [
            'id' => $doctor->id,
            'name' => $doctorName,
            'image' => $doctor->user->profile_image ?? null,
        ];
        //فتح صفحة الشات
        return view('parents.chat', ['doctor' => $data]);
    }
    //فك الارتباط بالدكتور
    public function removeDoctor($doctorId)
    {
        $parentProfile = auth()->user()->parentProfile;

        if (!$parentProfile) {
            return back()->withErrors(['parent' => 'Parent profile not found.']);
        }

        $childIds = $parentProfile->children->pluck('id');

        DB::table('child_doctor')
            ->where('doctor_id', $doctorId)
            ->whereIn('child_id', $childIds)
            ->delete();

        DoctorRequest::where('doctor_id', $doctorId)
            ->where('parent_id', $parentProfile->id)
            ->delete();

        return redirect()->route('doctors')->with('success', 'The doctor was deleted and the link was successfully removed.');
    }

    // عرض مهام الخطة العلاجية للأهل
    public function doctorTasks($id)
    {
        $doctor = \App\Models\DoctorProfile::with('user')->findOrFail($id);
        
        // جلب بروفايل ولي الأمر والطفل المرتبط بيه
        $parent = \App\Models\ParentProfile::where('user_id', auth()->id())->firstOrFail();
        $child = \App\Models\Child::where('parent_id', $parent->id)->first(); 

        if (!$child) {
            abort(404, 'No child found for this parent.');
        }

        // جلب المهام وترتيبها (غير المكتملة تطلع فوق)
        $tasks = \App\Models\HomeTask::where('child_id', $child->id)
            ->orderBy('is_completed', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('parents.doctor-tasks', compact('doctor', 'tasks'));
    }

    // تغيير حالة المهمة (تم / لم يتم)
    public function toggleTask($id)
    {
        $task = \App\Models\HomeTask::findOrFail($id);
        
        // عكس الحالة (لو 0 تولي 1، والعكس)
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return back();
    }
    
}