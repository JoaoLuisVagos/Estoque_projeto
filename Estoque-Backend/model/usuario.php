<?php
class Usuario {
    public $id;
    public $nome;
    public $email;
    public $senha;

    public function __construct($nome, $email, $senha, $id = null) {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->id = $id;
    }
}
?>