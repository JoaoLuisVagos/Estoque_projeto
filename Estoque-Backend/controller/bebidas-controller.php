<?php
require_once __DIR__ . '/../dao/bebidas-dao.php';
require_once __DIR__ . '/../model/bebida.php';

class BebidasController {
    public static function criarBebida() {
        $data = $_POST;
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

        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['imagem'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid("bebida_") . "." . $ext;
            $dest = __DIR__ . "/../public/imagens/" . $filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $imagem = $filename;
            }
        }

        $bebida = new Bebida(
            $data['nome'],
            $tipo_bebida,
            $estoque_total,
            $data['excluido'] ?? 0,
            $data['responsavel'] ?? null,
            date('Y-m-d H:i:s'),
            null,
            $imagem
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
        if (!empty($_FILES) || (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') !== false)) {
            $data = $_POST;
        } else {
            $data = Flight::request()->data->getData();
        }

        $db = (new Database())->getConnection();
        $dao = new BebidaDAO($db);

        $atual = $dao->buscarPorId($id);
        if (!$atual) {
            Flight::halt(404, 'Bebida não encontrada');
        }

        $currentEstoque = isset($atual['estoque_total']) ? (float)$atual['estoque_total'] : 0;
        $newEstoque = isset($data['estoque_total']) ? (float)$data['estoque_total'] : $currentEstoque;
        $tipoBebida = $data['tipo_bebida'] ?? ($atual['tipo_bebida'] ?? '');

        $totalAtual = $dao->getTotalVolumeByTipo($tipoBebida);
        $totalSemAtual = $totalAtual - $currentEstoque;
        $limite = $tipoBebida === "alcoolica" ? 500 : 400;
        if (($totalSemAtual + $newEstoque) > $limite) {
            Flight::halt(400, "Limite de estoque para o tipo '{$tipoBebida}' excedido.");
        }

        $imagem = $atual['imagem'] ?? null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['imagem'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid("bebida_") . "." . $ext;
            $dest = __DIR__ . "/../public/imagens/" . $filename;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                if (!empty($imagem) && file_exists(__DIR__ . "/../public/imagens/" . $imagem)) {
                    @unlink(__DIR__ . "/../public/imagens/" . $imagem);
                }
                $imagem = $filename;
            }
        }

        $b = new Bebida(
            $data['nome'] ?? $atual['nome'] ?? '',
            $tipoBebida,
            $newEstoque,
            $data['excluido'] ?? ($atual['excluido'] ?? 0),
            $data['responsavel'] ?? ($atual['responsavel'] ?? null),
            $data['data_registro'] ?? ($atual['data_registro'] ?? null),
            $id,
            $imagem
        );

        try {
            $ok = $dao->atualizarBebida($b);

            if ($ok && isset($imagem)) {
                $dao->atualizarImagem($id, $imagem);
            }

            if ($ok) {
                Flight::json(['mensagem' => 'Bebida atualizada com sucesso']);
            } else {
                Flight::halt(500, 'Erro ao atualizar bebida');
            }
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public static function excluirBebida($id) {
        $db  = (new Database())->getConnection(); 
        $dao = new BebidaDAO($db);
        $movimentacaoDao = new MovimentacaoDAO($db);
        try {
            $movimentacaoDao->softDeleteByBebida($id);
            $dao->excluirBebida($id);
            Flight::halt(200);
        } catch(Exception $e) {
            Flight::halt(500, $e->getMessage());
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