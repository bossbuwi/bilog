<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

use App\Models\EventsHistory;
use App\Models\Event;

trait HistoryService
{
    public static function addHistory(string $dataType, int $modelId)
    {
        if ($dataType === config('constants.datatypes.event')) {
            $event = Event::findOrFail($modelId);
            $history = $event->history;
            if (!$history->isEmpty()) {
                $newHistory = new EventsHistory();
                $newHistory->event_id = $modelId;
                $newHistory->status = config('constants.status.update');
                $newHistory->created_by = $event->created_by;
                $newHistory->last_modified_by = $event->last_modified_by;
                $newHistory->system_id = $event->system_id;
                $newHistory->zone = $event->zone;
                $newHistory->type_id = $event->type_id;
                $newHistory->jira_case = $event->jira_case;
                $newHistory->api_used = $event->api_used;
                $newHistory->compiled_sources = $event->compiled_sources;
                $newHistory->feature_on = $event->feature_on;
                $newHistory->feature_off = $event->feature_off;
                $newHistory->start_date = $event->start_date;
                $newHistory->end_date = $event->end_date;
                $newHistory->save();
            } else {
                $newHistory = new EventsHistory();
                $newHistory->event_id = $modelId;
                $newHistory->status = config('constants.status.create');
                $newHistory->created_by = $event->created_by;
                $newHistory->last_modified_by = $event->created_by;
                $newHistory->system_id = $event->system_id;
                $newHistory->zone = $event->zone;
                $newHistory->type_id = $event->type_id;
                $newHistory->jira_case = $event->jira_case;
                $newHistory->api_used = $event->api_used;
                $newHistory->compiled_sources = $event->compiled_sources;
                $newHistory->feature_on = $event->feature_on;
                $newHistory->feature_off = $event->feature_off;
                $newHistory->start_date = $event->start_date;
                $newHistory->end_date = $event->end_date;
                $newHistory->save();
            }
        }
    }
}
