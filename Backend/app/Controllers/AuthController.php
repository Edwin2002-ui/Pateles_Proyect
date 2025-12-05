<?php
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Models\User;
use App\Core\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {

    private $userModel;
    private $secretKey;
    
    public function __construct() {
        $this->userModel = new User();
        $this->secretKey = $_ENV['JWT_SECRET_KEY'];
    }
    
    // usamos jwt para generar token
       private function generateToken($user)
    {
        $payload = [
            'iss' => 'pasteles-api',           
            'aud' => 'pasteles-app',           
            'iat' => time(),                  
            'exp' => time() + (60 * 60 * 24), 
            'user_id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name']
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }



    public function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['name']) || empty($data['password'])) {
            Response::error('Todos los campos son requeridos', 400);
        }

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $name = htmlspecialchars($data['name']);
        $password = $data['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::error('Email inválido', 400);
        }

        if (strlen($password) < 6) {
            Response::error('La contraseña debe tener al menos 6 caracteres', 400);
        }

        if ($this->userModel->findByEmail($email)) {
            Response::error('El email ya está registrado', 409);
        }

        try {
            $userId = $this->userModel->createLocal($email, $name, $password);
            
            if ($userId) {
                $user = $this->userModel->findById($userId);
                unset($user['password']);
                
                $token = $this->generateToken($user);
                
                Response::success('Usuario registrado exitosamente', [
                    'user' => $user,
                    'token' => $token
                ], 201);
            }
        } catch (\Exception $e) {
            Response::serverError($e->getMessage());
        }
    }

    
        public function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['email']) || empty($data['password'])) {
            Response::error('Email y contraseña son requeridos', 400);
        }

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = $data['password'];

        $user = $this->userModel->verifyPassword($email, $password);

        if ($user) {
            unset($user['password']);
            
            $token = $this->generateToken($user);

            Response::success('Login exitoso', [
                'user' => $user,
                'token' => $token
            ]);
        } else {
            Response::error('Credenciales inválidas', 401);
        }
    }

     public function googleAuth()
    {
        // $data = json_decode(file_get_contents('php://input'), true);

        // if (empty($data['google_id']) || empty($data['email']) || empty($data['name'])) {
        //     Response::error('Datos de Google incompletos', 400);
        // }

        // $googleId = $data['google_id'];
        // $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        // $name = htmlspecialchars($data['name']);
        // $avatar = $data['avatar'] ?? null;

        // // Buscar usuario por Google ID
        // $user = $this->userModel->findByGoogleId($googleId);

        // if (!$user) {
        //     // Si no existe, verificar si el email ya está registrado
        //     $existingUser = $this->userModel->findByEmail($email);
            
        //     if ($existingUser) {
        //         Response::error('Este email ya está registrado con otro método', 409);
        //     }

        //     // Crear nuevo usuario de Google
        //     $userId = $this->userModel->createGoogle($email, $name, $googleId, $avatar);
        //     $user = $this->userModel->findById($userId);
        // }

        // // Crear sesión
        // $_SESSION['user_id'] = $user['id'];
        // $_SESSION['user_email'] = $user['email'];
        // $_SESSION['user_name'] = $user['name'];

        // unset($user['password']);

        // Response::success('Autenticación con Google exitosa', [
        //     'user' => $user,
        //     'session' => session_id()
        // ]);
    }


     public function me()
    {
        $headers = getallheaders();
     

        $authHeader = $headers['Authorization'] ?? '';

        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            Response::unauthorized('Token no proporcionado');
        }

        $token = $matches[1];
        $decoded = $this->verifyToken($token);

        if (!$decoded) {
            Response::unauthorized('Token inválido o expirado');
        }

        $user = $this->userModel->findById($decoded->user_id);

        if (!$user) {
            Response::notFound('Usuario no encontrado');
        }

        unset($user['password']);
        Response::success('Usuario autenticado', $user);
    }

        private function verifyToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

  
    public function logout()
    {
        Response::success('Sesión cerrada exitosamente');
    }
}