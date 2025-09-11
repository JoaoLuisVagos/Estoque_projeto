<?php
require_once __DIR__ . '/../dao/usuarios-dao.php';
require_once __DIR__ . '/../model/usuario.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $usuarioDao;
    private $jwt_secret = 'secreto123';

    public function __construct() {
        $this->usuarioDao = new UsuarioDAO((new Database())->getConnection());
    }

    public function registrar($data) {
        if ($this->usuarioDao->buscarPorEmail($data['email'])) {
            Flight::json(['erro' => 'Email já cadastrado'], 409);
            return;
        }
        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);
        $usuario = new Usuario($data['nome'], $data['email'], $senhaHash);
        $this->usuarioDao->criarUsuario($usuario);
        Flight::json(['mensagem' => 'Usuário criado com sucesso']);
    }

    public static function login() {
        $data = Flight::request()->data->getData();
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';

        if (!$email || !$senha) {
            Flight::halt(400, 'Email e senha são obrigatórios');
        }

        $db = (new Database())->getConnection();
        $dao = new UsuarioDAO($db);

        try {
            $usuario = $dao->buscarPorEmail($email);
            if (!$usuario) {
                Flight::halt(401, 'Usuário ou senha inválidos');
            }

            if (!password_verify($senha, $usuario['senha'])) {
                Flight::halt(401, 'Usuário ou senha inválidos');
            }

            unset($usuario['senha']);

            Flight::json([
                'mensagem' => 'Login realizado com sucesso',
                'usuario' => $usuario
            ]);
        } catch (Exception $e) {
            Flight::halt(500, $e->getMessage());
        }
    }

    public function autenticar() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            Flight::json(['erro' => 'Token não fornecido'], 401);
            exit;
        }
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        try {
            $decoded = JWT::decode($token, new Key($this->jwt_secret, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            Flight::json(['erro' => 'Token inválido'], 401);
            exit;
        }
    }
}
?>