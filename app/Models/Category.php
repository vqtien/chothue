<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'photo_url',
        'description',
        'sorted'
    ];

    public function parent()
    {
        $instance = $this->belongsTo('App\Models\Category', 'parent_id');
        return $instance->select(
            'id',
            'name',
            'slug',
            'photo_url',
            'parent_id',
            'sorted'
        );
    }

    public function childrens()
    {
        $instance = $this->hasMany('App\Models\Category', 'parent_id');
        return $instance->select(
            'id',
            'name',
            'slug',
            'photo_url',
            'parent_id',
            'sorted'
        );
    }
}
