<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Core\Response;
use App\Models\User;

class AuthMiddleware
{
    public static $currentUser= null;

    public static function authenticate()
    {
       
        $headers = getallheaders();
        
        
        $authHeader = $headers['Authorization'] ?? '';
        // var_dump($authHeader);

        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            Response::unauthorized('Token no proporcionado');
        }

        $token = $matches[1];
        $decoded = self::verifyToken($token);

        if (!$decoded) {
            Response::unauthorized('Token inválido o expirado');
        }

        $userModel = new User();
        $user = $userModel->findById($decoded->user_id);

        if (!$user) {
            Response::notFound('Usuario no encontrado');
        }

        unset($user['password']);
        self::$currentUser = $user;
        return $user;
    }


    private static function verifyToken($token)
    {
        try {
            // Accedemos directo a ENV porque es estático
            return JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }
   
}