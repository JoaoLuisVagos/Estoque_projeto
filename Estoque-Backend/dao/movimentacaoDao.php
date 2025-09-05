<?php
require_once __DIR__."/../model/movimentacaoModel.php";
require_once __DIR__ . '/../utils/helpers.php';

class MovimentacaoDAO {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function saveMovimentacao(Movimentacao $h) {
        $query = "INSERT INTO movimentacao (bebida_id, tipo, volume, responsavel) VALUES (:bebida_id, :tipo, :volume, :responsavel)";
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":bebida_id", $h->bebida_id);
            $stmt->bindParam(":tipo", $h->tipo);
            $stmt->bindParam(":volume", $h->volume);
            $stmt->bindParam(":responsavel", $h->responsavel);

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

    public function updateMovimentacao(Movimentacao $h) {
        $query = "UPDATE movimentacao SET bebida_id = :bebida_id, tipo = :tipo, volume = :volume, responsavel = :responsavel WHERE id = :id";
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":bebida_id", $h->bebida_id);
            $stmt->bindParam(":tipo", $h->tipo);
            $stmt->bindParam(":volume", $h->volume);
            $stmt->bindParam(":responsavel", $h->responsavel);
            $stmt->bindParam(":id", $h->id);

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

    public function deleteMovimentacao($id) {
        $query = "UPDATE movimentacao SET excluido = 1 WHERE id = :id";
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

    public function softDeleteByBebida($bebidaId) {
        $query = "UPDATE movimentacao SET excluido = 1 WHERE bebida_id = :bebida_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":bebida_id", $bebidaId);
        return $stmt->execute();
    }

    public function getMovimentacaoById($id, $busca) {
        $filtros = $busca;
        unset($filtros['excluido']); 

        $whereExtra = prepareWhere($filtros);

        if (isset($busca['excluido'])) {
            $excluidoFilter = "h.excluido = :excluido";
            if (!empty($whereExtra)) {
                $whereExtra .= " AND " . $excluidoFilter;
            } else {
                $whereExtra = $excluidoFilter;
            }
        }

        $query = "SELECT * FROM movimentacao as h WHERE h.id = :id";

        if (!empty($whereExtra)) {
            $query .= " AND " . $whereExtra;
        }

        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);

            if (isset($busca['excluido'])) {
                $stmt->bindParam(":excluido", $busca['excluido']);
            }

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

    public function getMovimentacaoByIdBebida($id, $busca) {
        $filtros = $busca;

        $whereExtra = "";

        if (isset($busca['excluido'])) {
            $whereExtra .= "h.excluido = :excluido";
            unset($filtros['excluido']);
        }

        $filtrosWhere = prepareWhere($filtros);
        if (!empty($filtrosWhere)) {
            if (!empty($whereExtra)) {
                $whereExtra .= " AND " . $filtrosWhere;
            } else {
                $whereExtra = $filtrosWhere;
            }
        }

        $allowed = ["data_registro", "tipo"];
        $orderBy = "data_registro";
        if (isset($busca["orderBy"]) && in_array($busca["orderBy"], $allowed)) {
            $orderBy = $busca["orderBy"];
        }
        $direction = (isset($busca["direction"]) && strtoupper($busca["direction"]) === "ASC") ? "ASC" : "DESC";

        $query = "SELECT h.*, b.nome as bebida, b.tipo_bebida as tipo_bebida
          FROM movimentacao as h
          INNER JOIN bebidas as b ON h.bebida_id = b.id
          WHERE b.id = :id";

        if (!empty($whereExtra)) {
            $whereExtra = str_replace("tipo =", "h.tipo =", $whereExtra);
            $query .= " AND " . $whereExtra;
        }

        $query .= " ORDER BY $orderBy $direction";


        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);

            if (isset($busca['excluido'])) {
                $stmt->bindParam(":excluido", $busca['excluido']);
            }

            foreach ($filtros as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function getAllMovimentacao($offset, $limit, $busca = []) {

        $filtros = $busca;
        unset($filtros['excluido']);

        $whereExtra = prepareWhere($filtros);

        if (isset($busca['excluido'])) {
            $excluidoFilter = "h.excluido = :excluido";
            if (!empty($whereExtra)) {
                $whereExtra .= " AND " . $excluidoFilter;
            } else {
                $whereExtra = $excluidoFilter;
            }
        } else {
            $whereExtra = ($whereExtra ? $whereExtra . " AND " : "") . "h.excluido = 0";
        }

        $allowed = ["data_registro", "tipo"];
        $orderBy = "data_registro";
        if (isset($busca["orderBy"]) && in_array($busca["orderBy"], $allowed)) {
            $orderBy = $busca["orderBy"];
        }
        $direction = (isset($busca["direction"]) && strtoupper($busca["direction"]) === "ASC") ? "ASC" : "DESC";

       
        $query = "SELECT h.*, b.nome as bebida, b.tipo_bebida as tipo_bebida
                FROM movimentacao h
                JOIN bebidas b ON b.id = h.bebida_id";

        if (!empty($whereExtra)) {
            $query .= " WHERE " . $whereExtra;
        }

        $query .= " ORDER BY $orderBy $direction";
        $query .= " LIMIT $offset, $limit";

        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            if (isset($busca['excluido'])) {
                $stmt->bindValue(':excluido', $busca['excluido'], PDO::PARAM_INT);
            }

            if (isset($busca['tipo'])) {
                $stmt->bindValue(':tipo', $busca['tipo'], PDO::PARAM_STR);
            }
        
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            var_dump("Erro: " . $e->getMessage());
            die;
        }   
    }
}
