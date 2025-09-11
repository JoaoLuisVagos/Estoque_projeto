<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../controller/movimentacao-controller.php';

class MovimentacaoControllerTest extends TestCase
{
    public function testControllerClassExists()
    {
        $this->assertTrue(class_exists('MovimentacaoController'));
    }
}
?>
