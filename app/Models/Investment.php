<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

use Spatie\Activitylog\LogOptions;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Spatie\Activitylog\Traits\LogsActivity;

class Investment extends Model
{

    use LogsActivity, HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'status',
        'developro',
        'vox_url',
        'name',
        'slug',
        'address',
        'city',
        'date_start',
        'date_end',
        'areas_amount',
        'area_range',
        'office_address',
        'meta_title',
        'meta_description',
        'meta_robots',
        'entry_content',
        'content',
        'end_content',
        'file_thumb',
        'file_logo',
        'file_header',
        'file_brochure',
        'galleries',
        'lat',
        'lng',
        'zoom',
        'marker'
    ];

    public function investmentPage()
    {
        return $this->hasOne(InvestmentPage::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get carousel images from gallery
     * @return HasMany
     */
    public function carousel()
    {
        return $this->hasMany(Image::class, 'gallery_id', 'carousel_id');
    }

    /**
     * Get investment plan
     * @return HasOne
     */
    public function plan(): HasOne
    {
        return $this->hasOne('App\Models\Plan');
    }

    /**
     * Get investment floors
     * @return HasMany
     */
    public function floors(): HasMany
    {
        return $this->hasMany('App\Models\Floor')->orderByDesc('position');
    }

    /**
     * Get investment floor
     * @return HasOne
     */
    public function floor(): HasOne
    {
        return $this->hasOne('App\Models\Floor');
    }

    /**
     * Get flats belonging to the floors of the investment
     * @return HasManyThrough
     */
    public function floorRooms(): HasManyThrough
    {
        return $this->hasManyThrough(
            'App\Models\Property',
            'App\Models\Floor',
            'investment_id',
            'floor_id',
            'id',
            'id'
        )->select('properties.*', 'floors.number as floor_number');
    }

    /**
     * Get investment building
     * @return HasOne
     */
    public function building(): HasOne
    {
        return $this->hasOne('App\Models\Building');
    }

    /**
     * Get investment buildings
     * @return HasMany
     */
    public function buildings(): HasMany
    {
        return $this->hasMany('App\Models\Building');
    }

    /**
     * Get investment floors
     * @return HasMany
     */
    public function buildingFloors(): HasMany
    {
        return $this->hasMany('App\Models\Floor');
    }

    /**
     * Get flats belonging to the floors of the investment
     * @return HasManyThrough
     */
    public function buildingRooms(): HasManyThrough
    {
        return $this->hasManyThrough(
            'App\Models\Property',
            'App\Models\Building',
            'investment_id',
            'building_id',
            'id',
            'id'
        )->join('floors', 'properties.floor_id', '=', 'floors.id')
            ->select('properties.*', 'floors.number as floor_number');
    }

    /**
     * Get investment pages
     * @return HasMany
     */
    public function pages(): HasMany
    {
        return $this->hasMany('App\Models\InvestmentPage')->orderBy('sort')->where('active', '=', 1);
    }

    /**
     * Get investment properties
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany('App\Models\Property');
    }

    /**
     * Get investment houses
     * @return HasMany
     */
    public function houses(): HasMany
    {
        return $this->hasMany('App\Models\Property')->orderBy('number_order');
    }

    /**
     * Get investment properties
     * @return HasMany
     */
    public function searchProperties(): HasMany
    {
        return $this->hasMany('App\Models\Property')->select(['investment_id', 'name', 'status', 'rooms', 'area', 'views', 'active', 'updated_at']);
    }

    /**
     * Get investment properties
     * @return HasMany
     */
    public function selectProperties(): HasMany
    {
        return $this->hasMany('App\Models\Property')->select(['investment_id', 'id', 'name', 'type']);
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($investment) {
            $investment->floors()->each(function($floor) {
                $floor->delete();
            });

            $investment->buildings()->each(function($building) {
                $building->delete();
            });

            $investment->properties()->each(function($property) {
                $property->delete();
            });
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        $logOptions = new LogOptions();
        $logOptions->useLogName('Investycje');
        $logOptions->logFillable();

        return $logOptions;
    }
}
