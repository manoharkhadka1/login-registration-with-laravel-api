<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\User;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;
    public $errorMessage = 'Something went wrong! Please try again.';
    public function register(Request $request) {
        $checkIfEmailExist = User::where('email', $request->email)->first();

        if ($checkIfEmailExist) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exist in our database.'
            ], 200);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function login(Request $request) {
        Log::debug("here we go!");
        $input = $request->only('email', 'password');
        if (!$jwt_token = $this->guard()->attempt($input)) {
            return response()->json([
                'success' => false,
                'status' => 'invalid_credentials',
                'message' => $this->errorMessage,
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'success' => true,
            'status' => 'ok',
            'token' => $jwt_token,
            'user' => $user
        ]);
    }

    public function logout(Request $request) {
        $this->validate($request, [
           'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'status' => 'ok',
                'success' => true,
                'message' => 'You are successfully logged out.'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'status' => 'unknown_error',
                'message' => $this->errorMessage,
            ]);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

}
