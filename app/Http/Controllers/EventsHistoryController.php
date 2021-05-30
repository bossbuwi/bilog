<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventsHistory;
use App\Http\Resources\EventsHistoryResource as EventsHistoryResource;

class EventsHistoryController extends Controller
{
    public function index()
    {
        $eventsHistory = EventsHistory::all();
        return EventsHistoryResource:: collection($eventsHistory);
    }

    public function getEventHistory(int $id)
    {
        $eventHistory = Event::findOrFail($id)->history;
        $currentEvent = Event::findOrFail($id);

        $latestHistory = new EventsHistory();
        $latestHistory->_id = 0;
        $latestHistory->event_id = $currentEvent->_id;
        $latestHistory->created_by = $currentEvent->created_by;
        $latestHistory->last_modified_by = $currentEvent->last_modified_by;
        $latestHistory->system_id = $currentEvent->system_id;
        $latestHistory->zone = $currentEvent->zone;
        $latestHistory->type_id = $currentEvent->type_id;
        $latestHistory->jira_case = $currentEvent->jira_case;
        $latestHistory->api_used = $currentEvent->api_used;
        $latestHistory->compiled_sources = $currentEvent->compiled_sources;
        $latestHistory->feature_on = $currentEvent->feature_on;
        $latestHistory->feature_off = $currentEvent->feature_off;
        $latestHistory->start_date = $currentEvent->start_date;
        $latestHistory->end_date = $currentEvent->end_date;
        if ($eventHistory->isEmpty()) {
            $latestHistory->status = config('constants.status.create');
        } else {
            $latestHistory->status = config('constants.status.current');
        }

        $eventHistory->push($latestHistory);

        $eventHistory = $eventHistory->reverse();

        return EventsHistoryResource::collection($eventHistory);
    }
}
