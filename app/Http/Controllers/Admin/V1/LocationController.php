<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LocationRequest;
use App\Models\Location;
use App\Models\Crumb;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parent_id  = $request->input('parent_id') ?? 0;
        $locations = Location::where("parent_id", $parent_id)->get();

        $data = [
            'locations' => $locations
        ];
        return response(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request)
    {
        $params                 = $request->validated();
        $params['sorted']       = $request->input('sorted');
        $params['parent_id']    = $request->input('parent_id');

        $location               = Location::create($params);

        return response(['data' => ['location' => $location]]);
    }

    /**
     * Display the specified resource.
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

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, string $id)
    {
        $params                 = $request->validated();
        $params['sorted']       = $request->input('sorted');
        $params['parent_id']    = $request->input('parent_id');

        $location               = Location::find($id);

        if ($location) {
            $location->update($params);
            return response(['data' => ['location' => $location]]);
        } else {
            return response(['error' => 'Location not found'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location   = Location::find($id);

        if ($location) {
            $location->delete();
            return response(['message' => 'OK']);
        } else {
            return response(['error' => 'Location not found'], 400);
        }
    }
}
