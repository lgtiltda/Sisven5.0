<?php

if (file_exists("../DAL/LojasDAO.php")) {
    require_once("../DAL/LojasDAO.php");
} else if (file_exists("DAL/LojasDAO.php")) {
    require_once("DAL/LojasDAO.php");
} else {
    require_once("../../DAL/LojasDAO.php");
}

class LojasController {

    private $lojasDAO;

    public function __construct() {
        $this->lojasDAO = new LojasDAO();
    }

    public function Cadastrar(Lojas $lojas) {
        if (strlen($lojas->getNome()) >= 3 && $lojas->getTipo() > 0) {
            return $this->lojasDAO->Cadastrar($lojas);
        } else {
            return false;
        }
    }

    public function RetornarLojas(string $termo, int $tipo, int $status) {
        if (strlen($termo) >= 0 && $tipo > 0 && $status >= 0) {
            return $this->lojasDAO->RetornarLojas($termo, $tipo, $status);
        } else {
            return false;
        }
    }
}
?>
