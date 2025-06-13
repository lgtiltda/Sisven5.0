<?php

if (file_exists("../DAL/MovimentoEmpresaDAO.php")) {
    require_once("../DAL/MovimentoEmpresaDAO.php");
} elseif (file_exists("DAL/MovimentoEmpresaDAO.php")) {
    require_once("DAL/MovimentoEmpresaDAO.php");
}

class MovimentoEmpresaController {

    private $movimentoEmpresaDAO;

    function __construct() {
        $this->movimentoEmpresaDAO = new MovimentoEmpresaDAO();
    }

    public function Cadastrar(MovimentoEmpresa $movimentoempresa) {
        if (
                $movimentoempresa->getCod_usu() > 0) {
            return $this->movimentoEmpresaDAO->Cadastrar($movimentoempresa);
        } else {
            return false;
        }
    }

    
    public function Deletar(int $coddeletar) {
        if ($coddeletar != 0) {
            return $this->movimentoEmpresaDAO->Deletar($coddeletar);
        } else {
            return false;
        }
    }
    public function RetornarUltCad(int $id) {
        if ($id!=0) {
            return $this->movimentoEmpresaDAO->RetornarUltCad($id);
        } else {
            return false;
        }
    }
    public function TesteCategoria(int $id) {
        if ($id!=0) {
            return $this->movimentoEmpresaDAO->TesteCategoria($id);
        } else {
            return false;
        }
    }

}

?>