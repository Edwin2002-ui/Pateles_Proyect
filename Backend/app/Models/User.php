<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    private $db;
    private $table = 'users';

    public function __construct() {
        $this->db = Database::connect();
    }

    // Crear usuario local - DEVUELVE EL ID
    public function createLocal($email, $name, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO {$this->table} (email, name, password, auth_provider) 
                VALUES (:email, :name, :password, 'local')";
        
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            ':email' => $email,
            ':name' => $name,
            ':password' => $hashedPassword
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    // Crear usuario de Google - DEVUELVE EL ID
    public function createGoogle($email, $name, $googleId, $avatar = null) {
        $sql = "INSERT INTO {$this->table} (email, name, google_id, google_avatar, auth_provider) 
                VALUES (:email, :name, :google_id, :avatar, 'google')";
        
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            ':email' => $email,
            ':name' => $name,
            ':google_id' => $googleId,
            ':avatar' => $avatar
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }

    // Buscar usuario por email
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email AND deleted = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    // Buscar usuario por Google ID
    public function findByGoogleId($googleId) {
        $sql = "SELECT * FROM {$this->table} WHERE google_id = :google_id AND deleted = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':google_id' => $googleId]);
        return $stmt->fetch();
    }

    // Buscar usuario por ID
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id AND deleted = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Verificar contraseña
    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && $user['auth_provider'] === 'local') {
            return password_verify($password, $user['password']) ? $user : false;
        }
        
        return false;
    }

    // Eliminar lógicamente un usuario
    public function softDelete($id) {
        $sql = "UPDATE {$this->table} 
                SET deleted = 1, deleted_at = NOW() 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Obtener todos los usuarios activos
    public function getAll() {
        $sql = "SELECT id, email, name, auth_provider, google_avatar, created_at 
                FROM {$this->table} 
                WHERE deleted = 0 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}