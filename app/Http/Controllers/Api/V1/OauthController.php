<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
    public function social(Request $request, $provider)
    {
        $redirect_uri = $request->input('redirect_uri');
        if ($redirect_uri) {
            return Socialite::with($provider)
                ->with(['redirect_uri' => $redirect_uri])
                ->stateless()
                ->redirect();
        } else {
            return response(['message' => 'Error redirect_uri'], 400);
        }
    }

    public function callback(Request $request, $provider)
    {
        dd($request->all());
    }

    public function token(Request $request, $provider)
    {
        try {
            $userSocial = Socialite::with($provider)
                ->stateless()
                ->user();

            $user = User::where('email', $userSocial->email)->whereNull('uid')->first();

            if ($user) {
                return response(['message' => __('site.email_taken')], 400);
            } else {

                $user = User::updateOrCreate([
                    'uid' => $userSocial->id,
                ], [
                    'name'      => $userSocial->name,
                    'email'     => $userSocial->email,
                    'avatar'    => $userSocial->avatar,
                    'provider'  => $provider
                ]);

                $user->tokens()->where('name', 'secret')->delete();

                return response([
                    'user'  => [
                        "id"        => $user->id,
                        "name"      => $user->name,
                        "email"     => $user->email,
                        "avatar"    => $user->avatar
                    ],
                    'token' => $user->createToken('secret')->plainTextToken,
                ], 200);
            }
        } catch (Exception $e) {
            return response(['message' => __('site.errors')], 400);
        }
    }
}
