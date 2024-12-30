<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurrentPrice extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'available_qty' => 'integer',
        'price' => 'float',
    ];

    public function marketPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->marketItems->pluck('price')->avg(),
        );
    }

    public function marketVolume(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->marketItems->pluck('quantity')->sum(),
        );
    }

    /**
     * Get all of the marketItems for the CurrentPrice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function marketItems(): HasMany
    {
        return $this->hasMany(MarketItem::class, 'produce_id');
    }
}
