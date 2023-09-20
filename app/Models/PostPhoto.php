<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPhoto extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'post_id',
        'photo_url',
        'photo_id',
    ];

    public static function createByParams($post_id, $photo_ids)
    {
        foreach ($photo_ids as $id) {
            $photo = Photo::find($id);
            PostPhoto::create([
                'post_id'   => $post_id,
                'photo_id'  => $id,
                'photo_url' => $photo->photo_url
            ]);
        }
    }

    protected static function booted()
    {
        static::deleting(function ($postPhoto) {
            $photo = Photo::find($postPhoto->photo_id);
            if ($photo) {
                $photo->delete();
            }
        });
    }
}
