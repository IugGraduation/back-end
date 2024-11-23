<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{

    use HasFactory, HasTranslations;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $translatable = ['name'];
    protected $appends = ['name_translate','image'];
    protected $guarded = [];
//    protected $hidden=['imageCategory','name','created_at','updated_at','status','products'];
    const PATH_IMAGE='/upload/category/images/';

    //Relations
    public function imageCategory()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }
    //Attributes
    public function getNameTranslateAttribute()
    {
        return @$this->name;
    }

    public function getImageAttribute()
    {
        return !is_null(@$this->imageCategory->path) ? asset(Storage::url(@$this->imageCategory->path) ): '';
    }
    //boot
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
