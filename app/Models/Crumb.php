<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Crumb
{
    static function crumbLocation($location_id)
    {
        if ($location_id > 0) {
            $crumbs = DB::select(
                "SELECT id, name , parent_id, level FROM
	                                (SELECT id,  name, parent_id, level,
	                                       CASE WHEN id = :id THEN @id := parent_id
	                                            WHEN id = @id THEN @id := parent_id
	                                            END as checkId
	                                FROM locations
	                                ORDER BY level desc) as T
	                                WHERE checkId IS NOT NULL",
                ['id' => $location_id]
            );
            return $crumbs;
        } else {
            return [];
        }
    }

    static function crumbCategory($category_id)
    {
        if ($category_id > 0) {
            $crumbs = DB::select(
                "SELECT id, name , parent_id, level, slug FROM
	                                (SELECT id, name, parent_id, level, slug,
	                                       CASE WHEN id = :id THEN @id := parent_id
	                                            WHEN id = @id THEN @id := parent_id
	                                            END as checkId
	                                FROM categories
	                                ORDER BY level desc) as T
	                                WHERE checkId IS NOT NULL",
                ['id' => $category_id]
            );
            return $crumbs;
        } else {
            return [];
        }
    }
}
