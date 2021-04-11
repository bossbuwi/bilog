<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemResource extends JsonResource
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
            'globalPrefix' => $this->global_prefix,
            'machine' => $this->machine,
            'zone1Prefix' => $this->zone1_prefix,
            'zone1Name' => $this->zone1_name,
            'zone2Prefix' => $this->zone2_prefix,
            'zone2Name' => $this->zone2_name,
            'loginNames' => $this->login,
            'systemAdmin' => $this->sysadmin,
            'systemUrl' => $this->url
        ];
    }
}
