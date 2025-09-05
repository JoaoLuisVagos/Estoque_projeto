<?php
require 'vendor/autoload.php';
require_once __DIR__."/../config/database.php";
require_once __DIR__."/../controller/bebidasController.php";
require_once __DIR__."/../controller/movimentacaoController.php";

//Rotas para criação de bebidas
Flight::route('POST /bebida', array('BebidaController','saveEstoque'));
Flight::route('POST /bebida/@id/update', array('BebidaController','updateEstoque'));
Flight::route('DELETE /bebida/@id/delete', array('BebidaController','deleteEstoque'));
Flight::route('GET /bebida/@id/getByID', array('BebidaController','getEstoqueById'));
Flight::route('GET /bebidas', array('BebidaController','getAllEstoque'));
Flight::route('GET /bebida/total/@tipo', ['BebidaController','getTotalByTipo']);

//Rotas Movimentacao
Flight::route('POST /movimentacao', array('MovimentacaoController','saveMovimentacao'));
Flight::route('POST /movimentacao/@id/update', array('MovimentacaoController','updateMovimentacao'));
Flight::route('DELETE /movimentacao/@id/delete', array('MovimentacaoController','deleteMovimentacao'));
Flight::route('GET /movimentacao/@id/getByID', array('MovimentacaoController','getMovimentacaoById'));
Flight::route('GET /movimentacao/bebida/@id/getByID', array('MovimentacaoController','getMovimentacaoByIdBebida'));
Flight::route('GET /movimentacoes', array('MovimentacaoController','getAllMovimentacao'));



