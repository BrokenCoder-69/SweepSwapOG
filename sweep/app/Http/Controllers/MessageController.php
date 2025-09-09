<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function send(Request $request, $receiverId)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $receiverId,
            'message' => $validated['message'],
        ]);
        return response()->json(['success' => true, 'message' => 'Message sent']);
    }

    public function conversation(Request $request, $userId)
    {
        $user = $request->user();

        $messages = Message::where(function ($query) use ($user, $userId) {
            $query->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($user, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }
}