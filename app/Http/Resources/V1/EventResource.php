<?php

namespace App\Http\Resources\V1;

use App\Models\Boardroom;
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
        return [
            'id' => $this->id,
            'description' => $this->description,
            'starttime' => $this->starttime,
            'endtime' => $this->endtime,
            'boardroom_id' => $this->boardroom_id,
            'user_id' => $this->user_id
        ];
    }
}
