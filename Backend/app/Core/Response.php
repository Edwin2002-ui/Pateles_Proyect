<?php

namespace App\Core;

class Response
{
    // Enviar respuesta JSON exitosa
    public static function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *'); // Para Vue en otro puerto
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        echo json_encode($data);
        exit;
    }

    // Respuesta de éxito
    public static function success($message, $data = null, $status = 200)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        self::json($response, $status);
    }

    public static function created($message, $data = null, $status = 201)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        self::json($response, $status);
    }

    // Respuesta de error
    public static function error($message, $status = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::json($response, $status);
    }

    // Respuestas predefinidas comunes
    public static function notFound($message = 'Recurso no encontrado')
    {
        self::error($message, 404);
    }

    public static function unauthorized($message = 'No autorizado')
    {
        self::error($message, 401);
    }

    public static function forbidden($message = 'Acceso prohibido')
    {
        self::error($message, 403);
    }

    public static function serverError($message = 'Error interno del servidor')
    {
        self::error($message, 500);
    }
}