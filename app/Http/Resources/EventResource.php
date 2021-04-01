<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'user' => $this->user,
            'system' => $this->system,
            'zone' => $this->zone,
            'type' => $this->type,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'apiUsed' => $this->api_used,
            'compiledSources' => $this->compiled_sources,
            'featureOn' => $this->feature_on,
            'featureOff' => $this->feature_off,
            'jiraCase' => $this->jira_case,
        ];
    }
}
