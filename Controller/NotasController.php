<?php

if (file_exists("../DAL/NotasDAO.php")) {
    require_once ("../DAL/NotasDAO.php");
} else {
    require_once ("DAL/NotasDAO.php");
}

class NotasController
{

    private $notasDAO;

    public function __construct()
    {
        $this->notasDAO = new NotasDAO();
    }

    public function Cadastrar($notas)
    {
        if (
            strlen($notas->getUsuario()) >= 0
        ) {

            return $this->notasDAO->Cadastrar($notas);
        } else {
            return false;
        }
    }
    public function Deletar(int $coddeletar)
    {
        if ($coddeletar != 0) {
            return $this->notasDAO->Deletar($coddeletar);
        } else {
            return false;
        }
    }
    public function RetornarUltimaNotaFunc(int $cod)
    {
        if ($cod != 0) {
            return $this->notasDAO->RetornarUltimaNotaFunc($cod);
        } else {
            return false;
        }
    }
    public function AlterStatusTodos(int $status, int $id)
    {
        if ($id != 0) {
            return $this->notasDAO->AlterStatusTodos($status, $id);
        } else {
            echo "erro: <-Controller!";
        }
    }
    public function RetornarNotas(int $tipo, int $id) {
        if ($tipo > 0 && $id > 0) {
            return $this->notasDAO->RetornarNotas($tipo, $id);
        } else {
            return false;
        }
    }
 
}
