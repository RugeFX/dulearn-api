<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
            'class' => $this->kelas,
            'subject' => $this->subject,
            'user' => $this->user,
            'title' => $this->title,
            'material' => $this->material,
            'created_at' => $this->created_at,
        ];
    }
}
