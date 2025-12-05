<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Ingrediente{
    private $db;
    private $table = 'ingrediente';

    public function __construct() {
        $this->db = Database::connect();
    }


    public function getAll(){
        $sql = "SELECT * 
                FROM {$this->table}
                WHERE deleted = 0
                ORDER BY created_at DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();

    }

    public function create($data){   
        try {
            $sql = "INSERT INTO {$this->table} 
                    (Nombre, Descripcion, Fecha_ingreso, Fecha_Vencimiento, created_by) 
                    VALUES (:nombre, :descripcion, :fecha_ingreso, :fecha_vencimiento, :created_by)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':fecha_ingreso', $data['fecha_ingreso']);
            $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
            $stmt->bindParam(':created_by', $data['created_by']);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }


        } catch (\PDOException $e) {
            // Log del error si quieres
            return false;
        }
    }


    public function findId($id){
        try{
            $sql = "SELECT * 
            FROM {$this->table} 
            WHERE ID_ingrediente = :id 
            AND deleted = 0";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update($data,$id){
        try {
    
            $sql = "UPDATE {$this->table} SET 
                    Nombre = :nombre, 
                    Descripcion = :descripcion, 
                    Fecha_ingreso = :fecha_ingreso, 
                    Fecha_Vencimiento = :fecha_vencimiento
                    WHERE ID_ingrediente = :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':fecha_ingreso', $data['fecha_ingreso']);
            $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
            $stmt->bindParam(':id', $id);

            return $stmt->execute(); // Retorna true si funcionó

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete($id){
        try {
            $sql = "UPDATE {$this->table} SET 
                    deleted = 1, 
                    deleted_at = NOW() 
                    WHERE ID_ingrediente = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();

        } catch (\PDOException $e) {
            return false;
        }
    }

}