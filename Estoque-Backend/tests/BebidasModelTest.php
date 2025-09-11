<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/bebida.php';

class BebidasModelTest extends TestCase
{
    public function testBebidaModel()
    {
        $bebida = new Bebida("Cerveja", "alcoolica", 100.5, 0, 1);
        $this->assertEquals("Cerveja", $bebida->nome);
        $this->assertEquals("alcoolica", $bebida->tipo_bebida);
        $this->assertEquals(100.5, $bebida->estoque_total);
        $this->assertEquals(0, $bebida->excluido);
        $this->assertEquals(1, $bebida->id);
    }
}
?>