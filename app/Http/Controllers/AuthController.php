<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = Socialite::driver('facebook')->userFromToken($request->accessToken);
        $accessToken = Str::random(90);
        User::updateOrCreate(['id' => $user->id], ['name' => $request->name, 'image' => $request->image, 'access_token' => $accessToken]);
        return response()->json($accessToken);
    }
}
