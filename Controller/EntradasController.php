<?php

if (file_exists("../DAL/EntradasDAO.php")) {
    require_once("../DAL/EntradasDAO.php");
} else if (file_exists("DAL/EntradasDAO.php")) {
    require_once("DAL/EntradasDAO.php");
} else {
    require_once("../../DAL/EntradasDAO.php");
}

class EntradasController {

    private $entradasDAO;

    public function __construct() {
        $this->entradasDAO = new EntradasDAO();
    }

    public function Cadastrar(Entradas $entradas) {

        if (1==1) {

            return $this->entradasDAO->Cadastrar($entradas);
        } else {
            return false;
        }
    }

    public function RetornarEntradas(string $termo, int $tipo, int $status) {
        if ($status != 0) {
            return $this->entradasDAO->RetornarEntradas($termo, $tipo, $status);
        } else {
            return null;
        }
    }

    public function RetornarEntradasMes(string $mes, string $ano, int $cod_orgao, int $tipo) {
        if ($cod_orgao != 0) {
            return $this->entradasDAO->RetornarEntradasMes($mes, $ano, $cod_orgao, $tipo);
        } else {
            return null;
        }
    }

    public function RetornarEntradasDiaMesAno(string $dia, string $mes, string $ano, int $cod_orgao, int $tipo) {
        if ($cod_orgao != 0) {
            return $this->entradasDAO->RetornarEntradasDiaMesAno($dia, $mes, $ano, $cod_orgao, $tipo);
        } else {
            return null;
        }
    }

    public function RetornarUltimoEntradas(int $cod_orgao) {
        return $this->entradasDAO->RetornarUltimoEntradas($cod_orgao);
    }

    public function RetornarNomeEntradas(int $id) {
        if ($id != 0) {

            return $this->entradasDAO->RetornarNomeEntradas($id);
        } else {
            return null;
        }
    }

    public function AlterStatus(int $status, int $cod) {
        if ($cod != 0 && $status != 0) {

            return $this->entradasDAO->AlterStatus($status, $cod);
        } else {
            return null;
        }
    }

    public function Deletar(int $coddeletar) {
        if ($coddeletar != 0) {

            return $this->entradasDAO->Deletar($coddeletar);
        } else {
            return null;
        }
    }
   
    public function AlterarImagem(string $thumb, int $cod) {
        if ($cod != 0 &&  strlen($thumb >= 1)) {   
            return $this->entradasDAO->AlterarImagem($thumb, $cod);
        } else {
            return null;
        }
    }

}
