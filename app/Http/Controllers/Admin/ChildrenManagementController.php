<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChildrenManagementController extends Controller
{
    /**
     * عرض قائمة الأطفال مع بيانات أولياء أمورهم
     */
  public function index(Request $request)
{
    // جلب الأطفال مع بيانات أولياء أمورهم
    $query = \App\Models\Child::with('parentProfile.user')->latest();

    // 💡 فلترة البحث: تبحث بالاسم
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where('name', 'like', "%{$searchTerm}%");
    }

    // الـ appends تحافظ على الكلمة المكتوبة لما تنقزي للصفحة الثانية
    $children = $query->paginate(10)->appends($request->query());

    return view('admin.children_management', compact('children'));
}
    // دالة التعديل (Update)
    public function update($id, Request $request)
    {
        // : Data Validation - التحقق من صحة المدخلات وإجبار قيم معينة (Enums) مثل مستوى التوحد
        $request->validate([
            'name' => 'required|string|max:255',
            'autism_level' => 'required|in:Mild,Moderate,Severe',
            'gender' => 'required|in:Male,Female',
            'birth_date' => 'nullable|date',
        ]);
    //  Manual Error Handling - استخدام find بدلاً من findOrFail للتحكم في رسالة الخطأ المرجعة للواجهة
        $child = Child::find($id);

        if (!$child) {
            return back()->withErrors(['child' => 'Child not found.']);
        }

        $child->update([
            'name' => $request->name,
            'autism_level' => $request->autism_level,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ]);

        return back()->with('success', 'Child updated successfully.');
    }

    public function destroy($id)
    {
        $child = Child::findOrFail($id);
 // Exception Handling - استخدام try-catch لحماية النظام من الانهيار إذا كان الطفل مرتبطاً ببيانات أخرى في قاعدة البيانات
        try {
            $child->delete();
            return back()->with('success', 'Child deleted successfully from the system.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error deleting child: ' . $e->getMessage());
        }
    }
}