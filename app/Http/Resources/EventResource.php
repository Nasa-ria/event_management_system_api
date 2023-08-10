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
            'details'=>$this->email,
            'time'=>$this->attendees,
            'status'=>$this->contact,
            'date'=>$this->date,
            'ticket_types_and_prices'=>$this->location,
         
        ] ;
    }
}
