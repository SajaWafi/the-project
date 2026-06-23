<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogsActivity; // 💡 استدعاء الـ Trait

class ParentManagementController extends Controller
{
    use LogsActivity; // 💡 تفعيل الـ Trait داخل الكلاس

    // ---------------------------------------------------------
    // 1. دالة العرض (Index)
    // ---------------------------------------------------------
   public function index(Request $request)
   {
        $query = ParentProfile::with(['user', 'children'])->latest();

        // 💡 فلترة البحث: تبحث في اسم الأب أو اسم العائلة أو رقم الهاتف
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%");
            });
        }

        $parents = $query->paginate(10)->appends($request->query());

        return view('admin.parents_management', compact('parents'));
   }

    // ---------------------------------------------------------
    // 2. دالة التعديل (Update - تعديل ثلاثي الأبعاد)
    // ---------------------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'relation_to_child' => 'nullable|string|max:255',

            'child_name' => 'required|string|max:255',
            'child_gender' => 'nullable|in:Male,Female',
            'autism_level' => 'nullable|in:Mild,Moderate,Severe',
        ]);

        $parent = ParentProfile::with(['user', 'children'])->findOrFail($id);

        DB::beginTransaction();

        try {
            // أ. تحديث جدول الـ Users (البيانات الأساسية)
            if ($parent->user) {
                $parent->user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                ]);
            }
            // ب. تحديث جدول بروفايل ولي الأمر
            $parent->update([
                'relation_to_child' => $request->relation_to_child,
            ]);
            // ج. تحديث جدول الأطفال 
            // 💡 [First Record Assumption]: التعديل يطبق على أول طفل مسجل للأب
            $child = $parent->children->first();

            if ($child) {
                $child->update([
                    'name' => $request->child_name,
                    'gender' => $request->child_gender,
                    'autism_level' => $request->autism_level,
                ]);
            }

            // 💡 تسجيل الحركة (تعديل)
            $this->logActivity('تعديل ولي أمر', "قام الأدمن بتحديث بيانات ولي الأمر: {$request->first_name} {$request->last_name}");

            DB::commit();

            return back()->with('success', 'Parent updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // ---------------------------------------------------------
    // 3. دالة الحذف (Destroy)
    // ---------------------------------------------------------
    public function destroy($id)
    {
        $parent = ParentProfile::with(['user', 'children'])->findOrFail($id);

        DB::beginTransaction();

        try {
            // حفظ الاسم قبل الحذف باش نسجلوه في الـ Log
            $parentName = $parent->user ? $parent->user->first_name . ' ' . $parent->user->last_name : 'ولي أمر غير معروف';

            if ($parent->user) {
                $parent->user->delete();
            } else {
                $parent->delete();
            }

            // 💡 تسجيل الحركة (حذف)
            $this->logActivity('حذف ولي أمر', "قام الأدمن بحذف حساب ولي الأمر: {$parentName}");

            DB::commit();

            return back()->with('success', 'Parent deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // ---------------------------------------------------------
    // 4. دالة الإضافة (Store - الإدخال المتسلسل)
    // ---------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'gender' => 'nullable|in:Male,Female',
            'password' => 'required|string|min:6',

            'relation_to_child' => 'nullable|string|max:255',

            'child_name' => 'required|string|max:255',
            'child_gender' => 'nullable|in:Male,Female',
            'child_birth_date' => 'nullable|date',
            'autism_level' => 'nullable|in:Mild,Moderate,Severe',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'role' => 'parent',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'password' => Hash::make($request->password),
            ]);

            $parent = ParentProfile::create([
                'user_id' => $user->id,
                'relation_to_child' => $request->relation_to_child,
            ]);

            Child::create([
                'parent_id' => $parent->id,
                'name' => $request->child_name,
                'gender' => $request->child_gender,
                'birth_date' => $request->child_birth_date,
                'autism_level' => $request->autism_level,
            ]);

            // 💡 تسجيل الحركة (إضافة)
            $this->logActivity('إضافة ولي أمر', "قام الأدمن بإنشاء حساب جديد لولي الأمر: {$request->first_name} {$request->last_name}");

            DB::commit();

            return back()->with('success', 'Parent and child added successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}