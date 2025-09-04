<?php
require_once __DIR__."/../models/Historico.php";
require_once __DIR__ . '/../utils/helpers.php';

class HistoricoDAO {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function saveHistorico(Historico $h) {
        $query = "INSERT INTO historico (bebida_id, acao, volume, responsavel) VALUES (:bebida_id, :acao, :volume, :responsavel)";
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":bebida_id", $h->bebida_id);
            $stmt->bindParam(":acao", $h->tipo);
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

    public function updateHistorico(Historico $h) {
        $query = "UPDATE historico SET bebida_id = :bebida_id, acao = :acao, volume = :volume, responsavel = :responsavel WHERE id = :id";
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com o banco não foi estabelecida.");
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":bebida_id", $h->bebida_id);
            $stmt->bindParam(":acao", $h->tipo);
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

    public function deleteHistorico($id) {
        $query = "DELETE FROM historico WHERE id = :id";
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

    public function getHistoricoById($id) {
        $query = "SELECT * FROM historico WHERE id = :id";
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

    public function getAllHistorico($offset,$limit,$busca = []) {
        $allowed = ["data_registro", "acao"];

        if (!in_array($busca["orderBy"], $allowed)) $orderBy = "data_registro";

        $direction = strtoupper($busca["direction"]) === "ASC" ? "ASC" : "DESC";

        $query = "SELECT h.id, b.nome as bebida, h.acao, h.volume, h.responsavel, h.data_registro
                  FROM historico h
                  JOIN bebidas b ON b.id = h.bebida_id";

        if(is_array($busca) && count($busca) > 0){
			$where  = prepareWhere($busca);
			$query   .= " WHERE ".$where."";
		}

        $query .= " ORDER BY $orderBy $direction";

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
}
