<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\DoctorProfile;
use App\Models\Message;
use App\Models\ParentProfile;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($parentId)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return back()->withErrors(['doctor' => 'Doctor profile not found.']);
        }

        $parent = ParentProfile::with(['user', 'children.doctors'])->findOrFail($parentId);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404);
        }

        $conversation = Conversation::firstOrCreate([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'child_id' => $linkedChild->id,
        ]);

        $parentName = trim(
            ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
        );

        if ($parentName === '') {
            $parentName = 'Parent';
        }

        $parentData = [
            'id' => $parent->id,
            'name' => $parentName,
            'image' => 'child.png',
        ];

        return view('doctor.chat', [
            'parent' => $parentData,
            'doctor' => $doctor,
            'conversation' => $conversation,
        ]);
    }

    public function messages($parentId)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 404);
        }

        $parent = ParentProfile::with(['children.doctors'])->findOrFail($parentId);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            return response()->json(['message' => 'Conversation not found.'], 404);
        }

        $conversation = Conversation::firstOrCreate([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'child_id' => $linkedChild->id,
        ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'message' => $message->message,
                    'type' => $message->type,
                    'time' => $message->created_at?->format('H:i'),
                    'is_me' => $message->user_id == auth()->id(),
                ];
            });

        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, $parentId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 404);
        }

        $parent = ParentProfile::with(['children.doctors'])->findOrFail($parentId);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            return response()->json(['message' => 'Conversation not found.'], 404);
        }

        $conversation = Conversation::firstOrCreate([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'child_id' => $linkedChild->id,
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'type' => 'text',
            'message' => $request->message,
            'file_path' => null,
            'read_at' => null,
        ]);

        return response()->json([
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'message' => $message->message,
                'type' => $message->type,
                'time' => $message->created_at?->format('H:i'),
                'is_me' => true,
            ]
        ]);
    }
}
