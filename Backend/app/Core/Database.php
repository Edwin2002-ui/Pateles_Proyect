<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{

    private static $connection = null;

    public static function connect()
    {

        if (self::$connection !== null) {
            return self::$connection;
        }

         try {
            self::$connection = new PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'] . ";charset=utf8mb4",
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            return self::$connection;

        } catch (PDOException $e) {
            // En producción, loguea el error en lugar de mostrarlo
            if ($_ENV['APP_ENV'] === 'development') {
                die("Error de conexión: " . $e->getMessage());
            } else {
                die("Error de conexión a la base de datos");
            }
        }
    }
}
