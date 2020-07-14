<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    public function login(Request $request) {

        $credentials = $request->only(['email', 'password']);

        try {
            if ( !$token = JWTAuth::attempt($credentials) ) {
                return response()->json([
                    'error' => 'Unauthorized'
                ], 401);
            }

        } catch (JWTException $e) {

            return response()->json([
                'error' => 'Could not create token'
            ], 500);
        }

        return $this->respondWithToken($token);
    }

    public function getAuthUser(Request $request) {

        try {

            if ( !$user = JWTAuth::parseToken()->authenticate() ) {
                return response()->json([
                    'error' => 'User not found'
                ], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['Token Expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['Token Invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['Token Absent'], $e->getStatusCode());
        }

        return response()->json(auth()->user());
    }

    public function logout() {
        auth()->logout();
        return response()->json([
            'message' => 'Succesfully logged out'
        ]);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
