<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

use App\Models\Tablet;
use App\Models\Courier;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'tabletLogin', 'courierLogin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'ok' => false,
                'error' => 'Email yoki parol xato!'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function tabletLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['phone', 'password']);

        if (! $token = auth('tablet')->attempt($credentials)) {
            return response()->json([
                'ok' => false,
                'error' => 'Telefon raqami yoki parol xato!',
                'request' => $request->all()
            ], 401);
        }

        return response()->json([
            'ok' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('tablet')->factory()->getTTL() * 60,
            'user' => Tablet::where('id', auth('tablet')->user()->id)->with('branch')->first(),
        ], 200);
    }

    public function courierLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['phone', 'password']);

        if (! $token = auth('courier')->attempt($credentials)) {
            return response()->json([
                'ok' => false,
                'error' => 'Telefon raqami yoki parol xato!',
                'request' => $request->all()
            ], 401);
        }

        return response()->json([
            'ok' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('courier')->factory()->getTTL() * 60,
            'user' => Courier::where('id', auth('courier')->user()->id)->with('branch')->first(),
        ], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            'ok' => true,
            'data' => auth()->user(),
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}