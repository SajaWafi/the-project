<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // عرض صفحة الإعدادات
    public function index()
    {
        $admin = Auth::user(); 
        return view('admin.settings', compact('admin'));
    }

 // تحديث بيانات الحساب
    public function update(Request $request)
    {
        $admin = Auth::user(); 

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $admin->id,
            'phone'      => 'nullable|string|max:20',
            // إذا كتب باسورد جديد، لازم يكتب الحالي
            'current_password' => 'nullable|required_with:password',
            'password'   => 'nullable|min:8', 
        ]);

        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        // التحقق وتحديث كلمة المرور
        if ($request->filled('password')) {
            // التأكد من أن الباسورد الحالي صحيح
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The current password you entered is incorrect.']);
            }
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->back()->with('success', 'Your settings have been updated successfully.');
    }
// دالة إضافة أدمن جديد
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'phone'                 => 'nullable|string|max:20',
            'gender'                => 'required|in:Male,Female', // ⬅️ إضافة التحقق من الجنس
            'password'              => 'required|min:8|same:password_confirmation', // ⬅️ ربط الباسورد بالتأكيد
            'password_confirmation' => 'required|min:8', // ⬅️ حقل التأكيد
        ]);

        // إنشاء المستخدم الجديد وحفظه في الداتابيز
        $newAdmin = new \App\Models\User();
        $newAdmin->first_name = $request->first_name;
        $newAdmin->last_name = $request->last_name;
        $newAdmin->email = $request->email;
        $newAdmin->phone = $request->phone;
        $newAdmin->gender = $request->gender; // ⬅️ حفظ الجنس
        $newAdmin->password = Hash::make($request->password);
        $newAdmin->role = 'admin'; // تعيين الصلاحية كأدمن
        $newAdmin->save();

        return redirect()->back()->with('success', 'New Admin has been added successfully.');
    }

    // حذف الحساب
  public function destroy(Request $request)
    {
        $admin = Auth::user();

        // 1. نعدوا قداش فيه مدير في النظام توا
        $adminCount = \App\Models\User::where('role', 'admin')->count();

        // 2. لو هو أدمن، وهو الوحيد اللي قاعد، نمنعوا الحذف ونرجعوه برسالة خطأ
        if ($admin->role === 'admin' && $adminCount <= 1) {
            return back()->withErrors([
                'error' => 'Sorry, you cannot delete your account. At least one administrator must remain in the innovation system.'
            ]);
        }

        // 3. لو تخطى الشرط (يعني في مدراء غيره)، يكمل ينفذ كودك الأساسي للحذف
        Auth::logout();
        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Your account has been deleted.');
    }
}