<?php
class Movimentacao {
    public $id;
    public $bebida_id;
    public $tipo;
    public $volume;
    public $responsavel;
    public $data_registro;

    public function __construct($bebida_id, $tipo, $volume, $responsavel, $data_registro = null, $id = null) {
        $this->bebida_id = $bebida_id;
        $this->tipo = $tipo;
        $this->volume = $volume;
        $this->responsavel = $responsavel;
        $this->data_registro = $data_registro;
        $this->id = $id;
    }
}
?>