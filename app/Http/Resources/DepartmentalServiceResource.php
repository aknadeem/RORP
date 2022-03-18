<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DepartmentalServiceResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // return [
        //     'user' => UserResource::make($this->whenLoaded('user')),
        //     'images' => ImageResource::collection($this->whenLoaded('images')),
        //     'tags' => TagResource::collection($this->whenLoaded('tags')),
        //     'featured_image' => ImageResource::make($this->whenLoaded('featuredImage')),
        //     'reservations_count' => $this->resource->reservations_count ?? 0,

        //     $this->merge(Arr::except(parent::toArray($request), [
        //         'created_at', 'updated_at'
        //     ]))
        // ];
    }
}