<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/movimentacao.php';

class MovimentacaoModelTest extends TestCase
{
    public function testMovimentacaoModel()
    {
        $mov = new Movimentacao(1, "entrada", 10.25, "João", "2024-01-01 10:00:00", 5);
        $this->assertEquals(1, $mov->bebida_id);
        $this->assertEquals("entrada", $mov->tipo);
        $this->assertEquals(10.25, $mov->volume);
        $this->assertEquals("João", $mov->responsavel);
        $this->assertEquals("2024-01-01 10:00:00", $mov->data_registro);
        $this->assertEquals(5, $mov->id);
    }
}
?>