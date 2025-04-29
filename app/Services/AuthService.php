<?php

namespace App\Services;

class AuthService
{
    public function auth($request)
    {
        $authorization = $request->header('Authorization');
        $secret = env('API_SECRET');

        if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
            return false;
        }

        $token = substr($authorization, 7);

        if ($token !== $secret) {
            return false;
        }

        return true;
    }
}
