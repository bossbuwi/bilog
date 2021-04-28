<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'length' => $this->length,
            'description' => $this->description,
            'validValues' => $this->valid_values,
            'defaultValue' => $this->default_value,
            'lastModifiedBy' => $this->last_modified_by,
        ];
    }
}
