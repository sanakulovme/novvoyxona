<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtCourier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Get the token from the request
            $token = JWTAuth::getToken();
            
            if (!$token) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Token not provided',
                    'debug' => [
                        'authorization_header' => $request->header('Authorization'),
                        'bearer_token' => $request->bearerToken(),
                    ]
                ], 401);
            }

            // Try to authenticate the user with the tablet guard
            // First, let's try to get the user ID from the token
            $payload = JWTAuth::setToken($token)->getPayload();
            $userId = $payload->get('sub');

            // Convert to integer if needed
            $userId = (int) $userId;

            // Now get the user from the tablet guard
            $user = Auth::guard('courier')->getProvider()->retrieveById($userId);

            if (!$user) {
                // Let's also try to find the user directly from the Tablet model
                $user = \App\Models\Courier::find($userId);

                if (!$user) {
                    return response()->json([
                        'ok' => false,
                        'message' => 'User not found',
                        'debug' => [
                            'token' => $token,
                            'token_string' => (string)$token,
                            'user_id_from_token' => $userId,
                            'user_id_type' => gettype($userId),
                            'payload' => $payload->toArray(),
                            'tablet_exists' => \App\Models\Courier::where('id', $userId)->exists(),
                            'all_tablets' => \App\Models\Courier::pluck('id')->toArray(),
                        ]
                    ], 401);
                }
            }

            // Set the authenticated user for the tablet guard
            Auth::guard('courier')->setUser($user);

        } catch (TokenExpiredException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Token has expired'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Token is invalid'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Token error: ' . $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
