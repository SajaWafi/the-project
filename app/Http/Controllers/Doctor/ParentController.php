<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorProfile;
use App\Models\ParentProfile;
use App\Models\Appointment;
use App\Models\DoctorRequest;
use App\Models\MedicalNote; // 💡 إضافة الموديل الجديد للملاحظات
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // 💡 إضافة مكتبة الـ PDF

class ParentController extends Controller
{
    // عرض صفحة أولياء الأمور.
    public function index(Request $request)
    {
        // يجيب ملف الدكتور الحالي من قاعدة البيانات.
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }
        
        // يجلب قيمة البحث.
        $search = trim($request->search ?? '');

        $parents = ParentProfile::with(['user', 'children.doctors'])
            ->get()
            // لإخفاء أولياء الأمور غير المرتبطين بالدكتور الحالي filter
            ->filter(function ($parent) use ($doctor, $search) {
                $linkedChildren = $parent->children->filter(function ($child) use ($doctor) {
                    return $child->doctors->contains('id', $doctor->id);
                });

                if ($linkedChildren->isEmpty()) {
                    return false;
                }

                if ($search === '') {
                    return true;
                }
                
                // بحث باسم الأب.
                $parentName = strtolower(trim(
                    ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
                ));
            
                $matchesParent = str_contains($parentName, strtolower($search));
                
                // بحث باسم الطفل.
                $matchesChild = $linkedChildren->contains(function ($child) use ($search) {
                    return str_contains(strtolower($child->name ?? ''), strtolower($search));
                });

                return $matchesParent || $matchesChild;
            })
            // تحويل البيانات لشكل جاهز للواجهة. map
            ->map(function ($parent) use ($doctor) {
                $linkedChild = $parent->children->first(function ($child) use ($doctor) {
                    return $child->doctors->contains('id', $doctor->id);
                });

                $parentName = trim(
                    ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
                );

                if ($parentName === '') {
                    $parentName = 'No Name';
                }

                return [
                    'id' => $parent->id,
                    'user_id' => $parent->user_id, // 💡 ضروري للشات
                    'name' => $parentName,
                    'subtitle' => $linkedChild
                        ? $linkedChild->name . "'s parent"
                        : 'No child linked',
                    'image' => $parent->user->profile_image ?? null,
                ];
            })
            ->values();

        return view('doctor.parents', compact('parents', 'search'));
    }

    // فتح ملف ولي الأمر (تمت إضافة جلب الملاحظات الطبية)
    public function show($id)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $parent = ParentProfile::with(['user', 'children.doctors'])->findOrFail($id);
        
        $workplaces = \App\Models\Workplace::where('doctor_id', $doctor->id)->get();

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404);
        }

        $parentName = trim(
            ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
        );

        if ($parentName === '') {
            $parentName = 'No Name';
        }
        
        // حساب العمر
        $childAge = $linkedChild->birth_date
            ? Carbon::parse($linkedChild->birth_date)->age . ' years'
            : 'Not available';

        $data = [
            'id' => $parent->id,
            'user_id' => $parent->user_id, // 💡 يمرر للشات
            'child_id' => $linkedChild->id, // 💡 يمرر لإضافة الملاحظات
            'name' => $parentName,
            'subtitle' => $linkedChild->name . "'s parent",
            'image' => $parent->user->profile_image ?? null,
            'phone' => $parent->user->phone ?? 'No Phone',
            'autism_level' => $linkedChild->autism_level ?? 'Not set',
            'age' => $childAge,
        ];

        $appointments = Appointment::with(['child'])
            ->where('doctor_id', $doctor->id)
            ->where('parent_id', $parent->id)
            ->where('child_id', $linkedChild->id)
            ->whereDate('date', '>=', now()->toDateString())
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

        // 💡 جلب الملاحظات السريرية لهذا الطفل من هذا الدكتور
        $medicalNotes = MedicalNote::with('doctor.user')
            ->where('doctor_id', $doctor->id)
            ->where('child_id', $linkedChild->id)
            ->orderBy('created_at', 'desc')
            ->get();

            // جلب المهام المنزلية لهذا الطفل
        $homeTasks = \App\Models\HomeTask::where('child_id', $linkedChild->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.parent-profile', [
            'parent' => $data,
            'appointments' => $appointments,
            'medicalNotes' => $medicalNotes, // 💡 تم التمرير للـ View
            'linkedChild' => $linkedChild,
            'doctor' => ['id' => $doctor->id],
            'workplaces' => $workplaces,
            'homeTasks' => $homeTasks
        ]);
    }

    // 💡 دالة حفظ الملاحظة السريرية الجديدة
    public function storeNote(Request $request)
    {
        $request->validate([
            'child_id'  => 'required|exists:children,id',
            'note_text' => 'required|string|max:1000',
        ]);

        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        MedicalNote::create([
            'doctor_id' => $doctor->id,
            'child_id'  => $request->child_id,
            'note_text' => $request->note_text,
            'is_shared' => $request->has('is_shared') ? true : false,
        ]);

        return back()->with('success', __('Clinical note saved successfully.'));
    }

    // 💡 دالة تصدير الملاحظات كملف PDF
    public function exportPdf($parentId)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();
        $parent = ParentProfile::with(['user', 'children'])->findOrFail($parentId);
        
        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404, "Child not found for this parent.");
        }

        $medicalNotes = MedicalNote::with('doctor.user')
            ->where('doctor_id', $doctor->id)
            ->where('child_id', $linkedChild->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // تمرير البيانات لملف تصميم الـ PDF
       // تمرير البيانات لملف تصميم الـ PDF مع تفعيل سحب الخطوط العربية
$pdf = Pdf::setOptions([
    'isRemoteEnabled' => true,
    'isHtml5ParserEnabled' => true,
])->loadView('doctor.medical_report', compact('parent', 'linkedChild', 'medicalNotes', 'doctor'));

        // تحديد اسم الملف المُنزل
        $fileName = 'Medical_Report_' . str_replace(' ', '_', $linkedChild->name) . '.pdf';

        return $pdf->download($fileName);
    }

    // فتح صفحة المحادثة.
    public function chat($id)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $parent = ParentProfile::with(['user', 'children.doctors'])->findOrFail($id);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404);
        }

        $parentName = trim(
            ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
        );

        if ($parentName === '') {
            $parentName = 'Parent';
        }

        $data = [
            'id' => $parent->id,
            'name' => $parentName,
            'image' => 'child.png',
        ];

        return view('doctor.chat', ['parent' => $data]);
    }

    public function searchAjax(Request $request)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return response()->json([
                'parents' => [],
                'message' => 'Doctor profile not found.'
            ], 404);
        }

        $search = trim($request->search ?? '');

        $parents = ParentProfile::with(['user', 'children.doctors'])
            ->get()
            ->filter(function ($parent) use ($doctor, $search) {
                $linkedChildren = $parent->children->filter(function ($child) use ($doctor) {
                    return $child->doctors->contains('id', $doctor->id);
                });

                if ($linkedChildren->isEmpty()) {
                    return false;
                }

                if ($search === '') {
                    return true;
                }

                $parentName = strtolower(trim(
                    ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
                ));

                $matchesParent = str_contains($parentName, strtolower($search));

                $matchesChild = $linkedChildren->contains(function ($child) use ($search) {
                    return str_contains(strtolower($child->name ?? ''), strtolower($search));
                });

                return $matchesParent || $matchesChild;
            })
            ->map(function ($parent) use ($doctor) {
                $linkedChild = $parent->children->first(function ($child) use ($doctor) {
                    return $child->doctors->contains('id', $doctor->id);
                });

                $parentName = trim(
                    ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
                );

                if ($parentName === '') {
                    $parentName = 'No Name';
                }

                return [
                    'id' => $parent->id,
                    'name' => $parentName,
                    'subtitle' => $linkedChild
                        ? $linkedChild->name . "'s parent"
                        : 'No child linked',
                    'image' => !empty($parent->user->profile_image)
                    ? asset('storage/' . ltrim($parent->user->profile_image, '/'))
                    : asset('images/default-user.png'),
                    'profile_url' => route('doctor.parent.profile', ['id' => $parent->id]),
                    'chat_url' => route('doctor.chat', ['parentId' => $parent->id]),
                ];
            })
            ->values();

        return response()->json([
            'parents' => $parents,
        ]);
    }

    // إلغاء ربط ولي الأمر بالدكتور.
    public function removeParent($parentId)
    {
        $doctorProfile = auth()->user()->doctorProfile;

        if (!$doctorProfile) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $parent = ParentProfile::with('children')->findOrFail($parentId);
        $childIds = $parent->children->pluck('id');

        DB::table('child_doctor')
            ->where('doctor_id', $doctorProfile->id)
            ->whereIn('child_id', $childIds)
            ->delete();

        DoctorRequest::where('doctor_id', $doctorProfile->id)
            ->where('parent_id', $parentId)
            ->delete();

        return redirect()->route('doctor.parents')->with('success', 'تم حذف ولي الأمر بنجاح.');
    }
    // 1. عرض صفحة الملاحظات المستقلة
    public function notesIndex($id)
    {
        $doctor = \App\Models\DoctorProfile::where('user_id', auth()->id())->firstOrFail();
        $parent = \App\Models\ParentProfile::with(['user', 'children'])->findOrFail($id);
        
        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) abort(404);

        $medicalNotes = \App\Models\MedicalNote::with('doctor.user')
            ->where('doctor_id', $doctor->id)
            ->where('child_id', $linkedChild->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.medical-notes', compact('parent', 'linkedChild', 'medicalNotes'));
    }

    // 2. تعديل ملاحظة
    public function updateNote(Request $request, $id)
    {
        $request->validate(['note_text' => 'required|string|max:1000']);
        
        $note = \App\Models\MedicalNote::findOrFail($id);
        $note->update([
            'note_text' => $request->note_text,
            'is_shared' => $request->has('is_shared') ? true : false,
        ]);

        return back()->with('success', __('Note updated successfully.'));
    }

    // 3. حذف ملاحظة
    public function destroyNote($id)
    {
        $note = \App\Models\MedicalNote::findOrFail($id);
        $note->delete();

        return back()->with('success', __('Note deleted successfully.'));
    }

    // دالة حفظ المهمة المنزلية
    public function storeTask(Request $request)
    {
        $request->validate([
            'child_id' => 'required|exists:children,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        \App\Models\HomeTask::create([
            'doctor_id' => $doctor->id,
            'child_id' => $request->child_id,
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => false,
        ]);

        return back()->with('success', 'Task added successfully.');
    }
    // عرض صفحة المهام المستقلة
    public function tasksIndex($id)
    {
        $doctor = \App\Models\DoctorProfile::where('user_id', auth()->id())->firstOrFail();
        $parent = \App\Models\ParentProfile::with(['user', 'children'])->findOrFail($id);
        
        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) abort(404);

        $homeTasks = \App\Models\HomeTask::where('child_id', $linkedChild->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.home-tasks', compact('parent', 'linkedChild', 'homeTasks'));
    }
    // دالة تعديل المهمة المنزلية
    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = \App\Models\HomeTask::findOrFail($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', __('Task updated successfully.'));
    }

    // دالة حذف المهمة المنزلية
    public function destroyTask($id)
    {
        $task = \App\Models\HomeTask::findOrFail($id);
        $task->delete();

        return back()->with('success', __('Task deleted successfully.'));
    }
}