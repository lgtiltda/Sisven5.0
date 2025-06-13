<?php

if (file_exists("../DAL/ClientesDAO.php")) {
    require_once("../DAL/ClientesDAO.php");
} else if (file_exists("DAL/ClientesDAO.php")) {
    require_once("DAL/ClientesDAO.php");
} else {
    require_once("../../DAL/ClientesDAO.php");
}

class ClientesController {

    private $clientesDAO;

    public function __construct() {
        $this->clientesDAO = new ClientesDAO();
    }

    public function Cadastrar($clientes) {
        if 
            (
                strlen($clientes->getNome()) >= 3
        ) {
            
            return $this->clientesDAO->Cadastrar($clientes);
            
        } else {
            return false;
        }
    }

    public function RetornarClientes(string $termo, int $tipo, int $status) {
        if (strlen($termo) >= 0 && $tipo > 0 && $status >= 0) {
            return $this->clientesDAO->RetornarClientes($termo, $tipo, $status);
        } else {
            return false;
        }
    }

    public function RetornarAtivosInativos(int $tipo) {
        if (0 == 0) {
            return $this->clientesDAO->RetornarAtivosInativos($tipo);
        } else {
            return false;
        }
    }

    public function VerificaCPFExiste(string $cpf) {
        if (strlen($cpf) == 14) {
            return $this->clientesDAO->VerificaCPFExiste($cpf);
        } else {
            -10;
        }
    }

    public function Alterar($clientes) {
        if (
                1==1
        ) {
            return $this->clientesDAO->Alterar($clientes);
        } else {
            return false;
        }
    }

    public function RetornarUltimoClientes() {
        if (1 == 1) {
            return $this->clientesDAO->RetornarUltimoClientes();
        } else {
            return false;
        }
    }

    public function RetornarNomeClientes(int $id) {
        if (1 == 1) {
 
            return $this->clientesDAO->RetornarNomeClientes($id);
        } else {
            return null;
        }
    }
    
     public function AlterIndicacao(int $indicacao, int $id) {
        if (1 == 1) {
            return $this->clientesDAO->AlterIndicacao($indicacao, $id);
        } else {
            return null;
        }
    }
    
     public function DesativarAtivar(int $status, int $id) {
        if ($id != 0) {
            return $this->clientesDAO->DesativarAtivar($status, $id);
        } else {
            return null;
        }
    }
    
}
