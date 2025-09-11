<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../dao/bebidas-dao.php';
require_once __DIR__ . '/../model/bebida.php';
require_once __DIR__ . '/../config/database.php';

class BebidaDaoTest extends TestCase
{
    private $dao;

    protected function setUp(): void
    {
        $db = (new Database())->getConnection();
        $this->dao = new BebidaDAO($db);
    }

    public function testCriarEBuscarBebida()
    {
        $bebida = new Bebida("TesteLitros", "alcoolica", 123.45);
        $this->dao->criarBebida($bebida);

        $result = $this->dao->buscarPorId($bebida->id);
        $this->assertNotNull($result);
        $this->assertEquals("TesteLitros", $result['nome']);
        $this->assertEquals("alcoolica", $result['tipo_bebida']);
        $this->assertEquals(123.45, $result['estoque_total']);
    }
}
?>
