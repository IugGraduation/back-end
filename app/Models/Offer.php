<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Offer extends Model
{
    use HasFactory;
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $appends = [ 'image', 'category_name', 'user_name','post_name','status_color', 'status_text',];
    protected $hidden = ['user', 'category', 'post', 'user', 'imageOffer',  'updated_at', 'created_at'];
    const PENDING = 0;
    const ACCEPT = 1;
    const REJECT = 2;
    const COMPLETE = 3;
//    const INACTIVE = 0;
    protected $guarded = [];
    const PATH_IMAGE = "/upload/offer";


    //Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid');
    }


    public function post()
    {
        return $this->belongsTo(Post::class, 'post_uuid');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid');
    }

    public function imageOffer()
    {
        return $this->morphOne(Upload::class, 'imageable');
    }



    //Attributes
    public function getStatusColorAttribute()
    {
       if ($this->status == self::PENDING) {
            return '#F78831';
        } elseif ($this->status == self::COMPLETE) {
            return '#028C59';
        } elseif ($this->status == self::ACCEPT) {
            return '#2C73EB';
        }
       return  '#2C73EB';
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == self::PENDING) {
            return __('pending');
        } elseif ($this->status == self::COMPLETE) {
            return  __('complete');
        } elseif ($this->status == self::ACCEPT) {
            return  __('accept');
        }
    }



    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }

    public function getPostNameAttribute()
    {
        return @$this->post->name;
    }
    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }

    public function getImageAttribute()
    {
        return !is_null(@$this->imageOffer->path) ? asset(Storage::url(@$this->imageOffer->path) ): '';
    }

    //boot
    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->uuid = Str::uuid();
        });
//        static::addGlobalScope('status', function (Builder $builder) {
//            $builder->where('status', 1);//1==active
//        });
    }
}

