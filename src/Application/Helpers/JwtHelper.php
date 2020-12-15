<?php

namespace App\Application\Helpers;

use \Firebase\JWT\JWT;

class JwtHelper
{
    public static function response($message)
    {
        return print_r($message);
    }

    public static function createJWT($payload)
    {
        $key = "tp";
        return Jwt::encode($payload, $key);
    }

    public static function validatorJWT($jwt)
    {
        $key = "tp";
        //print $decoded;
        return JWT::decode($jwt, $key, array('HS256'));
//        try {
//            $key = "tp";
//            print $jwt;
//            return JWT::decode($jwt, $key, ['HS256']);
//        } catch (\Exception $e) {
//            throw new \Exception('Login error');
//        }
    }
}
