<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\DoctorProfile;
use App\Models\Message;
use App\Models\ParentProfile;
use App\Models\Notification; // تم إضافة مودل الإشعارات هنا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function show($parentId)
    {
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            abort(403, 'Doctor profile not found.');
        }

        $parent = ParentProfile::with('user', 'children.doctors')->findOrFail($parentId);

        $linkedChild = $parent->children->first(function ($child) use ($doctor) {
            return $child->doctors->contains('id', $doctor->id);
        });

        if (!$linkedChild) {
            abort(404, 'Parent is not linked to this doctor.');
        }

        $conversation = Conversation::firstOrCreate([
            'doctor_id' => $doctor->id,
            'parent_id' => $parent->id,
            'child_id'  => $linkedChild->id,
        ]);

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $parentName = trim(
            ($parent->user->first_name ?? '') . ' ' . ($parent->user->last_name ?? '')
        );

        if ($parentName === '') {
            $parentName = 'Parent';
        }

        return view('doctor.chat', [
            'parent' => [
                'id' => $parent->id,
                'name' => $parentName,
                'image' => $parent->user->profile_image ?? null,
            ],
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, $parentId)
    {
        try {
            $request->validate([
                'message' => 'nullable|string|max:2000',
                'file' => 'nullable|file|max:10240',
            ]);

            $doctor = DoctorProfile::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return response()->json(['message' => 'Doctor profile not found.'], 403);
            }

            $parent = ParentProfile::with('children.doctors')->findOrFail($parentId);

            $linkedChild = $parent->children->first(function ($child) use ($doctor) {
                return $child->doctors->contains('id', $doctor->id);
            });

            if (!$linkedChild) {
                return response()->json(['message' => 'Parent is not linked to this doctor.'], 404);
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

            // --- إضافة إشعار لولي الأمر ---
            $notifyMessage = 'Sent you a new message.';
            if ($type == 'image') {
                $notifyMessage = 'Sent you an image.';
            } elseif ($type == 'file') {
                $notifyMessage = 'Sent you a file.';
            }

            Notification::create([
                'user_id' => $parent->user_id, // توجيه الإشعار لحساب ولي الأمر
                'related_id' => auth()->user()->doctorProfile->id,
                'title' => 'New Message from Dr. ' . auth()->user()->first_name,
                'message' => $notifyMessage,
                'type' => 'chat_message',
            ]);
            // -----------------------------

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

    public function sendAudio(Request $request, $parentId)
    {
        try {
            $request->validate([
                'audio' => 'required|file|max:10240',
            ]);

            $doctor = DoctorProfile::where('user_id', auth()->id())->first();

            if (!$doctor) {
                return response()->json(['message' => 'Doctor profile not found.'], 403);
            }

            $parent = ParentProfile::with('children.doctors')->findOrFail($parentId);

            $linkedChild = $parent->children->first(function ($child) use ($doctor) {
                return $child->doctors->contains('id', $doctor->id);
            });

            if (!$linkedChild) {
                return response()->json(['message' => 'Parent is not linked to this doctor.'], 404);
            }

            $conversation = Conversation::firstOrCreate([
                'doctor_id' => $doctor->id,
                'parent_id' => $parent->id,
                'child_id'  => $linkedChild->id,
            ]);

            $audioFile = $request->file('audio');
            $filePath = $audioFile->store('chat-audio', 'public');

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => auth()->id(),
                'type' => 'audio',
                'message' => null,
                'file_path' => $filePath,
                'read_at' => null,
            ]);

            // --- إضافة إشعار بصمة صوت لولي الأمر ---
            Notification::create([
                'user_id' => $parent->user_id, // توجيه الإشعار لحساب ولي الأمر
                'title' => 'New Message from Dr. ' . auth()->user()->first_name,
                'message' => 'Sent you a voice message.',
                'type' => 'chat_message',
            ]);
            // ---------------------------------------

            return response()->json([
                'id' => $message->id,
                'type' => 'audio',
                'file_url' => asset('storage/' . $filePath),
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
        $doctor = DoctorProfile::where('user_id', auth()->id())->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found.'], 403);
        }

        $message = Message::findOrFail($messageId);

        $conversation = Conversation::where('id', $message->conversation_id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$conversation) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($message->file_path) {
            Storage::disk('public')->delete($message->file_path);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }
}
