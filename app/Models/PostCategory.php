<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostCategory extends Model
{
    use HasFactory;
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $appends = ['category_name'];
    protected $hidden=['created_at','updated_at','category','post_uuid'];

    protected $guarded=[];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_uuid');
    }

    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }

    //boot
    public static function boot()
    {
        parent::boot();
        self::creating(function ($item) {
            $item->uuid = Str::uuid();
        });
    }
}
