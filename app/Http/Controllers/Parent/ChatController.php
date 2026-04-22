<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\DoctorProfile;
use App\Models\Message;
use App\Models\ParentProfile;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function show($doctorId)
    {
        $parent = ParentProfile::with('children.doctors')
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            abort(403, 'Parent profile not found.');
        }

        $doctor = DoctorProfile::with('user')->findOrFail($doctorId);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404, 'Doctor is not linked to this parent.');
        }

        $conversation = Conversation::firstOrCreate([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'child_id'  => $linkedChild->id,
        ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $doctorName = trim(
            ($doctor->user->first_name ?? '') . ' ' . ($doctor->user->last_name ?? '')
        );

        if ($doctorName === '') {
            $doctorName = 'Doctor';
        }

        return view('parents.chat', [
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctorName,
                'image' => $doctor->user->profile_image ?? null,
            ],
            'messages' => $messages,
        ]);
    }

public function send(Request $request, $doctorId)
{
    try {
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'file' => 'nullable|file|max:10240',
        ]);

        $parent = ParentProfile::with('children.doctors')
            ->where('user_id', auth()->id())
            ->first();

        if (!$parent) {
            return response()->json(['message' => 'Parent profile not found.'], 403);
        }

        $doctor = DoctorProfile::findOrFail($doctorId);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            return response()->json(['message' => 'Doctor is not linked to this parent.'], 404);
        }

        if (!$request->filled('message') && !$request->hasFile('file')) {
            return response()->json(['message' => 'Message or file is required.'], 422);
        }

        $conversation = Conversation::firstOrCreate([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'child_id'  => $linkedChild->id,
        ]);

        $type = 'text';
        $filePath = null;
        $fileName = null;
        $messageText = $request->message;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('chat-files', 'public');
            $fileName = $file->getClientOriginalName();

            if (str_starts_with($file->getMimeType(), 'image/')) {
                $type = 'image';
            } else {
                $type = 'file';
            }
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'message' => $messageText,
            'file_path' => $filePath,
            'read_at' => null,
        ]);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'type' => $message->type,
            'file_url' => $message->file_path ? asset('storage/' . $message->file_path) : null,
            'file_name' => $fileName,
            'time' => $message->created_at->format('H:i'),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ], 500);
    }
}
public function deleteMessage($messageId)
{
    $parent = ParentProfile::where('user_id', auth()->id())->first();

    if (!$parent) {
        return response()->json(['message' => 'Parent profile not found.'], 403);
    }

    $message = Message::findOrFail($messageId);

    $conversation = Conversation::where('id', $message->conversation_id)
        ->where('parent_id', $parent->id)
        ->first();

    if (!$conversation) {
        return response()->json(['message' => 'Unauthorized.'], 403);
    }

    if ($message->file_path) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($message->file_path);
    }

    $message->delete();

    return response()->json(['success' => true]);
}
}