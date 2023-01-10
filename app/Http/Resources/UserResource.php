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
        return [
            'id' => $this->id,
            'reg_num' => $this->reg_num,
            'level' => $this->level,
            'name' => $this->name,
            'level_id' => $this->level_id,
            'created_at' => $this->created_at,
        ];
    }
}
