<?php

class ListaEntradas {

    private $cod;
    private $cod_entrada;
    private $ata_pregao;
    private $cod_produto;
    private $lote;
    private $mes_validade;
    private $ano_validade;
    private $qtd;
    private $valor_total;
    
    function getAta_pregao() {
        return $this->ata_pregao;
    }

    function setAta_pregao($ata_pregao) {
        $this->ata_pregao = $ata_pregao;
    }
 
    function getCod_produto() {
        return $this->cod_produto;
    }

    function setCod_produto($cod_produto) {
        $this->cod_produto = $cod_produto;
    }

    function getCod() {
        return $this->cod;
    }

    function getCod_entrada() {
        return $this->cod_entrada;
    }

    function getLote() {
        return $this->lote;
    }

    function getMes_validade() {
        return $this->mes_validade;
    }

    function getAno_validade() {
        return $this->ano_validade;
    }

    function getQtd() {
        return $this->qtd;
    }

    function getValor_total() {
        return $this->valor_total;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setCod_entrada($cod_entrada) {
        $this->cod_entrada = $cod_entrada;
    }

    function setLote($lote) {
        $this->lote = $lote;
    }

    function setMes_validade($mes_validade) {
        $this->mes_validade = $mes_validade;
    }

    function setAno_validade($ano_validade) {
        $this->ano_validade = $ano_validade;
    }

    function setQtd($qtd) {
        $this->qtd = $qtd;
    }

    function setValor_total($valor_total) {
        $this->valor_total = $valor_total;
    }

}
?>








