<?php

if (file_exists("../DAL/ServicosDAO.php")) {
    require_once("../DAL/ServicosDAO.php");
} else {
    require_once("DAL/ServicosDAO.php");
}

class ServicoController {

    private $servicosDAO;

    public function __construct() {
        $this->servicosDAO = new ServicosDAO();
    }

    public function Cadastrar($servicos) {
        if (
                strlen($servicos->getNome()) >= 1) {
            return $this->servicosDAO->Cadastrar($servicos);
        } else {
            return false;
        }
    }
    
     public function Deletar(int $coddeletar) {
        if ($coddeletar!=0) {
            return $this->servicosDAO->Deletar($coddeletar);
        } else {
            return false;
        }
    }
    
    public function RetornarServicos() {
        if (1==1) {
            return $this->servicosDAO->RetornarServicos();
        } else {
            return false;
        }
    }
    
     public function RetornarServicos2(int $id) {
        if ($id!=0) {
            return $this->servicosDAO->RetornarServicos2($id);
        } else {
            return false;
        }
    }
     public function RetornarServicosPesquisa(string $termo, int $tipo, int $cat) {
        if (0==0) {
           
            return $this->servicosDAO->RetornarServicosPesquisa($termo, $tipo, $cat);
        } else {
            return false;
        }
    }
    
    public function RetornarServicosPorCat(int $cat) {
        if ($cat!=0) {
            return $this->servicosDAO->RetornarServicosPorCat($cat);
        } else {
            return false;
        }
    }
    
    public function Alterar($servicos) {
        if (
                strlen($servicos->getNome()) >= 1
        ) {
            return $this->servicosDAO->Alterar($servicos);
        } else {
            return false;
        }
    }
     public function RetornarNomeServico(int $id) {
        if ($id!=0) {
            return $this->servicosDAO->RetornarNomeServico($id);
        } else {
            return false;
        }
    }
     public function RetornarUltCad(int $id) {
        if ($id!=0) {
            return $this->servicosDAO->RetornarUltCad($id);
        } else {
            return false;
        }
    }
     public function RetornarTipo(int $id) {
        if ($id!=0) {
            return $this->servicosDAO->RetornarTipo($id);
        } else {
            return false;
        }
    }
    
    public function AlterarImagem(string $thumb, int $cod) {
        if ($cod != 0 &&  strlen($thumb >= 1)) {   
            return $this->servicosDAO->AlterarImagem($thumb, $cod);
        } else {
            return null;
        }
    }
    
     public function AlterQtdEstoque(int $qtd, int $cod) {
        if ($cod != 0) {

            return $this->servicosDAO->AlterQtdEstoque($qtd, $cod);
        } else {
            return null;
        }
    }
    
    public function RetornarNomeValorProdutos(int $id) {
        if ($id != 0) {

            return $this->servicosDAO->RetornarNomeValorProdutos($id);
        } else {
            return null;
        }
    }
}
