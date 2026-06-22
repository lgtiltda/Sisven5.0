<?php

if (file_exists("../DAL/ListaEntradasDAO.php")) {
    require_once("../DAL/ListaEntradasDAO.php");
} else if (file_exists("DAL/ListaEntradasDAO.php")) {
    require_once("DAL/ListaEntradasDAO.php");
} else {
    require_once("../../DAL/ListaEntradasDAO.php");
}

class ListaEntradasController {

    private $listaentradasDAO;

    public function __construct() {
        $this->listaentradasDAO = new ListaEntradasDAO();
    }

    public function Cadastrar(ListaEntradas $listaentradas) {
        if (strlen($listaentradas->getCod_entrada()) >= 1) {

            return $this->listaentradasDAO->Cadastrar($listaentradas);
        } else {
            return false;
        }
    }

    public function RetornarListaEntradas(string $termo, int $tipo, int $status) {
        if ($tipo != 0 && $status !=0) {
            
            return $this->listaentradasDAO->RetornarListaEntradas($termo, $tipo, $status);
        } else {
            return false;
        }
    }

    public function RetornarProdutosVencidos(int $mes, int $ano, int $produto) {
        if ($mes != 0 && $ano !=0) {
            
            return $this->listaentradasDAO->RetornarProdutosVencidos($mes, $ano, $produto);
        } else {
            return false;
        }
    }

    public function RetornarUltimoListaEntradas(int $cod_orgao) {
        return $this->listaentradasDAO->RetornarUltimoListaEntradas($cod_orgao);
    }

    public function RetornarNomeListaEntradas(int $id) {
        if ($id != 0) {

            return $this->listaentradasDAO->RetornarNomeListaEntradas($id);
        } else {
            return null;
        }
    }
    
    public function Deletar(int $coddeletar) {
        if ($coddeletar != 0) {

            return $this->listaentradasDAO->Deletar($coddeletar);
        } else {
            return null;
        }
    }
    
    public function DeletarTodos(int $coddeletar) {
        if ($coddeletar != 0) {

            return $this->listaentradasDAO->DeletarTodos($coddeletar);
        } else {
            return null;
        }
    }

}
