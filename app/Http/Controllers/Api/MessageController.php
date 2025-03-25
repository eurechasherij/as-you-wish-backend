<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatusEnum;
use App\Events\MessageSubmitEvent;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Message;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        return Message::with('event', 'sender')->where('event_id', $request->get('event_id', 1))->get();
    }

    public function store(Request $request)
    {
        $messageStatuses = implode(',', array_column(MessageStatusEnum::cases(), 'value'));
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'content' => 'required|string',
            'status' => 'required|in:' . $messageStatuses,
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'instagram' => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();

        if (!$sender) {
            $guest = Guest::firstOrCreate(
                ['email' => $request->email],
                ['name' => $request->name ?? 'Anonymous']
            );

            if ($request->instagram) {
                UserInfo::updateOrCreate(
                    ['model_id' => $guest->id, 'model_type' => Guest::class],
                    ['instagram' => $request->instagram]
                );
            }

            $sender = $guest;
        }

        $message = Message::create([
            'event_id' => $request->event_id,
            'content' => $request->content,
            'status' => $request->status,
            'sender_id' => $sender->id,
            'sender_type' => $sender->getMorphClass(),
        ]);

        return response()->json($message, 201);
    }

    public function show(Message $message)
    {
        return $message->load('event', 'sender');
    }

    public function update(Request $request, Message $message)
    {
        $request->validate([
            'content' => 'sometimes|string',
            'status' => 'sometimes|in:pending,visible',
        ]);

        $message->update($request->only(['content', 'status']));

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json(null, 204);
    }

    public function submitMessage(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $request->validate([
            'content' => 'required|string',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'instagram' => 'nullable|string|max:255',
        ]);

        if (Auth::check()) {
            $sender = Auth::user();
        } else {
            $sender = Guest::firstOrCreate(
                ['email' => $request->email],
                ['name' => $request->name ?? 'Anonymous']
            );

            if ($request->has('instagram')) {
                UserInfo::updateOrCreate(
                    ['model_id' => $sender->id, 'model_type' => $sender->getMorphClass()],
                    ['instagram' => $request->instagram]
                );
            }
        }

        $message = Message::create([
            'event_id' => $event->id,
            'content' => $request->content,
            'status' => MessageStatusEnum::VISIBLE, // TODO: change to review mode
            'displayed' => false,
            'sender_id' => $sender->id,
            'sender_type' => $sender->getMorphClass(),
        ]);

        MessageSubmitEvent::broadcast($message);

        return response()->json($message, 201);
    }
}
