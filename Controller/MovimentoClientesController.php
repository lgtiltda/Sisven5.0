<?php

if (file_exists("../DAL/MovimentoClientesDAO.php")) {
    require_once("../DAL/MovimentoClientesDAO.php");
} elseif (file_exists("DAL/MovimentoClientesDAO.php")) {
    require_once("DAL/MovimentoClientesDAO.php");
}

class MovimentoClientesController {

    private $movimentoClientesDAO;

    function __construct() {
        $this->movimentoClientesDAO = new MovimentoClientesDAO();
    }

    public function Cadastrar(MovimentoClientes $movimentoclientes) {
        if (1 == 1) {

            return $this->movimentoClientesDAO->Cadastrar($movimentoclientes);
        } else {
            return false;
        }
    }

    public function RetornarDiaMesAno(int $dia, int $mes, int $ano, int $tipo) {

        if ($mes != 0) {
            return $this->movimentoClientesDAO->RetornarDiaMesAno($dia, $mes, $ano, $tipo);
        } else {
            return null;
        }
    }

    public function RetornarDiaMesAnoTodos(int $dia, int $mes, int $ano) {

        if ($mes != 0) {

            return $this->movimentoClientesDAO->RetornarDiaMesAnoTodos($dia, $mes, $ano);
        } else {
            return null;
        }
    }

    public function RetornarMes(int $mes, int $ano) {

        if ($mes != 0) {
            return $this->movimentoClientesDAO->RetornarMes($mes, $ano);
        } else {
            return null;
        }
    }

    public function RetornarMesAP(int $ativopassivo, int $dia, int $mes, int $ano) {

        if ($mes != 0) {
            return $this->movimentoClientesDAO->RetornarMesAP($ativopassivo, $dia, $mes, $ano);
        } else {
            return null;
        }
    }

    public function RetornarAno(int $ano) {

        if ($ano != 0) {
            return $this->movimentoClientesDAO->RetornarAno($ano);
        } else {
            return null;
        }
    }

    public function RetornarPorCategoria(int $cod, int $mes, int $ano, int $categoria) {

        if (
                $cod > 0) {
            return $this->movimentoClientesDAO->RetornarPorCategoria($cod, $mes, $ano, $categoria);
        } else {
            return null;
        }
    }

    public function RetornarTodos(int $cod, int $ano) {

        if (
                $cod > 0
        ) {
            return $this->movimentoClientesDAO->RetornarTodos($cod, $ano);
        } else {
            return null;
        }
    }

    public function RetornarMovimentoCliDiaMesCat(int $categoria, int $dia, int $mes, int $ano) {
        if ($categoria != 0 && $mes != 0 && $ano != 0) {
            return $this->movimentoClientesDAO->RetornarMovimentoCliDiaMesCat($categoria, $dia, $mes, $ano);
        } else {
            return false;
        }
    }
    public function RetornarMovimentoCliMesCat(int $categoria, int $mes, int $ano) {
        if ($categoria != 0 && $mes != 0 && $ano != 0) {
            return $this->movimentoClientesDAO->RetornarMovimentoCliMesCat($categoria, $mes, $ano);
        } else {
            return false;
        }
    }

    public function Deletar(int $coddeletar) {
        if ($coddeletar != 0) {
            return $this->movimentoClientesDAO->Deletar($coddeletar);
        } else {
            return false;
        }
    }

    public function RetornarPagamentos3(int $id) {
        if (1 == 1) {
            return $this->movimentoClientesDAO->RetornarPagamentos($id);
        } else {
            return false;
        }
    }

    public function RetornarPagamentos4(int $id) {
        if (1 == 1) {
            return $this->movimentoClientesDAO->RetornarPagamentos4($id);
        } else {
            return false;
        }
    }

    public function RetornarPagamentos(int $id) {
        if (1 == 1) {
            return $this->movimentoClientesDAO->RetornarPagamentos($id);
        } else {
            return false;
        }
    }

    public function RetornarPagamentos2(int $mes, int $ano, int $categoria) {
        if (1 == 1) {
            return $this->movimentoClientesDAO->RetornarPagamentos2($mes, $ano, $categoria);
        } else {
            return false;
        }
    }

	public function RetornarAtraso() {
        if (0 == 0) {
            return $this->movimentoClientesDAO->RetornarAtraso();
        } else {
            return null;
        }
    }
}

?>