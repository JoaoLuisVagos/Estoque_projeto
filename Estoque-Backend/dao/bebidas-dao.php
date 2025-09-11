<?php
require_once __DIR__ . '/../model/bebida.php';

class BebidaDAO {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function criarBebida(Bebida $b) {
        $query = "INSERT INTO bebidas (nome, tipo_bebida, estoque_total, excluido, responsavel, data_registro) 
                VALUES (:nome, :tipo_bebida, :estoque_total, :excluido, :responsavel, :data_registro)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $b->nome);
        $stmt->bindParam(":tipo_bebida", $b->tipo_bebida);
        $stmt->bindParam(":estoque_total", $b->estoque_total);
        $stmt->bindParam(":excluido", $b->excluido);
        $stmt->bindParam(":responsavel", $b->responsavel);
        $stmt->bindParam(":data_registro", $b->data_registro);
        return $stmt->execute();
    }

    public function atualizarBebida(Bebida $b) {
        $query = "UPDATE bebidas SET nome = :nome, tipo_bebida = :tipo_bebida, estoque_total = :estoque_total, excluido = :excluido, responsavel = :responsavel, data_registro = :data_registro WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nome", $b->nome);
        $stmt->bindParam(":tipo_bebida", $b->tipo_bebida);
        $stmt->bindParam(":estoque_total", $b->estoque_total);
        $stmt->bindParam(":excluido", $b->excluido);
        $stmt->bindParam(":responsavel", $b->responsavel);
        $stmt->bindParam(":data_registro", $b->data_registro);
        $stmt->bindParam(":id", $b->id);
        return $stmt->execute();
    }

    public function excluirBebida($id) {
        $query = "UPDATE bebidas SET excluido = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $query = "SELECT * FROM bebidas WHERE id = :id AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarTodas() {
        $query = "SELECT * FROM bebidas WHERE excluido = 0";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEstoqueById($id) {
        $query = "SELECT id, nome, tipo_bebida, estoque_total FROM bebidas WHERE id = :id AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasCapacity($tipo_bebida, $volume_adicional) {
        $limite = $tipo_bebida === "alcoolica" ? 500 : 400;
        $query = "SELECT SUM(estoque_total) as total FROM bebidas WHERE tipo_bebida = :tipo_bebida AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tipo_bebida", $tipo_bebida);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalAtual = $result ? (float)$result['total'] : 0;
        return ($totalAtual + (float)$volume_adicional) <= $limite;
    }

    public function updateEstoqueTotal($bebidaId, $novoEstoque) {
        $query = "UPDATE bebidas SET estoque_total = :estoque_total WHERE id = :id AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estoque_total", $novoEstoque);
        $stmt->bindParam(":id", $bebidaId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function getTotalVolumeByTipo($tipo_bebida) {
        $query = "SELECT SUM(estoque_total) as total FROM bebidas WHERE tipo_bebida = :tipo_bebida AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tipo_bebida', $tipo_bebida);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) $row['total'];
    }
}
?>