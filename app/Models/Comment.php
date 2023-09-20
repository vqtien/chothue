<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'target_user_id',
        'parent_id',
        'commentable_id',
        'commentable_type',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id');
    }

    public function user()
    {
        $instance = $this->belongsTo('App\Models\User');
        return $instance->select('users.id', 'users.name', 'email', 'avatar');
    }
}
