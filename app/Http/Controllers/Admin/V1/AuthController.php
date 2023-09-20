<?php

namespace App\Http\Controllers\Admin\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'phone' => ['required', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors()], 400);
        }

        $user = Admin::create([
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password'  => Hash::make($request->input('password')),
        ]);

        return response([
            'user'  => $user,
            'token' => $user->createToken('admin_secret')->plainTextToken,
        ], 200);
    }

    // Login email
    public function login(Request $request)
    {
        $attrs = $request->validate([
            'email'     => ['required', 'string', 'email'],
            'password'  => ['required', 'string', 'min:6'],
        ]);

        if (!Auth::guard('admin')->attempt($attrs)) {
            return response([
                'error' => 'Tài khoản hoặc mật khẩu không chính xác'
            ], 400);
        }

        $user = Admin::select('id', 'name', 'email', 'phone')
            ->find(Auth::guard('admin')->id());

        $user->tokens()->where('name', 'admin_secret')->delete();

        return response([
            'user'  => $user,
            'token' => $user->createToken('admin_secret')->plainTextToken,
        ], 200);
    }

    public function logout()
    {
        $user = Admin::find(Auth::id());
        $user->tokens()->where('name', 'admin_secret')->delete();
        return response([
            'message' => "OK"
        ], 200);
    }
}
