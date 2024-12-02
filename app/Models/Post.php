<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $appends = [ 'attachments', 'category_name', 'user_name','status_name'];
//    protected $hidden = ['imageProduct', 'category', 'cart', 'user', 'subCategory', 'status', 'updated_at', 'created_at'];

    protected $guarded = [];
    const PATH_IMAGE = "/upload/post";
//    const OPEN='open';
//    const CLOSE='close';
    const ACTIVE=1;
    const CLOSE=3;

    const PENDING=0;
    const REJECT=2;


    //Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid');
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class, 'post_uuid');
    }

//    public function favorite()
//    {
//        return $this->hasMany(Favorite::class, 'content_uuid');
//    }


    public function categories()
    {
        return $this->hasMany(PostCategory::class, 'post_uuid');
    }

    public function imagePost()
    {
        return $this->morphMany(Upload::class, 'imageable');
    }



    //Attributes


//    public function getIsFavoriteAttribute()
//    {
//        if (@Auth::guard('sanctum')->user()->uuid) {
//            return Favorite::query()->where('content_uuid', $this->uuid)->where('user_uuid', Auth::guard('sanctum')->user()->uuid)->exists();
//        } else {
//            return false;
//        }
//    }



    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }
    public function getStatusNameAttribute()
    {
        if ($this->status==self::PENDING){
            return __('Pending');
        }elseif ($this->status==self::ACTIVE){
            return __('Active');
        }elseif($this->status==self::REJECT){
            return __('Rejected');
        }else{
            return __('Close');

        }
    }


    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }

    public function getAttachmentsAttribute()
    {
        $attachments = [];
        foreach ($this->imagePost as $item) {
            $attachments[] = [
                'uuid' => $item->uuid,
                'attachment' => !is_null(@$item->path) ? asset(Storage::url(@$item->path)) : null,
            ];
        }
        return $attachments;
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
