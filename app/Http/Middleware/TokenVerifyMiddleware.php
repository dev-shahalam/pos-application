<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->cookie('token_cookie');
            $result = JWTToken::VerifyToken($token);
            if ($result == "unauthorized") {
                return redirect()->route('login');
            } else {
                $request->headers->set('email', $result->userEmail);
                $request->headers->set('id', $result->userId);
                return $next($request);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'unauthorized'
            ], 401);
        }
    }

}
