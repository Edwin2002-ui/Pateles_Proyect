<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Pastel;


class PastelController{
    private $pastelModel;

    public function __construct()
    {
        $this->pastelModel = new Pastel();
    }


    public function index(){

        $pasteles = $this->pastelModel->getAll();

        foreach ($pasteles as &$pastel) {
            $pastel['ingredientes'] = $this->pastelModel->getIngredientes($pastel['ID_pastel']);
        }

        Response::success('Listado de pasteles con ingredientes', $pasteles);
    }

    public function store(){
        $user = \App\Middleware\AuthMiddleware::$currentUser;
        $data = json_decode(file_get_contents('php://input'), true);

    
        $insertData = [
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'preparado_por' => $data['preparado_por'],
            'fecha_creacion' => $data['fecha_creacion'],
            'fecha_vencimiento' => $data['fecha_vencimiento'],
            'created_by' => $user['id']
        ];

        $pastelId = $this->pastelModel->create($insertData);

        if ($pastelId) {
            
            if (!empty($data['ingredientes']) && is_array($data['ingredientes'])) {
                foreach ($data['ingredientes'] as $ingredienteId) {
            
                    $this->pastelModel->addIngrediente($pastelId, $ingredienteId);
                }
            }

            Response::success('Pastel creado con ingredientes', ['id' => $pastelId], 201);
        } else {
            Response::serverError('No se pudo crear el pastel');
        }
        
    }

    public function show($id) {
  
        $pastel = $this->pastelModel->findId($id);

        if (!$pastel) {
            Response::notFound('Pastel no encontrado');
            return;
        }

        $ingredientes = $this->pastelModel->getIngredientes($id);

       
        $pastel['lista_ingredientes'] = $ingredientes;

        Response::success('Pastel encontrado', $pastel);
    }

    public function update($id){

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['nombre'])) {
            Response::error('El nombre es obligatorio', 400);
            return;
        }

        $exists = $this->pastelModel->findId($id);

        if (!$exists) {
            Response::notFound('No se puede actualziar: Pastel no existe');
            return;
        }

        $updateData = [
        'nombre' => $data['nombre'],
        'descripcion' => $data['descripcion'] ?? $exists['Descripcion'], 
        'preparado_por' => $data['preparado_por'] ?? $exists['Preparado_por'],
        'fecha_creacion' => $data['fecha_creacion'] ?? $exists['Fecha_creacion'],
        'fecha_vencimiento' => $data['fecha_vencimiento'] ?? $exists['Fecha_Vencimiento']
        ];


        $success = $this->pastelModel->update($updateData,$id);


       if ($success) {
    
            if (isset($data['ingredientes']) && is_array($data['ingredientes'])) {
        
                $this->pastelModel->removeAllIngredientes($id);

                foreach ($data['ingredientes'] as $ingredienteId) {
                    $this->pastelModel->addIngrediente($id, $ingredienteId);
                }
            }

            // Corregido el mensaje (decia Ingrediente antes)
            Response::success('Pastel y sus ingredientes actualizados correctamente');
        } else {
            Response::serverError('Error al actualizar el pastel');
        }

    }

    public function delete($id){

        $exists = $this->pastelModel->findId($id);

        if (!$exists) {
            Response::notFound('No se puede eliminar: Pastel no existe');
            return;
        }

        $success =  $this->pastelModel->delete($id);
        if ($success) {
            Response::success('Pastel eliminado correctamente');
        } else {
            Response::serverError('Error al eliminar el pastel');
        }
        



    }


    public function reporte() {
        $data = $this->pastelModel->getReporteIngredientes();
        Response::success('Reporte generado', $data);
    }
        
    

}