<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;

class AdminAlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['parent.user', 'child'])
            ->latest('sent_at')
            ->latest()
            ->paginate(10);

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
