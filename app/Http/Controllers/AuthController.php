<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use JWTAuth;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
      $this->validate($request, [
          'email' => 'required|email|max:255',
          'password' => 'required',
      ]);
      try {
        if (! $token = JWTAuth::attempt($request->only('email', 'password'))) {
          return response()->json(['user_not_found'], 404);
        }
      }catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json(['token_expired'], 500);
      }

      return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(\Auth::user());
    }

    public function logout()
    {
        \Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(\Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => \Auth::user()
        ]);
    }
}
