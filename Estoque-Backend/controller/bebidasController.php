<?php
require_once __DIR__."/../dao/bebidasDao.php";

class BebidaController {

    public static function saveEstoque() {
        $data = Flight::request()->data->getData();

        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);

        $bebida = new Bebida();
        $bebida->nome = $data['nome'];
        $bebida->tipo = $data['tipo'];
        $bebida->volume = $data['volume'];
        $bebida->responsavel = $data['responsavel'];

        try {
            $dao->saveEstoque($bebida);
            Flight::halt(201); // sucesso
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function updateEstoque($id) {
        $data = Flight::request()->data->getData();

        $db  = (new Database())->getConnection();
        $dao = new BebidaDAO($db);

        $bebida = new Bebida();
        $bebida->id = $id;
        $bebida->nome = $data['nome'];
        $bebida->tipo = $data['tipo'];
        $bebida->volume = $data['volume'];
        $bebida->responsavel = $data['responsavel'];

        try {
            $dao->updateEstoque($bebida);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function deleteEstoque($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);
        try {
            $dao->deleteEstoque($id);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function getEstoqueById($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);

        $bebida = $dao->getEstoqueById($id, $_GET);
        if($bebida)
            Flight::json($bebida);
        else
            Flight::halt(404,'bebida não encontrada');
    }

    public static function getAllEstoque() {
        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);

        $offset = $_GET['offset'] ?? 0;
        $limit  = $_GET['limit'] ?? 10;

        $bebidas = $dao->getAllEstoque($offset, $limit, $_GET);
        if($bebidas)
            Flight::json($bebidas);
        else
            Flight::halt(404,'Nebidas não encontradas');
    }

}
