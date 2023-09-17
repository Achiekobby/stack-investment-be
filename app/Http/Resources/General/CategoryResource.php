<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'category'=>$this->category,
            'description'=>$this->description,
            'image'=>config('services.url.image_path')."/".$this->image
        ];
    }
}
