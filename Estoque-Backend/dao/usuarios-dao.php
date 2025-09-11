<?php
require_once __DIR__ . '/../model/usuario.php';

class UsuarioDAO {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criarUsuario(Usuario $u) {
        $query = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $u->nome);
        $stmt->bindParam(":email", $u->email);
        $stmt->bindParam(":senha", $u->senha);
        return $stmt->execute();
    }

    public function buscarPorEmail($email) {
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>