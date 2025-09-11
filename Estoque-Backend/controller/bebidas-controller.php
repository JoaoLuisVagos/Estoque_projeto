<?php
require_once __DIR__ . '/../dao/bebidas-dao.php';
require_once __DIR__ . '/../model/bebida.php';

class BebidasController {
    public static function criarBebida() {
        $data = Flight::request()->data->getData();
        $db = (new Database())->getConnection();
        $dao = new BebidaDAO($db);
        $movDao = new MovimentacaoDAO($db);

        $estoque_total = $data['estoque_total'] ?? 0;
        $tipo_bebida = $data['tipo_bebida'] ?? '';

        $totalAtual = $dao->getTotalVolumeByTipo($tipo_bebida);
        $limite = $tipo_bebida === "alcoolica" ? 500 : 400;

        if (($totalAtual + $estoque_total) > $limite) {
            Flight::halt(400, "Limite de estoque para o tipo '{$tipo_bebida}' excedido.");
        }

        $bebida = new Bebida(
            $data['nome'],
            $tipo_bebida,
            $estoque_total,
            $data['excluido'] ?? 0,
            $data['responsavel'] ?? null,
            $data['data_registro'] ?? null
        );

        try {
            $dao->criarBebida($bebida);
            $bebidaId = $db->lastInsertId();

            if ($estoque_total > 0) {
                $mov = new Movimentacao(
                    $bebidaId,
                    "entrada",
                    $estoque_total,
                    $data['responsavel']
                );
                $movDao->saveMovimentacao($mov);
            }

            Flight::json(['mensagem' => 'Bebida criada com sucesso'], 201);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function atualizarBebida($id) {
        $data = Flight::request()->data->getData();
        $db = (new Database())->getConnection();
        $dao = new BebidaDAO($db);

        $b = new Bebida(
            $data['nome'],
            $data['tipo_bebida'],
            $data['estoque_total'] ?? 0,
            $data['excluido'] ?? 0,
            $data['responsavel'] ?? null,
            $data['data_registro'] ?? null,
            $id
        );

        if ($dao->atualizarBebida($b)) {
            Flight::json(['mensagem' => 'Bebida atualizada com sucesso']);
        } else {
            Flight::halt(500, 'Erro ao atualizar bebida');
        }
    }

    public static function excluirBebida($id) {
        $db = (new Database())->getConnection();
        $dao = new BebidaDAO($db);

        if ($dao->excluirBebida($id)) {
            Flight::json(['mensagem' => 'Bebida excluída com sucesso']);
        } else {
            Flight::halt(500, 'Erro ao excluir bebida');
        }
    }

    public static function buscarPorId($id) {
        $db = (new Database())->getConnection();
        $dao = new BebidaDAO($db);

        $bebida = $dao->buscarPorId($id);
        if ($bebida) {
            Flight::json($bebida);
        } else {
            Flight::halt(404, 'Bebida não encontrada');
        }
    }

    public static function listarTodas() {
        $db = (new Database())->getConnection();
        $dao = new BebidaDAO($db);

        $bebidas = $dao->listarTodas();
        Flight::json($bebidas);
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
?>