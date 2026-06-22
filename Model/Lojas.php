<?php

class Lojas {

    private $cod;
    private $nome;
    private $tipo;
    private $cnpj;
    private $responsavel;
    private $telefone;
    private $email;
    private $cep;
    private $endereco;
    private $numero;
    private $bairro;
    private $cidade;
    private $estado;
    private $observacao;
    private $status;
    private $data_cadastro;

    function getCod() {
        return $this->cod;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getTipo() {
        return $this->tipo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function getCnpj() {
        return $this->cnpj;
    }

    function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    function getResponsavel() {
        return $this->responsavel;
    }

    function setResponsavel($responsavel) {
        $this->responsavel = $responsavel;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function getEmail() {
        return $this->email;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function getCep() {
        return $this->cep;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function getNumero() {
        return $this->numero;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function getBairro() {
        return $this->bairro;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function getCidade() {
        return $this->cidade;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function getEstado() {
        return $this->estado;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function getObservacao() {
        return $this->observacao;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    function getStatus() {
        return $this->status;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function getData_cadastro() {
        return $this->data_cadastro;
    }

    function setData_cadastro($data_cadastro) {
        $this->data_cadastro = $data_cadastro;
    }
}
?>
