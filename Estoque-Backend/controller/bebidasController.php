<?php
require_once __DIR__."/../dao/bebidasDao.php";

class BebidaController {

    public static function saveEstoque() {
        $data = Flight::request()->data->getData();

        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);

        $tipo = $data['tipo'];
        $volume = $data['volume'];
        $volumeInicial = isset($data['volume']) ? (int)$data['volume'] : 0;

        if (!$dao->hasCapacity($tipo, $volume)) {
            $limite = $tipo === "alcoolica" ? 500 : 400;
            Flight::json(["error" => "Capacidade da seção de {$tipo} excedida! Limite máximo: {$limite} unidades."], 400);
            return;
        }

        $bebida = new Bebida();
        $bebida->nome = $data['nome'];
        $bebida->tipo = $data['tipo'];
        $bebida->volume = $volumeInicial;
        $bebida->estoque_total = $volumeInicial;
        $bebida->responsavel = $data['responsavel'];

        try {
            $dao->saveEstoque($bebida);
            Flight::halt(201);
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
        $movimentacaoDao = new MovimentacaoDAO($db);
        try {
            $movimentacaoDao->softDeleteByBebida($id);
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

    public function getTotalByTipo($tipo) {
        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);

        $total = $dao->getTotalVolumeByTipo($tipo);
        $limite = $tipo === "alcoolica" ? 500 : 400;
        $espacoDisponivel = $limite - $total;

        Flight::json([
            "tipo" => $tipo,
            "total" => $total,
            "limite" => $limite,
            "disponivel" => $espacoDisponivel
        ]);
    }


}
