<?php
require_once __DIR__."/../dao/movimentacaoDao.php";

class MovimentacaoController {

    public static function saveMovimentacao() {
        $data = Flight::request()->data->getData();
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);
        $bebidaDao = new BebidaDAO($db);

        $h = new Movimentacao();

        $h->bebida_id = $data['bebida_id'];
        $h->tipo = $data['tipo'];
        $h->volume = $data['volume'];
        $h->responsavel = $data['responsavel'];

        try {
            $stmtCheck = $db->prepare("SELECT id FROM bebidas WHERE id = :id");
            $stmtCheck->bindParam(":id", $h->bebida_id, PDO::PARAM_INT);
            $stmtCheck->execute();

            if (!$stmtCheck->fetch()) {
                Flight::halt(400, "Bebida não encontrada com ID: " . $h->bebida_id);
            }


            $bebidaId = $data['bebida_id'];
            $tipo = $data['tipo'];
            $volume = (int)$data['volume'];
            $bebida = $bebidaDao->getEstoqueById($bebidaId);

            if ($tipo === "saida" && $bebida['estoque_total'] < $volume) {
                Flight::json(["error" => "Estoque insuficiente para saída"], 400);
                return;
            }

            $tipo_bebida = $bebida['tipo_bebida'];

            if ($tipo === "entrada" && !$bebidaDao->hasCapacity($tipo_bebida, $data['volume'])) {
                $limite = $tipo_bebida === "alcoolica" ? 500 : 400;
                Flight::json(["error" => "Capacidade da seção de {$tipo} excedida! Limite máximo: {$limite} unidades."], 400);
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

        $h = new Movimentacao();
        $h->id = $id;
        $h->bebida_id = $data['bebida_id'];
        $h->tipo = $data['tipo'];
        $h->volume = $data['volume'];
        $h->responsavel = $data['responsavel'];
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
        $bebida = $dao->getMovimentacaoById($id, $_GET);
        if($bebida)
            Flight::json($bebida);
        else
            Flight::halt(404,'Movimentação não encontrada');
    }

    public static function getMovimentacaoByIdBebida($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);
        $bebida = $dao->getMovimentacaoByIdBebida($id, $_GET);
        if($bebida)
            Flight::json($bebida);
        else
            Flight::halt(404,'Movimentação não encontrada');
    }

    public static function getAllMovimentacao() {
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);

        $offset = $_GET['offset'] ?? 0;
        $limit  = $_GET['limit'] ?? 10;

        $bebidas = $dao->getAllMovimentacao($offset, $limit, $_GET);
        if($bebidas)
            Flight::json($bebidas);
        else
            Flight::halt(404,'Movimentações não encontradas');
    }
}
