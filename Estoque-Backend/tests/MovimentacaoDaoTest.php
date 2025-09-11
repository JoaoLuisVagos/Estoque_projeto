<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../dao/movimentacao-dao.php';
require_once __DIR__ . '/../model/movimentacao.php';
require_once __DIR__ . '/../config/database.php';

class MovimentacaoDaoTest extends TestCase
{
    private $dao;

    protected function setUp(): void
    {
        $db = (new Database())->getConnection();
        $this->dao = new MovimentacaoDAO($db);
    }

    public function testSalvarMovimentacao()
    {
        $mov = new Movimentacao(1, "entrada", 5.5, "Maria", date('Y-m-d H:i:s'));
        $result = $this->dao->saveMovimentacao($mov);
        $this->assertTrue($result);
    }
}
?>
