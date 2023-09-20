<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostProperty extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'post_id',
        'category_property_id',
    ];

    public function property()
    {
        $instance = $this->belongsTo("App\Models\CategoryProperty", "category_property_id");
        return $instance->selectRaw("category_properties.id, category_properties.name, category_properties.parent_id");
    }

    public static function createByParams($post_id, $property_ids)
    {
        foreach ($property_ids as $id) {
            PostProperty::create([
                'post_id'               => $post_id,
                'category_property_id'  => $id,
            ]);
        }
    }
}
