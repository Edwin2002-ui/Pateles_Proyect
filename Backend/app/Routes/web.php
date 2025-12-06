<?php

use App\Core\Response;
use App\Core\Database;
use App\Middleware\AuthMiddleware;
use App\Controllers\AuthController;
use App\Controllers\IngredienteController;
use App\Controllers\PastelController;
use App\Controllers\ReposteroController;


$authController = new AuthController();
$ingredienteController = new IngredienteController();
$pastelController = new PastelController();
$reposteroController = new ReposteroController();



$router->before('GET|POST|PUT|DELETE', '/.*', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    // Manejar preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
});

// =======================
// RUTAS DE PRUEBA
// =======================

$router->get('/', function() {
    Response::json([
        'message' => 'API Pasteles funcionando correctamente',
        'version' => '1.0.0'
    ]);
});

$router->get('/test-db', function() {
    try {
        $db = Database::connect();
        $stmt = $db->query("SELECT DATABASE() as db_name");
        $result = $stmt->fetch();
        
        Response::success('Conexión exitosa', [
            'database' => $result['db_name']
        ]);
    } catch (Exception $e) {
        Response::serverError($e->getMessage());
    }
});






$router->post('/auth/register', function() use ($authController) {
    $authController->register();
});


$router->post('/auth/login', function() use ($authController) {
    $authController->login();
});

$router->post('/auth/google', function() {
    Response::json(['message' => 'Ruta de Google Auth']);
});

$router->post('/auth/logout', function()  use ($authController){
    $authController->logout();
});

$router->get('/auth/me', function() use ($authController) {
    $authController->me();
});



$router->mount('/pasteles', function() use ($router, $pastelController) {

    $router->get('/', function() use ($pastelController)  {
        AuthMiddleware::authenticate(); 
        $pastelController->index();
    });

    $router->post('', function() use ($pastelController) {
        AuthMiddleware::authenticate(); 
        $pastelController->store();
        
    });

    $router->get('/(\d+)', function($id) use ($pastelController) {
        AuthMiddleware::authenticate(); 
        $pastelController->show($id);
        
    });

    $router->put('/(\d+)', function($id) use ($pastelController) {
        AuthMiddleware::authenticate(); 
        $pastelController->update($id);
        
    });

    $router->delete('/(\d+)', function($id) use ($pastelController) {
        AuthMiddleware::authenticate(); 
        $pastelController->delete($id);
        
    });

    $router->get('/reporte', function() use ($pastelController) {
        AuthMiddleware::authenticate(); 
        $pastelController->reporte();
        
    });



});


$router->mount('/ingredientes', function() use ($router,$ingredienteController){

   
    $router->get('/', function() use ($ingredienteController) {
        AuthMiddleware::authenticate(); 
        $ingredienteController->index();
    });

    $router->post('/', function() use ($ingredienteController){
        AuthMiddleware::authenticate(); 
        $ingredienteController->create();
    });

    $router->get('/(\d+)', function($id) use ($ingredienteController) {
        AuthMiddleware::authenticate(); 
        $ingredienteController->show($id);
    });

    $router->put('/(\d+)', function($id) use ($ingredienteController) {
        AuthMiddleware::authenticate(); 
       $ingredienteController->update($id);

    });

    $router->delete('/(\d+)', function($id) use ($ingredienteController) {
        AuthMiddleware::authenticate(); 
        $ingredienteController->delete($id);
       
    });

});


$router->mount('/reposteros', function() use ($router,$reposteroController){

    $router->get('/', function() use ($reposteroController) {
        AuthMiddleware::authenticate(); 
        $reposteroController->index();
    });

    $router->post('/', function() use ($reposteroController){
        AuthMiddleware::authenticate(); 
        $reposteroController->create();
    });

    $router->get('/(\d+)', function($id) use ($reposteroController) {
        AuthMiddleware::authenticate(); 
        $reposteroController->show($id);
    });

    $router->delete('/(\d+)', function($id) use ($reposteroController) {
        AuthMiddleware::authenticate(); 
        $reposteroController->delete($id);
       
    });

});



$router->set404(function() {
    Response::notFound('Ruta no encontrada');
});