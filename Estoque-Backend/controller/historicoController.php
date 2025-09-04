<?php
require_once __DIR__."/../dao/HistoricoDAO.php";

class HistoricoController {

    public static function saveHistorico() {
        $data = Flight::request()->data->getData();
        $db  = (new Database())->getConnection(); 
        $dao = new HistoricoDAO($db);

        $h = new Historico();

        $h->bebida_id = $data['bebida_id'];
        $h->tipo = $data['tipo'];
        $h->volume = $data['volume'];
        $h->responsavel = $data['responsavel'];
        try {
            $dao->saveHistorico($h);
            Flight::halt(201); // sucesso
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function updateHistorico($id) {
        $data = Flight::request()->data->getData();
        $db  = (new Database())->getConnection(); 
        $dao = new HistoricoDAO($db);

        $h = new Historico();
        $h->id = $id;
        $h->bebida_id = $data['bebida_id'];
        $h->tipo = $data['tipo'];
        $h->volume = $data['volume'];
        $h->responsavel = $data['responsavel'];
        try {
            $dao->updateHistorico($h);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function deleteHistorico($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new HistoricoDAO($db);
        try {
            $dao->deleteHistorico($id);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function getHistoricoById($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new HistoricoDAO($db);
        try {
            $h = $dao->getHistoricoById($id);
            Flight::halt(200, $h);
        } catch(Exception $e) {
            Flight::halt(404,'Historico não encontrado');
        }
    }

    public static function getAllHistorico() {
        $db  = (new Database())->getConnection(); 
        $dao = new HistoricoDAO($db);

        $offset = $_GET['offset'] ?? 0;
        $limit  = $_GET['limit'] ?? 10;

        try {
            $h = $dao->getAllHistorico($offset, $limit, $_GET);
            Flight::halt(200, $h);
        } catch(Exception $e) {
            Flight::halt(404,'Historicos não encontrados');
        }
    }
}
