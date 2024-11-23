<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasFactory, HasTranslations;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $translatable = ['name'];
    protected $guarded = [];
    protected $appends = ['name_translate', 'city_name'];
    protected $hidden=['city','name','city_uuid','status','updated_at','created_at'];

//Relations
    public function city()
    {
        return @$this->belongsTo(City::class)->withoutGlobalScope('status');
    }

    //Attributes
    public function getCityNameAttribute()
    {
        return @$this->city->name;
    }

    public function getNameTranslateAttribute()
    {
        return @$this->name;
    }

    //Boot

    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->uuid = Str::uuid();
        });
        static::addGlobalScope('status', function (Builder $builder) {
            $builder->where('status', 1);//1==active
        });

    }
}
