<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $links = [];
        if ($this->link_1 != "") $links[] = $this->link_1;
        if ($this->link_2) $links[] = $this->link_2;
        if ($this->link_3) $links[] = $this->link_3;
        if ($this->link_4) $links[] = $this->link_4;
        if ($this->link_5) $links[] = $this->link_5;
        if ($this->link_6) $links[] = $this->link_6;
        if ($this->link_7) $links[] = $this->link_7;

        return [
            'id' => $this->id,
            'pr_id' => $this->pr_id,
            'state' => $this->state,
            'city' => $this->city,
            'date' => $this->date,
            'title' => $this->title,
            'description' => $this->description,
            'links' => $links,
            'data' => $this->data,
            'geocoding' => [
                'lat' => $this->lat,
                'long' => $this->long,
            ]
        ];
    }
}
