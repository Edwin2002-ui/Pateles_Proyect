
<?php

require __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
session_start();
// Router
$router = new Router();

$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = str_replace('/index.php', '', $scriptName);

// Configurar basePath dinámicamente
if ($basePath !== '') {
    $router->setBasePath($basePath);
}

// Cargar archivo de rutas
require __DIR__ . '/../app/Routes/web.php';

$router->run();
