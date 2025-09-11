<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../model/usuario.php';

class UsuarioModelTest extends TestCase
{
    public function testUsuarioModel()
    {
        $usuario = new Usuario("Maria", "maria@email.com", "senha123", 2);
        $this->assertEquals("Maria", $usuario->nome);
        $this->assertEquals("maria@email.com", $usuario->email);
        $this->assertEquals("senha123", $usuario->senha);
        $this->assertEquals(2, $usuario->id);
    }
}
?>