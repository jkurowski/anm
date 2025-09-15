<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Notifications\Notifiable;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class Property extends Model
{
    use LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'investment_id',
        'building_id',
        'floor_id',
        'vox_id',
        'status',
        'name',
        'name_list',
        'number',
        'number_order',
        'type',
        'type_vox',
        'rooms',
        'area',
        'area_search',
        'price',
        'price_brutto',
        'ask_for_price',
        'additional',
        'garden_area',
        'balcony_area',
        'balcony_area_2',
        'terrace_area',
        'loggia_area',
        'parking_space',
        'garage',
        'storeroom',
        'deadline',
        'kitchen_type',
        'type',
        'html',
        'cords',
        'file',
        'file_3d',
        'file_pdf',
        'file_webp',
        'meta_title',
        'meta_description',
        'views',
        'active'
    ];

    public function priceHistory($date = '2025-09-11')
    {
        return $this->hasMany(PriceHistory::class, 'real_estate_id')
            ->where('date_modified', '>', $date)
            ->orderBy('date_modified', 'desc'); // newest first
    }

    /**
     * Get next property
     * @param int $investment
     * @param int $id
     * @return Property
     */
    public function findNext(int $investment, int $id, ?int $floor_id = null)
    {

        $query = $this->where('investment_id', $investment)->where('number_order', '=', $id + 1);

        if (!is_null($floor_id)) {
            $query->where('floor_id', $floor_id);
        }

        return $query->first();
    }

    /**
     * Get previous property
     * @param int $investment
     * @param int $id
     * @return Property
     */
    public function findPrev(int $investment, int $id, ?int $floor_id = null)
    {
        $query = $this->where('investment_id', $investment)->where('number_order', '=', $id -1)->orderByDesc('number_order');

        if (!is_null($floor_id)) {
            $query->where('floor_id', $floor_id);
        }

        return $query->first();
    }

    /**
     * Get notifications for room
     * @return HasMany
     */
    public function roomsNotifications(): HasMany
    {
        return $this->hasMany(
            'App\Models\Notification',
            'notifiable_id',
            'id'
        )->where('notifiable_type', 'App\Models\Property')->latest();
    }

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        $logOptions->useLogName('Powierzchnia');
        $logOptions->logFillable();

        return $logOptions;
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    // Define an accessor for the URL
    public function getUrlAttribute()
    {
        $investmentSlug = $this->investment->slug ?? '';
        return "/inwestycje/i/{$investmentSlug}/pietro/{$this->floor_id}/m/{$this->id}";
    }
}
