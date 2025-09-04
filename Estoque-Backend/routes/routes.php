<?php
require 'vendor/autoload.php';
require_once __DIR__."/../config/database.php";
require_once __DIR__."/../controller/bebidasController.php";
require_once __DIR__."/../controller/historicoController.php";

//Rotas para criação de bebidas
Flight::route('POST /bebida', array('BebidaController','saveEstoque'));
Flight::route('POST /bebida/@id/update', array('BebidaController','updateEstoque'));
Flight::route('DELETE /bebida/@id/delete', array('BebidaController','deleteEstoque'));
Flight::route('GET /bebida/@id/getByID', array('BebidaController','getEstoqueById'));
Flight::route('GET /bebidas', array('BebidaController','getAllEstoque'));

//Rotas Historico
Flight::route('POST /historico', array('HistoricoController','saveHistorico'));
Flight::route('POST /historico/@id/update', array('HistoricoController','updateHistorico'));
Flight::route('DELETE /historico/@id/delete', array('HistoricoController','deleteHistorico'));
Flight::route('GET /historico/@id/getByID', array('HistoricoController','getHistoricoById'));
Flight::route('GET /historico', array('HistoricoController','getAllHistorico'));


