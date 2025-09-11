<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../controller/bebidas-controller.php';

class BebidasControllerTest extends TestCase
{
    public function testControllerClassExists()
    {
        $this->assertTrue(class_exists('BebidasController'));
    }
}
?>
