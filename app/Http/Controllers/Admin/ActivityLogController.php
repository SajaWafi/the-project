<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
  public function index()
{
    // جلب السجلات مع بيانات المستخدم
    $logs = ActivityLog::with('user')->latest()->paginate(15);
    
    // التعديل هنا: نادوا الملف مباشرة من مجلد admin
    return view('admin.activity-logs', compact('logs'));
}
}