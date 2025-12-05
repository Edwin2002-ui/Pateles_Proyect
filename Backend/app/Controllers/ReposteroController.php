<?php
namespace App\Controllers;

use App\Core\Response;
use App\Models\Repostero;

class ReposteroController {

    private $reposteroModel;
    public function __construct() {
        
        $this->reposteroModel = new Repostero();
    }

    public function index(){
        $reposteros = $this->reposteroModel->getAll();
        Response::success('Lista de reposteros obtenida', $reposteros);

    }

    public function create(){
        $data = json_decode(file_get_contents('php://input'), true);


        if (empty($data['nombres']) || empty($data['apellidos'])) {
            Response::error('Nombres y Apellidos son obligatorios', 400);
            return;
        }

    
        $insertData = [
            'nombres' => $data['nombres'],
            'apellidos' => $data['apellidos']
        ];

  
        $id = $this->reposteroModel->create($insertData);

        if ($id) {
            Response::success('Repostero contratado exitosamente', ['id' => $id], 201);
        } else {
            Response::serverError('No se pudo guardar el repostero');
        }

    }

    public function show($id){
        $repostero = $this->reposteroModel->findId($id);

        if ($repostero) {
            Response::success('Repostero encontrado', $repostero);
        } else {
            Response::notFound('El repostero no existe o fue eliminado');
        }

    }

    public function delete($id){

        $exists = $this->reposteroModel->findId($id);

        if (!$exists) {
            Response::notFound('No se puede despedir: El repostero no existe');
            return;
        }

    
        $deleted = $this->reposteroModel->delete($id);

        if ($deleted) {
            Response::success('Repostero eliminado correctamente');
        } else {
            Response::serverError('Hubo un error al intentar eliminar el registro');
        }

    }

}