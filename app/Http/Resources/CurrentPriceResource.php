<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentPriceResource extends JsonResource
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
            'item' => $this->item,
            'unit' => $this->unit,
            'icon' => $this->icon,
            'available_qty' => $this->available_qty,
            'price' => $this->price,
            'market_price' => $this->marketPrice,
            'market_volume' => $this->marketVolume,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
