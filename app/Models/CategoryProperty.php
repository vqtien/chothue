<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProperty extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'parent_id',
        'category_id',
        'sorted',
    ];

    public function parent()
    {
        $instance = $this->belongsTo(CategoryProperty::class, 'parent_id');
        return $instance;
    }

    public function childrens()
    {
        $instance = $this->hasMany(CategoryProperty::class, 'parent_id');
        return $instance;
    }
}
