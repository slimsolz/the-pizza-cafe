<?php

namespace App\Http\Middleware;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('token')) {
            $token = $request->get('token');
        } else if ($request->header('Authorization')){
            $token = substr($request->header('Authorization'), 7);
        } else {
            $token = '';
        }

        if (!$token) {
            return response()->json([
                'message' => 'Token not provided'
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $decoded = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch (ExpiredException $e) {
            return response()->json([
                'message' => 'Token expired, please login again'
            ], Response::HTTP_UNAUTHORIZED);
        } catch (SignatureInvalidException $e) {
            return response()->json([
                'message' => 'Invalid token provided'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = new AuthResource(User::find($decoded->sub));
        $request->auth = $user;
        return $next($request);
    }
}
