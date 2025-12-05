<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Repostero{
    private $db;
    private $table = 'reposteros';

    public function __construct()
    {
        $this->db= Database::connect();
    }


    public function create($data){
        try {
            $sql = "INSERT INTO {$this->table} (nombres, apellidos) 
                    VALUES (:nombres, :apellidos)";
            
            $stmt = $this->db->prepare($sql);

            $stmt->bindParam(':nombres', $data['nombres']);
            $stmt->bindParam(':apellidos', $data['apellidos']);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getall(){
        $sql = "SELECT * 
                FROM {$this->table}
                WHERE deleted = 0
                Order by created_at DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function findId($id){
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE id = :id 
                    AND deleted = 0"; 

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return false;
        }
    }


    public function delete($id)
    {
        try {
        
            $sql = "UPDATE {$this->table} SET 
                    deleted = 1, 
                    deleted_at = NOW() 
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }
}