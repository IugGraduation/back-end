<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'image' => $this->image,
            'posts' => $this->posts()->count(),
            'offers' => $this->offers()->count(),
            'name' => $this->name,
            'mobile' => $this->mobile,
            'place' => $this->place,
            'bio' => $this->bio,

        ];
    }
}
