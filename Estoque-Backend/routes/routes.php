<?php
require 'vendor/autoload.php';
require_once __DIR__."/../config/database.php";
require_once __DIR__."/../controller/bebidasController.php";


Flight::route('POST /bebida', array('BebidaController','saveEstoque'));
Flight::route('POST /bebida/@id/update', array('BebidaController','updateEstoque'));
Flight::route('DELETE /bebida/@id/delete', array('BebidaController','deleteEstoque'));
Flight::route('GET /bebida/@id/getByID', array('BebidaController','getEstoqueById'));
Flight::route('GET /bebidas', array('BebidaController','getEstoque'));


