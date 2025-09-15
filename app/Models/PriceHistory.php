<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $fillable = [
        'real_estate_id',
        'date_modified',
        'price_gross',
        'price_net',
        'price_per_mkw',
        'price_per_mkw_net',
        'price_before_gross',
        'price_before_net',
        'price_before_per_mkw',
        'price_before_per_mkw_net',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'real_estate_id');
    }
}
