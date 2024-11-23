<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditPostResource extends JsonResource
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
            'images' => $this->attachments,
            'name' => $this->name,
            'details' => $this->details,
            'place' => $this->place,
            'status'=>$this->status_name,
            'category_uuid'=>$this->category_uuid,
            'category_name' => $this->category_name,
            'fav_categories'=>$this->categories()->select('category_uuid')->get()

        ];
    }
}
