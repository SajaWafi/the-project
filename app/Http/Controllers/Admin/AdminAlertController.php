<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;

class AdminAlertController extends Controller
{
public function index(\Illuminate\Http\Request $request)
{
    // جلب التنبيهات مع العلاقات الأساسية
    $query = \App\Models\Alert::with(['parent.user', 'child'])->latest();

    // 💡 1. البحث النصي (يبحث في العنوان، المحتوى، اسم الطفل، أو اسم الأب)
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('message', 'like', "%{$searchTerm}%")
              ->orWhereHas('child', function($sub) use ($searchTerm) {
                  $sub->where('name', 'like', "%{$searchTerm}%");
              })
              ->orWhereHas('parent.user', function($sub) use ($searchTerm) {
                  $sub->where('first_name', 'like', "%{$searchTerm}%")
                      ->orWhere('last_name', 'like', "%{$searchTerm}%");
              });
        });
    }

    // 💡 2. فلترة نوع التنبيه (Panic, Heart Rate, الخ..)
    if ($request->filled('type') && $request->type !== 'all') {
        $query->where('alert_type', $request->type);
    }

    // 💡 3. فلترة حالة القراءة (مقروء / غير مقروء)
    if ($request->filled('read_status') && $request->read_status !== 'all') {
        $isRead = $request->read_status === 'read' ? 1 : 0;
        $query->where('is_read', $isRead);
    }

    // 💡 4. فلترة التاريخ (اليوم، أمس، هذا الأسبوع)
    if ($request->filled('date_filter') && $request->date_filter !== 'all') {
        $today = \Carbon\Carbon::today();
        
        if ($request->date_filter === 'today') {
            $query->whereDate('created_at', $today);
        } elseif ($request->date_filter === 'yesterday') {
            $query->whereDate('created_at', \Carbon\Carbon::yesterday());
        } elseif ($request->date_filter === 'week') {
            $query->where('created_at', '>=', now()->subWeek());
        }
    }

    // التقسيم لـ 15 سجل مع الحفاظ على الفلاتر في الروابط
    $alerts = $query->paginate(15)->appends($request->query());

    return view('admin.alerts_management', compact('alerts'));
}

    public function markRead($id)
    {
        $alert = Alert::findOrFail($id);

        $alert->update([
            'is_read' => true,
        ]);

        return back()->with('success', 'Alert marked as read.');
    }

    public function markUnread($id)
    {
        $alert = Alert::findOrFail($id);

        $alert->update([
            'is_read' => false,
        ]);

        return back()->with('success', 'Alert marked as unread.');
    }

    public function destroy($id)
    {
        $alert = Alert::findOrFail($id);
        $alert->delete();

        return back()->with('success', 'Alert deleted successfully.');
    }
}
