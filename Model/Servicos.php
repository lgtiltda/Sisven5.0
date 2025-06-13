<?php

class Servicos {

    private $cod;
    private $nome;
    private $descricao;
    private $valor;
    private $categoria;
    private $img;
    private $codbarra;
    private $tipo;
    private $est_max;
    private $est_mim;
    private $fornecedor;
    
    function getTipo() {
        return $this->tipo;
    }

    function getEst_max() {
        return $this->est_max;
    }

    function getEst_mim() {
        return $this->est_mim;
    }

    function getFornecedor() {
        return $this->fornecedor;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setEst_max($est_max) {
        $this->est_max = $est_max;
    }

    function setEst_mim($est_mim) {
        $this->est_mim = $est_mim;
    }

    function setFornecedor($fornecedor) {
        $this->fornecedor = $fornecedor;
    }

        

    function getCodbarra() {
        return $this->codbarra;
    }

    function setCodbarra($codbarra) {
        $this->codbarra = $codbarra;
    }

    function getImg() {
        return $this->img;
    }

    function setImg($img) {
        $this->img = $img;
    }

    function getCod() {
        return $this->cod;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getValor() {
        return $this->valor;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

}
?>








