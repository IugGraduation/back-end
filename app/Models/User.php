<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $appends = ['image', 'city_name','country_name'];

    protected $fillable = [
        'name',
        'email',
        'place',
        'email',
        'password',
        'mobile',
        'status',
        'bio'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    const PATH_IMAGE = "/upload/user/personal/";

    public function city()
    {
        return $this->belongsTo(City::class, 'city_uuid');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_uuid');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class, 'user_uuid');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_uuid');
    }
    public function imageUser()
    {
        return $this->morphOne(Upload::class, 'imageable')->where('type', '=', Upload::IMAGE)->where('name', '=', 'personal_photo');
    }

    public function fcm_tokens()
    {
        return $this->hasMany(FcmToken::class, 'user_uuid');
    }

    public function getCityNameAttribute()
    {
        return @$this->city->name;
    }
    public function getCountryNameAttribute()
    {
        return @$this->country->name;
    }
    public function getImageAttribute()
    {
        if (@$this->imageUser->filename) {
            return !is_null(@$this->imageUser->path) ? asset(Storage::url(@$this->imageUser->path) ): '';
        } else {
            return url('/') . '/dashboard/app-assets/images/4367.jpg';
        }
    }


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
