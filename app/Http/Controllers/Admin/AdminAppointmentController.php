<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAppointmentController extends Controller
{
    // ---------------------------------------------------------
    // دالة العرض: تجيب المواعيد مرتبة زمنياً بذكاء
    // ---------------------------------------------------------
public function index(\Illuminate\Http\Request $request)
    {
        // 1. تحديد تاريخ اليوم الحالي
        $today = \Carbon\Carbon::today()->toDateString();

        // 💡 [Automated State Transition]: أي موعد فات تاريخه يتحول تلقائياً إلى "مكتمل"
        // شملنا الحالات (pending, scheduled, cancelled) باش الكود يصلح الداتا بيز توا تلقائياً
        \App\Models\Appointment::whereDate('date', '<', $today)
            ->whereIn('status', ['pending', 'scheduled', 'cancelled'])
            ->update(['status' => 'completed']);

        // 2. تجهيز الاستعلام الأساسي مع العلاقات
        $query = \App\Models\Appointment::with([
                'doctor.user',
                'parent.user',
                'child',
                'workplace',
            ]);

        // 3. فلترة البحث (مبنية على اسم الطفل بالدرجة الأولى)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('child', function($sub) use ($searchTerm) {
                    $sub->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('doctor.user', function($sub) use ($searchTerm) {
                    $sub->where('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('parent.user', function($sub) use ($searchTerm) {
                    $sub->where('first_name', 'like', "%{$searchTerm}%")
                        ->orWhere('last_name', 'like', "%{$searchTerm}%");
                });
            });
        }

        // 4. فلترة حالة الموعد
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // 5. فلترة تاريخ الموعد
        if ($request->filled('date_filter') && $request->date_filter !== 'all') {
            if ($request->date_filter === 'today') {
                $query->whereDate('date', $today);
            } elseif ($request->date_filter === 'upcoming') {
                $query->whereDate('date', '>', $today);
            } elseif ($request->date_filter === 'past') {
                $query->whereDate('date', '<', $today);
            }
        }

        // 6. الترتيب بالوقت والتاريخ، ثم التقسيم (Pagination)
        $appointments = $query->orderBy('date')
            ->orderByRaw("
                CASE 
                    WHEN from_period = 'AM' AND from_hour = 12 THEN 0
                    WHEN from_period = 'AM' THEN from_hour
                    WHEN from_period = 'PM' AND from_hour = 12 THEN 12
                    ELSE from_hour + 12
                END
            ")
            ->orderBy('from_minute')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.appointments_management', compact('appointments'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',

            'from_hour' => 'required|integer|min:1|max:12',
            'from_minute' => 'required|integer|in:0,15,30,45',
            'from_period' => 'required|in:AM,PM',

            'to_hour' => 'required|integer|min:1|max:12',
            'to_minute' => 'required|integer|in:0,15,30,45',
            'to_period' => 'required|in:AM,PM',

            'status' => 'required|in:pending,scheduled,completed,cancelled',
            'note' => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::findOrFail($id);

        DB::beginTransaction();

        try {
            $appointment->update([
                'date' => $request->date,

                'from_hour' => $request->from_hour,
                'from_minute' => $request->from_minute,
                'from_period' => $request->from_period,

                'to_hour' => $request->to_hour,
                'to_minute' => $request->to_minute,
                'to_period' => $request->to_period,

                'status' => $request->status,
                'note' => $request->note,
            ]);

            DB::commit(); // حفظ التعديلات نهائياً لو كل شيء تمام

            return back()->with('success', 'Appointment updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack(); // تراجع عن أي تغيير لو صار خطأ برمجي أو فصل السيرفر فجأة

            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->delete();

        return back()->with('success', 'Appointment deleted successfully.');
    }
}