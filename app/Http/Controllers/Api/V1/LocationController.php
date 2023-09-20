<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Crumb;

class LocationController extends Controller
{

    public function index(Request $request)
    {
        $parent_id    = $request->input('parent_id') ?? 0;

        $locations = Location::where("parent_id", $parent_id)->get();

        $data = [
            'locations' => $locations
        ];
        return response(['data' => $data]);
    }

    /**
     * Avatar
     */
    public function show(string $id)
    {
        $location     = Location::find($id);
        $crumbs       = Crumb::crumbLocation($id);

        if ($location) {
            return response(['data' => [
                'location'  => $location,
                'crumbs'    => $crumbs
            ]]);
        } else {
            return response(['error' => 'Location not found'], 400);
        }
    }
}
