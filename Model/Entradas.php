<?php
        
class Entradas {

   
    private $cod;
    private $ata_pregao;
    private $cod_solicitacao;
    private $n_notafiscal;
    private $fornecedor;
    private $cod_produto;
    private $cod_funcionario;
    private $dia;
    private $mes;
    private $ano;
    private $cod_orgao;
    private $img;
    private $status;
    private $conferidor1;
    private $conferidor2;
    private $conferidor3;
    
    
    function getCod_solicitacao() {
        return $this->cod_solicitacao;
    }

    function setCod_solicitacao($cod_solicitacao) {
        $this->cod_solicitacao = $cod_solicitacao;
    }

        
    function getFornecedor() {
        return $this->fornecedor;
    }

    function setFornecedor($fornecedor) {
        $this->fornecedor = $fornecedor;
    }
    
    function getAta_pregao() {
        return $this->ata_pregao;
    }

    function setAta_pregao($ata_pregao) {
        $this->ata_pregao = $ata_pregao;
    }
    
    function getConferidor1() {
        return $this->conferidor1;
    }

    function getConferidor2() {
        return $this->conferidor2;
    }

    function getConferidor3() {
        return $this->conferidor3;
    }

    function setConferidor1($conferidor1) {
        $this->conferidor1 = $conferidor1;
    }

    function setConferidor2($conferidor2) {
        $this->conferidor2 = $conferidor2;
    }

    function setConferidor3($conferidor3) {
        $this->conferidor3 = $conferidor3;
    }
    
    function getStatus() {
        return $this->status;
    }

    function setStatus($status) {
        $this->status = $status;
    }    
            
    function getCod() {
        return $this->cod;
    }

    function getN_notafiscal() {
        return $this->n_notafiscal;
    }

    function getCod_produto() {
        return $this->cod_produto;
    }

    function getCod_funcionario() {
        return $this->cod_funcionario;
    }

    function getDia() {
        return $this->dia;
    }

    function getMes() {
        return $this->mes;
    }

    function getAno() {
        return $this->ano;
    }

    function getCod_orgao() {
        return $this->cod_orgao;
    }

    function getImg() {
        return $this->img;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setN_notafiscal($n_notafiscal) {
        $this->n_notafiscal = $n_notafiscal;
    }

    function setCod_produto($cod_produto) {
        $this->cod_produto = $cod_produto;
    }

    function setCod_funcionario($cod_funcionario) {
        $this->cod_funcionario = $cod_funcionario;
    }

    function setDia($dia) {
        $this->dia = $dia;
    }

    function setMes($mes) {
        $this->mes = $mes;
    }

    function setAno($ano) {
        $this->ano = $ano;
    }

    function setCod_orgao($cod_orgao) {
        $this->cod_orgao = $cod_orgao;
    }

    function setImg($img) {
        $this->img = $img;
    }


    
}
?>








