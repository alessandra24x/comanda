<?php

namespace App\Helpers;

use \Firebase\JWT\JWT;

class JwtHelper
{
    public static function response($message)
    {
        return print_r($message);
    }

    public static function createJWT($payload)
    {
        $key = "";
        return Jwt::encode($payload, $key);
    }

    public static function validatorJWT($jwt)
    {
        try {
            $key = "";
            return JWT::decode($jwt, $key, ['HS256']);
        } catch (\Exception $e) {
            throw new \Exception('Login error');
        }
    }
}
