<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../dao/usuarios-dao.php';
require_once __DIR__ . '/../model/usuario.php';
require_once __DIR__ . '/../config/database.php';

class UsuarioDaoTest extends TestCase
{
    private $dao;

    protected function setUp(): void
    {
        $db = (new Database())->getConnection();
        $this->dao = new UsuarioDAO($db);
    }

    public function testCriarEBuscarUsuario()
    {
        $usuario = new Usuario("TesteUser", "testeuser@email.com", "senha123");
        $this->dao->criarUsuario($usuario);

        $result = $this->dao->buscarPorEmail("testeuser@email.com");
        $this->assertNotNull($result);
        $this->assertEquals("TesteUser", $result['nome']);
        $this->assertEquals("testeuser@email.com", $result['email']);
    }
}
?>
