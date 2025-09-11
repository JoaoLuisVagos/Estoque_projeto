<?php
class Bebida {
    public $id;
    public $nome;
    public $tipo_bebida;
    public $estoque_total;
    public $excluido;
    public $responsavel;
    public $imagem; 
    public $data_registro;

    public function __construct($nome, $tipo_bebida, $estoque_total = 0, $excluido = 0, $responsavel = null, $data_registro = null, $id = null, $imagem = null) {
        $this->nome = $nome;
        $this->tipo_bebida = $tipo_bebida;
        $this->estoque_total = $estoque_total;
        $this->excluido = $excluido;
        $this->responsavel = $responsavel;
        $this->imagem = $imagem;
        $this->data_registro = $data_registro;
        $this->id = $id;
    }
}
?>