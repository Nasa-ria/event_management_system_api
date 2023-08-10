<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'event_id'=>$this->event_id,
            'total_payment'=>$this->total_payment,
            'status'=>$this->status,
            'ticket_code'=>$this->ticket_code,
            'ticket_type'=>$this->ticket_type,
            'quantity'=>$this->qua
        ];
    }
}
