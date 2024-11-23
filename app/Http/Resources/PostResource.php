<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'post_image' => $this->attachments[0]['attachment'],
            'post_name' => $this->name,
            'post_details' => $this->details,
            'num_offers'=>$this->offers()->count(),

        ];
    }
}
