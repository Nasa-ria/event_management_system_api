<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'=>$this->id,
            'event' => $this->event,
            'details'=>$this->details,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,
            'status'=>$this->status,
            'date'=>$this->date,
            'capacity'=>$this->capacity,
            'ticket_types_and_prices'=>$this->location,
         
        ] ;
    }
}
