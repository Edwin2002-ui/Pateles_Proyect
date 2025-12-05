<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Pastel{
    private $db;
    private $table = 'pastel';
    private $pivotTable = 'Pastel_Ingredientes';

    public function __construct() {
        $this->db = Database::connect();
    }


    public function getAll(){
        $sql = "SELECT 
                    p.ID_pastel,
                    p.Nombre,
                    p.Descripcion,
                    p.Fecha_creacion,
                    p.Fecha_Vencimiento,
                    p.created_at,
                    p.updated_at,
                    CONCAT(r.nombres, ' ', r.apellidos) AS Preparado_por,
                    IFNULL(u.name, 'Usuario Eliminado') AS created_by
                FROM {$this->table} p
                INNER JOIN reposteros r ON p.Preparado_por = r.id
                LEFT JOIN users u ON p.created_by = u.id
                WHERE p.deleted = 0
                ORDER BY p.created_at DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findId($id){
        try {
            $sql = "SELECT 
                        p.ID_pastel,
                        p.Nombre,
                        p.Descripcion,
                        p.Fecha_creacion,
                        p.Fecha_Vencimiento,
                        p.created_at,
                        p.updated_at,
                        IFNULL(CONCAT(r.nombres, ' ', r.apellidos), 'Sin Asignar') AS Preparado_por,
                        IFNULL(u.name, 'Usuario Eliminado') AS created_by
                        
                    FROM {$this->table} p
                    LEFT JOIN reposteros r ON p.Preparado_por = r.id
                    LEFT JOIN users u ON p.created_by = u.id
                    WHERE p.ID_pastel = :id 
                    AND p.deleted = 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function created($data){
        try {
            $sql = "INSERT INTO {$this->table}
            (Nombre, Descripcion, Preparado_por,Fecha_creacion, Fecha_Vencimiento, created_by) 
            VALUES (:nombre, :descripcion, :fecha_ingreso, :fecha_vencimiento, :created_by)";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }



    public function create($data){
        try {
            
            $sql = "INSERT INTO {$this->table} 
                    (Nombre, Descripcion, Preparado_por, Fecha_creacion, Fecha_Vencimiento, created_by) 
                    VALUES (:nombre, :descripcion, :preparado_por, :fecha_creacion, :fecha_vencimiento, :created_by)";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':preparado_por', $data['preparado_por']); 
            $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);
            $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
            $stmt->bindParam(':created_by', $data['created_by']);


            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;

        } catch (\PDOException $e) {
            // var_dump($e->getMessage()); 
            return false;
        }
    }


    public function update($data, $id){
        try {
            $sql = "UPDATE {$this->table} SET 
                    Nombre = :nombre,
                    Descripcion = :descripcion,
                    Preparado_por = :preparado_por,
                    Fecha_creacion = :fecha_creacion,
                    Fecha_Vencimiento = :fecha_vencimiento
                    WHERE ID_pastel = :id";

            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':nombre', $data['nombre']);
            $stmt->bindParam(':descripcion', $data['descripcion']);
            $stmt->bindParam(':preparado_por', $data['preparado_por']);
            $stmt->bindParam(':fecha_creacion', $data['fecha_creacion']);
            $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete($id){
        try {
            $sql = "UPDATE {$this->table} SET 
                    deleted = 1,
                    deleted_at = NOW()
                    WHERE ID_pastel = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();

        } catch (\PDOException $e) {
            return false;
        }
    }

    public function addIngrediente($pastelId, $ingredienteId) {
        try {
            $sql = "INSERT INTO {$this->pivotTable} (ID_pastel, ID_ingrediente) 
                    VALUES (:id_pastel, :id_ingrediente)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pastel', $pastelId);
            $stmt->bindParam(':id_ingrediente', $ingredienteId);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function removeIngrediente($pastelId, $ingredienteId) {
        try {
            $sql = "DELETE FROM {$this->pivotTable} 
                    WHERE ID_pastel = :id_pastel AND ID_ingrediente = :id_ingrediente";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pastel', $pastelId);
            $stmt->bindParam(':id_ingrediente', $ingredienteId);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getIngredientes($pastelId) {
        try {
          
            $sql = "SELECT i.*, 
                    IFNULL(u.name, 'Usuario Eliminado') AS created_by_name
                    FROM Ingrediente i
                    JOIN {$this->pivotTable} pi ON i.ID_ingrediente = pi.ID_ingrediente
                    LEFT JOIN users u ON i.created_by = u.id
                    WHERE pi.ID_pastel = :id_pastel
                    AND i.deleted = 0";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id_pastel', $pastelId);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }


    public function removeAllIngredientes($pastelId) {
    try {
        $sql = "DELETE FROM {$this->pivotTable} 
                WHERE ID_pastel = :id_pastel";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_pastel', $pastelId);
        
        return $stmt->execute();
    } catch (\PDOException $e) {
        return false;
    }
}

}
