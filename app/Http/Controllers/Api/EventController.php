<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ori: return Event::all();
        //json 往上會多一層 data[]array
        // return EventResource::collection(Event::all());
        return EventResource::collection(Event::with('user')->get());

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time'  => 'required|date',
                'end_time'    => 'required|date|after:start_time',

            ]),
            'user_id' => 1,
        ]);

        // ori:return $event;
        //json 往上會多一層 data[]array
        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // ori:return $event;
        //json 往上會多一層 data[]array
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
            $request->validate([
                'name'        => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time'  => 'sometimes|date',
                'end_time'    => 'sometimes|date|after:start_time',

            ]));

        // ori:return $event;
        //json 往上會多一層 data[]array
        return new EventResource($event);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        // return response()->json([
        //     'message' => 'Event deleted successfully.',
        // ]);
        return response(status: 204);
    }
}
