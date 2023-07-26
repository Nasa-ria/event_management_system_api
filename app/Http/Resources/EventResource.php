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
            'email'=>$this->email,
            'attendees'=>$this->attendees,
            'contact'=>$this->contact,
            'date'=>$this->date,
            'location'=>$this->location,
            'user_id'=>$this->user_id
        ] ;
    }
}
