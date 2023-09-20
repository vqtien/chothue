<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'photo_url',
        'photo_size',
        'user_id',
    ];

    public static function deleteByPath($path)
    {
        if (empty($path)) {
            return;
        }

        $arr        = explode('/', $path);
        $fileName   = $arr[count($arr) - 1];
        $photo      = Photo::where('photo_url', $fileName)->first();
        if ($photo) {
            $photo->delete();
        }
    }

    protected static function booted()
    {
        static::deleted(function ($photo) {
            $mediumPath = public_path('uploads/photos/medium/');
            $largePath  = public_path('uploads/photos/large/');
            File::delete($mediumPath . $photo->photo_url);
            File::delete($largePath  . $photo->photo_url);
        });
    }
}
