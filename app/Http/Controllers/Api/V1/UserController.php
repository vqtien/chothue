<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Photo;

class UserController extends Controller
{

    public function update(Request $request)
    {
        $user_id    = Auth::id();
        $user       = User::find($user_id);

        if ($user->email == null) {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|min:3',
                'phone' => 'required',
                'email' => 'required|email|unique:users',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|min:3',
                'phone' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user->name     = $request->input('name');
        $user->phone    = $request->input('phone');

        if ($request->input('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        $current_user = array(
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'avatar'    => $user->avatar
        );

        return response(['data' => $current_user]);
    }

    /**
     * Avatar
     */
    public function avatar(Request $request)
    {
        $user_id    = Auth::id();
        $user       = User::find($user_id);

        if ($request->input('avatar')) {
            $imagePath      = $user->avatar;
            $user->avatar   = $request->input('avatar');
            Photo::deleteByPath($imagePath);
        }

        $user->save();

        $current_user = array(
            'id'        => $user->id,
            'name'      => $user->name,
            'email'     => $user->email,
            'avatar'    => $user->avatar
        );

        return response(['data' => $current_user]);
    }
}
