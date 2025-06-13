<?php

require_once("Banco.php");

class ServicosDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Servicos $servicos) {
        try {

            $sql = "INSERT INTO `servicos` (nome,descricao, categoria, valor, img, codbarra, fornecedor, est_max, est_mim, tipo) VALUES (:nome, :descricao, :categoria, :valor, :img, :codbarra, :fornecedor, :est_max, :est_mim, :tipo)";
            $param = array(
                ":nome" => $servicos->getNome(),
                ":descricao" => $servicos->getDescricao(),
                ":categoria" => $servicos->getCategoria(),
                ":valor" => $servicos->getValor(),
                ":img" => $servicos->getImg(),
                ":codbarra" => $servicos->getCodbarra(),
                ":est_max" => $servicos->getEst_max(),
                ":est_mim" => $servicos->getEst_mim(),
                ":fornecedor" => $servicos->getFornecedor(),
                ":tipo" => $servicos->getTipo(),
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Deletar(int $coddeletar) {
        try {

            $sql = "DELETE FROM `servicos` WHERE cod = :coddeletar";

            $param = array(
                ":coddeletar" => $coddeletar
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarServicos() {
        try {

            $sql = "SELECT * FROM servicos ORDER BY cod ASC";


            $dataTable = $this->pdo->ExecuteQuery($sql);

            $listaServicos = [];

            foreach ($dataTable as $resultado) {
                $servicos = new Servicos();

                $servicos->setCod($resultado["cod"]);
                $servicos->setNome($resultado["nome"]);
                $servicos->setDescricao($resultado["descricao"]);
                $servicos->setCategoria($resultado["categoria"]);
                $servicos->setValor($resultado["valor"]);
                $servicos->setImg($resultado["img"]);
                $servicos->setCodbarra($resultado["codbarra"]);
                $listaServicos[] = $servicos;
            }

            return $listaServicos;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarServicos2(int $id) {
        try {

            $sql = "SELECT * FROM servicos WHERE cod = :cod  ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaServicos = [];

            foreach ($dataTable as $resultado) {
                $servicos = new Servicos();

                $servicos->setCod($resultado["cod"]);
                $servicos->setNome($resultado["nome"]);
                $servicos->setDescricao($resultado["descricao"]);
                $servicos->setCategoria($resultado["categoria"]);
                $servicos->setValor($resultado["valor"]);
                $servicos->setImg($resultado["img"]);
                $servicos->setCodbarra($resultado["codbarra"]);


                $listaServicos[] = $servicos;
            }

            return $listaServicos;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarServicosPorCat(int $cat) {
        try {

            $sql = "SELECT * FROM servicos WHERE categoria = :cat  ORDER BY cod ASC";
            $param = array(
                ":cat" => $cat
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaServicos = [];

            foreach ($dataTable as $resultado) {
                $servicos = new Servicos();

                $servicos->setCod($resultado["cod"]);
                $servicos->setNome($resultado["nome"]);
                $servicos->setDescricao($resultado["descricao"]);
                $servicos->setCategoria($resultado["categoria"]);
                $servicos->setValor($resultado["valor"]);
                $servicos->setImg($resultado["img"]);
                $servicos->setCodbarra($resultado["codbarra"]);


                $listaServicos[] = $servicos;
            }

            return $listaServicos;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarServicosPesquisa(string $termo, int $tipo, int $cat) {
        try {
            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM servicos WHERE nome LIKE :termo AND categoria = :cat ORDER BY cod ASC";
                    $param = array(
                        ":termo" => "%{$termo}%",
                        ":cat" => $cat
                    );
                    break;
                case 2:
                    $sql = "SELECT * FROM servicos  ORDER BY cod ASC";
                    $param = array(
                    );
                    break;
                case 3:
                    $sql = "SELECT * FROM clientes WHERE nome LIKE :termo AND status= :status ORDER BY id ASC";
                    $param = array(
                        ":termo" => "%{$termo}%",
                        ":status" => $status
                    );
                    break;
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaServicos = [];

            foreach ($dataTable as $resultado) {
                $servicos = new Servicos();

                $servicos->setCod($resultado["cod"]);
                $servicos->setNome($resultado["nome"]);
                $servicos->setDescricao($resultado["descricao"]);
                $servicos->setCategoria($resultado["categoria"]);
                $servicos->setValor($resultado["valor"]);
                $servicos->setImg($resultado["img"]);
                $servicos->setCodbarra($resultado["codbarra"]);


                $listaServicos[] = $servicos;
            }

            return $listaServicos;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function Alterar(Servicos $servicos) {
        try {
            $sql = "UPDATE servicos SET nome = :nome, descricao = :descricao, categoria = :categoria, valor=:valor, codbarra=:codbarra WHERE cod= :cod";
            $param = array(
                ":cod" => $servicos->getCod(),
                ":nome" => $servicos->getNome(),
                ":descricao" => $servicos->getDescricao(),
                ":categoria" => $servicos->getCategoria(),
                ":valor" => $servicos->getValor(),
                ":codbarra" => $servicos->getCodbarra()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarNomeServico(int $id) {
        try {

            $sql = "SELECT * FROM servicos WHERE cod = :cod  ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $nome = $resultado["nome"];

                $nomefulano = $nome;
            }

            return $nomefulano;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarUltCad(int $id) {
        try {

            $sql = "SELECT * FROM servicos ORDER BY cod DESC LIMIT 1";
            

            $dataTable = $this->pdo->ExecuteQuery($sql);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $cod = $resultado["cod"];

                $nomefulano = $cod;
            }

            return $nomefulano;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

            public function RetornarTipo(int $id) {
        try {

            $sql = "SELECT * FROM servicos WHERE cod = :cod  ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $nome = $resultado["tipo"];

                $nomefulano = $nome;
            }

            return $nomefulano;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    
	public function AlterarImagem(string $thumb, int $cod) {
        try {
            $sql = "UPDATE servicos SET img = :img WHERE cod = :cod";
            $param = array(
                ":img" => $thumb,
                ":cod" => $cod
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

public function AlterQtdEstoque(int $qtd, int $cod) {
        $sql = "UPDATE servicos SET qtd = :qtd WHERE cod = :cod AND tipo = 1";
        $param = array(
            ":qtd" => $qtd,
            ":cod" => $cod
        );
        return $this->pdo->ExecuteNonQuery($sql, $param);
    }
    public function RetornarNomeValorProdutos(int $id) {
        try {

            $sql = "SELECT * FROM servicos WHERE cod = :cod AND tipo = 1  ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
               $qtd = $resultado["qtd"];

                $nomefulano = $qtd;
            }

            return $nomefulano;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}
