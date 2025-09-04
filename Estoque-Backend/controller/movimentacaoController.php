<?php
require_once __DIR__."/../dao/movimentacaoDao.php";

class MovimentacaoController {

    public static function saveMovimentacao() {
        $data = Flight::request()->data->getData();
        $db  = (new Database())->getConnection(); 
        $dao = new MovimentacaoDAO($db);

        $h = new Movimentacao();

        $h->bebida_id = $data['bebida_id'];
        $h->tipo = $data['tipo'];
        $h->volume = $data['volume'];
        $h->responsavel = $data['responsavel'];
        try {
            $dao->saveMovimentacao($h);
            Flight::halt(201); // sucesso
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
