<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'title' => $this->title,
        //     'description' => $this->description,
        //     'user_id' => $this->user_id,
        //     'created_at' => $this->created_at->format('d/m/y'),
        //     'updated_at' => $this->updated_at->format('d/m/y'),
        // ];
    }
}
