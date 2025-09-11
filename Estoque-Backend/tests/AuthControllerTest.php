<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../controller/auth-controller.php';

class AuthControllerTest extends TestCase
{
    public function testControllerClassExists()
    {
        $this->assertTrue(class_exists('AuthController'));
    }
}
?>
