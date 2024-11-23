<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'user_name' => $this->user->name,
            'user_image' => $this->user->image,
            'image' => $this->image,
            'title' => $this->title,
            'details' => $this->details,
        ];
    }
}
