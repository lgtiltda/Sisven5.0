<?php

if (file_exists("../DAL/UsuariosDAO.php")) {
    require_once("../DAL/UsuariosDAO.php");
} else if (file_exists("DAL/UsuariosDAO.php")) {
    require_once("DAL/UsuariosDAO.php");
} else {
    require_once("../../DAL/UsuariosDAO.php");
}

class UsuarioController {

    private $usuariosDAO;

    public function __construct() {
        $this->usuariosDAO = new UsuarioDAO();
    }

    public function Cadastrar($usuarios) {
        if (
                strlen($usuarios->getNome()) >= 5
        ) {
            return $this->usuariosDAO->Cadastrar($usuarios);
        } else {
            return false;
        }
    }

    public function RetornarUsuarios(string $termo, int $tipo, int $status) {
        if (strlen($termo) >= 0 && $tipo > 0 && $status >= 0) {
            return $this->usuariosDAO->RetornarUsuarios($termo, $tipo, $status);
        } else {
            return false;
        }
    }

    public function Alterar($usuarios) {
        if (
                strlen($usuarios->getNome()) >= 4
        ) {
            return $this->usuariosDAO->Alterar($usuarios);
        } else {
            return false;
        }
    }

    public function Deletar2(int $coddeletar) {
        if ($coddeletar != 0) {
            return $this->usuariosDAO->Deletar2($coddeletar);
        } else {
            return false;
        }
    }

    public function AutenticarUsuario(string $usu, string $senha, int $permissao) {
        if (strlen($usu) >= 1 && strlen($senha) >= 1 && $permissao > 0 && $permissao < 4) {
            $senha = md5($senha);
            return $this->usuariosDAO->AutenticarUsuario($usu, $senha, $permissao);
        } else {
            return null;
        }
    }
    
    public function RetornarNomeUsuarios(int $id) {
        if (1 == 1) {
 
            return $this->usuariosDAO->RetornarNomeUsuarios($id);
        } else {
            return null;
        }
    }
   
    public function AlterarImagemUsu(string $thumb, int $cod) {
        if ($cod != 0 &&  strlen($thumb >= 1)) {   
            return $this->usuariosDAO->AlterarImagemUsu($thumb, $cod);
        } else {
            return null;
        }
    }
    


}
