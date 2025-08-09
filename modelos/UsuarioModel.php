<?php
require_once __DIR__ . '/../config/Conexion.php';

class UsuarioModel {
    private $pdo;
    
    public function __construct() { 
        $this->pdo = Conexion::getConexion(); 
    }

    public function findByUsername($username) {
        $st = $this->pdo->prepare("SELECT * FROM usuario WHERE username = ? LIMIT 1");
        $st->execute([$username]);
        return $st->fetch(PDO::FETCH_ASSOC);
    }
}
