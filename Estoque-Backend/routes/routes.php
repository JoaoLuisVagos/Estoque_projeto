<?php
require 'vendor/autoload.php';
require_once __DIR__."/../config/database.php";
require_once __DIR__ . '/../controller/bebidas-controller.php';
require_once __DIR__ . '/../controller/movimentacao-controller.php';
require_once __DIR__ . '/../controller/auth-controller.php';

//Rotas para autenticação
Flight::route('POST /api/registrar', function(){
    $data = Flight::request()->data->getData();
    (new AuthController())->registrar($data);
});

Flight::route('POST /api/login', function(){
    $data = Flight::request()->data->getData();
    (new AuthController())->login($data);
});

Flight::route('GET /imagens/@filename', function($filename){
    $path = __DIR__ . '/../public/imagens/' . $filename;
    if (!file_exists($path)) {
        Flight::halt(404, "Imagem não encontrada.");
    }
    $mime = mime_content_type($path);
    header("Content-Type: $mime");
    readfile($path);
    exit;
});


//Rotas para criação de bebidas
Flight::route('POST /bebida', array('BebidasController','criarBebida'));
Flight::route('POST /bebida/@id/update', array('BebidasController','atualizarBebida'));
Flight::route('DELETE /bebida/@id/delete', array('BebidasController','excluirBebida'));
Flight::route('GET /bebida/@id/getByID', array('BebidasController','buscarPorId'));
Flight::route('GET /bebidas', array('BebidasController','listarTodas'));
Flight::route('GET /bebida/total/@tipo', ['BebidasController','getTotalByTipo']);

//Rotas Movimentacao
Flight::route('POST /movimentacao', array('MovimentacaoController','saveMovimentacao'));
Flight::route('POST /movimentacao/@id/update', array('MovimentacaoController','updateMovimentacao'));
Flight::route('DELETE /movimentacao/@id/delete', array('MovimentacaoController','deleteMovimentacao'));
Flight::route('GET /movimentacao/@id/getByID', array('MovimentacaoController','getMovimentacaoById'));
Flight::route('GET /movimentacao/bebida/@id/getByID', array('MovimentacaoController','getMovimentacaoByIdBebida'));
Flight::route('GET /movimentacoes', array('MovimentacaoController','getAllMovimentacao'));
