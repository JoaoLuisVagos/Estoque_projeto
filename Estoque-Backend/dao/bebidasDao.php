<?php
require_once __DIR__."/../model/bebidasModel.php";
require_once __DIR__ . '/../utils/helpers.php';


class BebidaDAO {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function saveEstoque(Bebida $bebida) {
        $query = "INSERT INTO bebidas (nome, tipo_bebida, volume, responsavel, estoque_total) VALUES (:nome, :tipo_bebida, :volume, :responsavel, :estoque_total)";
        
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nome", $bebida->nome);
            $stmt->bindParam(":tipo_bebida", $bebida->tipo_bebida);
            $stmt->bindParam(":volume", $bebida->volume);
            $stmt->bindParam(":estoque_total", $bebida->estoque_total);
            $stmt->bindParam(":responsavel", $bebida->responsavel);

            if ($stmt->execute()) {
                $bebida->id = $this->conn->lastInsertId();
                return true;
            } else {
                var_dump($stmt->errorInfo());
                return false;
            }

        } catch (PDOException $e) {
            var_dump("Erro PDO: " . $e->getMessage());
            die;
        } catch (Exception $e) {
            var_dump("Erro: " . $e->getMessage());
            die;
        }
    }

    public function updateEstoque(Bebida $bebida) {
        $query = "UPDATE bebidas SET nome = :nome, tipo_bebida = :tipo_bebida, volume = :volume, responsavel = :responsavel WHERE id = :id";
        
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nome", $bebida->nome);
            $stmt->bindParam(":tipo_bebida", $bebida->tipo_bebida);
            $stmt->bindParam(":volume", $bebida->volume);
            $stmt->bindParam(":responsavel", $bebida->responsavel);
            $stmt->bindParam(":id", $bebida->id);

            if ($stmt->execute()) {
                return true;
            } else {
                var_dump($stmt->errorInfo());
                return false;
            }

        } catch (PDOException $e) {
            var_dump("Erro PDO: " . $e->getMessage());
            die;
        } catch (Exception $e) {
            var_dump("Erro: " . $e->getMessage());
            die;
        }
    }

    public function deleteEstoque($id) {
        $query = "UPDATE bebidas SET excluido = 1 WHERE id = :id";
        
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                return true;
            } else {
                var_dump($stmt->errorInfo());
                return false;
            }

        } catch (PDOException $e) {
            var_dump("Erro PDO: " . $e->getMessage());
            die;
        } catch (Exception $e) {
            var_dump("Erro: " . $e->getMessage());
            die;
        }
    }

    public function getEstoqueById($id, $busca = []) {
        $query = "SELECT * FROM bebidas WHERE id = :id";

        if(is_array($busca) && count($busca) > 0){
			$where  = prepareWhere($busca);
			$query   .= " AND ".$where."";
		}
        
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                var_dump($stmt->errorInfo());
                return false;
            }

        } catch (PDOException $e) {
            var_dump("Erro PDO: " . $e->getMessage());
            die;
        } catch (Exception $e) {
            var_dump("Erro: " . $e->getMessage());
            die;
        }
    }

    public function getAllEstoque($offset,$limit,$busca = []) {
        
        $query = "SELECT * FROM bebidas";

        if(is_array($busca) && count($busca) > 0){
			$where  = prepareWhere($busca);
			$query   .= " WHERE ".$where."";
		}

        $query .= " LIMIT $offset, $limit";

        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            var_dump("Erro: " . $e->getMessage());
            die;
        }
    }

    public function getTotalVolumeByTipo($tipo_bebida) {
        $query = "SELECT COALESCE(SUM(estoque_total),0) as total 
                FROM bebidas 
                WHERE tipo_bebida = :tipo_bebida AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tipo_bebida", $tipo_bebida);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['total'];
    }

    public function hasCapacity($tipo_bebida, $novoVolume) {
        $limite = $tipo_bebida === "alcoolica" ? 500 : 400;
        $totalAtual = $this->getTotalVolumeByTipo($tipo_bebida);
        return ($totalAtual + $novoVolume) <= $limite;
    }

    public function hasDifferentTypeStored($tipo_bebida) {
        $query = "SELECT COUNT(*) as qtd 
                FROM bebidas 
                WHERE tipo_bebida != :tipo_bebida AND excluido = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":tipo_bebida", $tipo_bebida);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['qtd'] > 0;
    }

    public function updateEstoqueTotal($id, $novoEstoque) {
        $query = "UPDATE bebidas SET estoque_total = :estoque WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estoque", $novoEstoque);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
