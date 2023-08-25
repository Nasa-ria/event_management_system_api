<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->event,
            'email' => $this->email,      
            'password' => $this->password, 
            'about'=>$this->about,
            'subscription_plan'=>$this->subscription_plan,
            'subscription_status'=>$this->subscription_status,
            'image'=>$this->image,
            'contact'=>$this->contact
        ];
    }
}
