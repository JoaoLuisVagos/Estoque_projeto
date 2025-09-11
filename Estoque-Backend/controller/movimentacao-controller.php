<?php
require_once __DIR__ . '/../dao/movimentacao-dao.php';
require_once __DIR__ . '/../dao/bebidas-dao.php';
require_once __DIR__ . '/../model/movimentacao.php';

class MovimentacaoController {

    public static function saveMovimentacao() {
        $data = Flight::request()->data->getData();
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);
        $bebidaDao = new BebidaDAO($db);

        $h = new Movimentacao(
            $data['bebida_id'],
            $data['tipo'],
            $data['volume'],
            $data['responsavel'],
        );

        try {
            $stmtCheck = $db->prepare("SELECT id FROM bebidas WHERE id = :id");
            $stmtCheck->bindParam(":id", $h->bebida_id, PDO::PARAM_INT);
            $stmtCheck->execute();

            if (!$stmtCheck->fetch()) {
                Flight::halt(400, "Bebida não encontrada com ID: " . $h->bebida_id);
            }

            $bebidaId = $data['bebida_id'];
            $tipo = $data['tipo'];
            $volume = (float)$data['volume'];
            $bebida = $bebidaDao->getEstoqueById($bebidaId);

            if ($tipo === "saida" && $bebida['estoque_total'] < $volume) {
                Flight::json(["error" => "Estoque insuficiente para saída"], 400);
                return;
            }

            $tipo_bebida = $bebida['tipo_bebida'];

            if ($tipo === "entrada" && !$bebidaDao->hasCapacity($tipo_bebida, $volume)) {
                $limite = $tipo_bebida === "alcoolica" ? 500 : 400;
                Flight::json(["error" => "Capacidade da seção de {$tipo_bebida} excedida! Limite máximo: {$limite} litros."], 400);
                return;
            }

            $novoEstoque = $tipo === "entrada" ? $bebida['estoque_total'] + $volume : $bebida['estoque_total'] - $volume;

            $bebidaDao->updateEstoqueTotal($bebidaId, $novoEstoque);

            $dao->saveMovimentacao($h);
            Flight::halt(201);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function updateMovimentacao($id) {
        $data = Flight::request()->data->getData();
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);

        $h = new Movimentacao(
            $data['bebida_id'],
            $data['tipo'],
            $data['volume'],
            $data['responsavel'],
            $data['data_registro'] ?? null,
            $id
        );
        try {
            $dao->updateMovimentacao($h);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function deleteMovimentacao($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);
        try {
            $dao->deleteMovimentacao($id);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function getMovimentacaoById($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);
        $mov = $dao->getMovimentacaoById($id, $_GET);
        if($mov)
            Flight::json($mov);
        else
            Flight::halt(404,'Movimentação não encontrada');
    }

    public static function getMovimentacaoByIdBebida($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);
        $movs = $dao->getMovimentacaoByIdBebida($id, $_GET);
        if($movs)
            Flight::json($movs);
        else
            Flight::halt(404,'Movimentação não encontrada');
    }

    public static function getAllMovimentacao() {
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);

        $offset = $_GET['offset'] ?? 0;
        $limit  = $_GET['limit'] ?? 10;

        $movs = $dao->getAllMovimentacao($offset, $limit, $_GET);
        if($movs)
            Flight::json($movs);
        else
            Flight::halt(404,'Movimentações não encontradas');
    }
}