<?php

if (file_exists("../DAL/PedidosDAO.php")) {
    require_once("../DAL/PedidosDAO.php");
} else {
    require_once("DAL/PedidosDAO.php");
}

class PedidosController {

    private $pedidosDAO;

    public function __construct() {
        $this->pedidosDAO = new PedidosDAO();
    }

    public function Cadastrar($pedidos) {
        if (
                strlen($pedidos->getValor()) >= 1) {

            return $this->pedidosDAO->Cadastrar($pedidos);
        } else {
            return false;
        }
    }

    public function RetornarPedidos(string $termo, int $tipo, int $status) {
        if (strlen($termo) >= 0 && $tipo > 0 && $status >= 0) {
            return $this->pedidosDAO->RetornarPedidos($termo, $tipo, $status);
        } else {
            return false;
        }
    }

    
    public function RetornarPedidosMesDiaCat(int $categoria, int $dia, int $mes, int $ano) {
        if ($mes != 0 && $ano != 0) {
            return $this->pedidosDAO->RetornarPedidosMesDiaCat($categoria, $dia, $mes, $ano);
        } else {
            return false;
        }
    }
    
    public function RetornarPedidosMesCat(int $categoria, int $mes, int $ano) {
        if (     $mes != 0 && $ano != 0) {
            return $this->pedidosDAO->RetornarPedidosMesCat($categoria, $mes, $ano);
        } else {
            return false;
        }
    }

    public function Alterar($pedidos) {
        if (
                strlen($pedidos->getNome()) >= 1
        ) {
            return $this->pedidosDAO->Alterar($pedidos);
        } else {
            return false;
        }
    }

    public function Deletar2(int $coddeletar) {
        if ($coddeletar != 0) {
            return $this->pedidosDAO->Deletar2($coddeletar);
        } else {
            return false;
        }
    }

    public function DeletarO(int $coddeletar) {
        if ($coddeletar != 0) {
            return $this->pedidosDAO->DeletarO($coddeletar);
        } else {
            return false;
        }
    }

    public function AlterStatusTodos2(int $status, int $id) {
        if ($id != 0) {
            return $this->pedidosDAO->AlterStatusTodos2($status, $id);
        } else {
            
        }
    }
    
    public function AlterStatusCancelar(int $status, int $id) {
        if ($id != 0) {
            return $this->pedidosDAO->AlterStatusCancelar($status, $id);
        } else {
            
        }
    }
    
    public function AlterQtdValor(int $qtd, string $valor, string $obs, int $id) {
        if ($id != 0) {
            return $this->pedidosDAO->AlterQtdValor($qtd, $valor, $obs,  $id);
        } else {
            
        }
    }
    

}
