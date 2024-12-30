<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'qty' => $this->quantity . ' ' . str($this->produce?->unit ?: $this->quantity_unit)->apa(),
            'name' => $this->user->fullname ?? 'Unknown',
            'type' => $this->type,
            'grade' => $this->grade,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'quantity_unit' => $this->produce?->unit ?: $this->quantity_unit,
            'prod_img' => $this->media_file,
            'image_url' => $this->media_file,
            'product_name' => $this->name,
            'location' => $this->location,
            'address' => $this->address,
            'active' => $this->active,
            'approved' => $this->approved,
            'avatar' => $this->user->avatar ?? $this->image_url,
            'user_id' => $this->user->id,
            'username' => $this->user->username,
            'produce' => $this->produce?->item,
            'produce_id' => $this->produce?->id,
        ];
    }
}
