<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category_id',
        'slug',
        'photo_url',
        'price',
        'content',
        'province_id',
        'district_id',
        'ward_id',
        'address',
    ];

    public function post_photos()
    {
        $instance = $this->hasMany('App\Models\PostPhoto');
        return $instance->select('post_id', 'photo_url');
    }

    public function post_properties()
    {
        $instance = $this->hasMany('App\Models\PostProperty');
        return $instance;
    }

    protected static function booted()
    {
        static::deleting(function ($post) {
            $photo = Photo::where("photo_url", $post->photo_url)->first();
            if ($photo) {
                $photo->delete();
            }
            $postPhotos = PostPhoto::where('post_id', $post->id)->get();

            foreach ($postPhotos as $pt) {
                $pt->delete();
            }
        });
    }
}
