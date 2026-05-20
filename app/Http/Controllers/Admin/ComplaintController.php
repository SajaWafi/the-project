<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string',
        ]);

        Complaint::create([
            'user_id' => auth()->id(),
            'category' => $request->category,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back();
    }

    public function index()
    {
        $complaints = Complaint::with('user')->latest()->get();

        return view('admin.complaints_managment', compact('complaints'));
    }

    public function destroy($id)
    {
        Complaint::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Complaint deleted');
    }
}