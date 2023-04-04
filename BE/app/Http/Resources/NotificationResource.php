<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'message' => $this->message,
            'is_read' => $this->is_read,
            'clinic' => $this->clinic,
            'sender' => $this->sender,
            'type' => $this->type,
            'created_at' => \Carbon\Carbon::parse($this->created_at)->format('d.m.Y H:i')
        ];
    }
}
