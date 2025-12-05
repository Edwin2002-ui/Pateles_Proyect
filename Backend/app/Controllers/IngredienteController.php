<?php
namespace App\Controllers;

use App\Core\Response;
use App\Models\Ingrediente;


class IngredienteController{

    private $ingredienteModel;

    public function __construct()
    {
        $this->ingredienteModel = new Ingrediente();
    }


    public function index(){

        $ingredientes = $this->ingredienteModel->getAll();

        return Response::success('Lista de ingredients', $ingredientes);
    }

    public function show ($id){
    
        $ingrediente = $this->ingredienteModel->findId($id);

        if ($ingrediente) {
            Response::success('Ingrediente encontrado', $ingrediente);
        } else {
            Response::notFound('Ingrediente no encontrado');
        }
    
    }

    public function create() {

        $user = \App\Middleware\AuthMiddleware::$currentUser;

        if (!$user) {
            Response::serverError('Error: No se identificó al usuario (Middleware no ejecutado)');
            return;
        }
        

        $data = json_decode(file_get_contents('php://input'), true);



        if (empty($data['nombre']) || empty($data['fecha_ingreso'])) {
            Response::error('Nombre y Fecha de ingreso son obligatorios', 400);
            return; 
        }


        $insertData = [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'fecha_ingreso' => $data['fecha_ingreso'],
            'fecha_vencimiento' => $data['fecha_vencimiento'] ?? null,
            'created_by' =>$user['id'] // <--- ¡AQUÍ ESTÁ LA MAGIA!
        ];

        
        $id = $this->ingredienteModel->create($insertData);

        if ($id) {
            $data['id'] = $id; 
            Response::success('Ingrediente creado correctamente', $data, 201);
        } else {
            Response::serverError('No se pudo guardar el ingrediente');
        }
    }

    public function update($id){

        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nombre'])) {
            Response::error('El nombre es obligatorio', 400);
            return;
        }

        $exists = $this->ingredienteModel->findId($id);
        if (!$exists) {
            Response::notFound('No se puede actualizar: Ingrediente no existe');
            return; 
        }

        $success = $this->ingredienteModel->update($data, $id);

        if ($success) {
            Response::success('Ingrediente actualizado correctamente');
        } else {
            Response::serverError('Error al actualizar el ingrediente');
        }

    }


    public function delete($id) {

        $exists = $this->ingredienteModel->findId($id);
        if (!$exists) {
            Response::notFound('No se puede eliminar: Ingrediente no existe');
            return;
        }
        
        $success = $this->ingredienteModel->delete($id);
        if ($success) {
            Response::success('Ingrediente eliminado correctamente');
        } else {
            Response::serverError('Error al eliminar el ingrediente');
        }


    }

    

    
}