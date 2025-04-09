<?php

namespace App\Http\Controllers\Api;

use App\Enums\MessageStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\DisplayEventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        return Event::with('creator')->where('created_by', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:events,slug',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        if ($request->has('slug') && !empty($request->slug)) {
            $request->merge([
                'slug' => Str::slug($request->slug),
            ]);
        } else {
            $slug = Event::where('slug', Str::slug($request->event_name))->first();

            if ($slug) {
                $request->merge([
                    'slug' => Str::slug($request->event_name) . '-' . $slug->id,
                ]);
            } else {
                $request->merge([
                    'slug' => Str::slug($request->event_name),
                ]);
            }
        }

        $event = Event::create([
            ...$request->only(['event_name', 'slug', 'description', 'event_date']),
            'code' => Str::uuid(),
            'created_by' => Auth::id(),
        ]);

        return response()->json($event, 201);
    }

    public function show(Event $event)
    {
        return $event->load('creator', 'messages');
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'event_name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:events,slug,' . $event->id,
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
        ]);

        $event->update($request->only(['event_name', 'slug', 'description', 'event_date']));

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(null, 204);
    }

    public function showEventPage($slug)
    {
        $event = Event::where('slug', $slug)->select('id', 'event_name', 'slug')->firstOrFail();

        $event->load(['messages' => function ($query) {
            $query->with('sender.userInfo')->where('displayed', false)->where('status', MessageStatusEnum::VISIBLE)->orderBy('created_at', 'desc')->limit(10);
        }]);

        return DisplayEventResource::make($event);
    }
}
