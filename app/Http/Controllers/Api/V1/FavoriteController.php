<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;


class FavoriteController extends Controller
{

    public function index(Request $request)
    {
        $data = [];
        return response(['data' => $data]);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $object_id      = $request->input('object_id');
        $object_name    = $request->input('object_name');

        if ($object_id && $object_name) {
            $favoriteable_type = "App\Models\\$object_name";
            $favoriteable_id = $object_id;
            $user_id = Auth::id();

            $param = [
                'user_id'           => $user_id,
                'favoriteable_id'   => $favoriteable_id,
                'favoriteable_type' => $favoriteable_type,
            ];

            $favorite   = Favorite::where($param)->first();

            if ($favorite) {
                $favorite->delete();
            } else {
                Favorite::create($param);
            }

            return response(['message' => __("site.success")]);
        } else {
            return response(['error' => __('site.errors')], 400);
        }
    }

    public function destroy(string $id)
    {
        $favorite = Favorite::find($id);
        if ($favorite) {
            $favorite->delete();
            return response(['message' => __('site.success')]);
        }
        return response(['error' => __('site.not_found')], 400);
    }
}
