<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventsHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            '_id' => $this->_id,
            'eventId' => $this->event->_id,
            'system' => $this->system->global_prefix,
            'zone' => $this->zone,
            'type' => $this->type->event_code,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'apiUsed' => $this->api_used,
            'compiledSources' => $this->compiled_sources,
            'featureOn' => $this->feature_on,
            'featureOff' => $this->feature_off,
            'jiraCase' => $this->jira_case,
            'createdBy' => $this->created_by,
            'lastModifiedBy' => $this->last_modified_by,
            'status' => $this->status
        ];
    }
}
