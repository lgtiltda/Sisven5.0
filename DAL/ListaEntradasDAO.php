<?php

require_once("Banco.php");

class ListaEntradasDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(ListaEntradas $listaentradas) {
        try {

            $sql = "INSERT INTO `lista_entradas` (cod_entrada, cod_produto, lote, mes_validade, ano_validade, qtd, valor_total) VALUES (:cod_entrada, :cod_produto, :lote, :mes_validade, :ano_validade, :qtd, :valor_total)";
            $param = array(
                ":cod_entrada" => $listaentradas->getCod_entrada(),
                ":cod_produto" => $listaentradas->getCod_produto(),
                ":lote" => $listaentradas->getLote(),
                ":mes_validade" => $listaentradas->getMes_validade(),
                ":ano_validade" => $listaentradas->getAno_validade(),
                ":qtd" => $listaentradas->getQtd(),
                ":valor_total" => $listaentradas->getValor_total()
            );
            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarListaEntradas(string $termo, int $tipo, int $status) {
        try {

            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM lista_entradas WHERE cod_entrada = :cod_entrada ORDER BY cod ASC";
                    $param = array(
                        ":cod_entrada" => $status
                    );
                    break;
                case 2:
                    $sql = "SELECT * FROM lista_entradas WHERE cod = :cod ORDER BY cod ASC";
                    $param = array(
                        ":cod" => $status
                    );
                    break;
                case 3:
                    $sql = "SELECT * FROM lista_entradas WHERE cod_produto = :cod_produto ORDER BY cod ASC";
                    $param = array(
                        ":cod_produto" => $status
                    );
                    break;

                case 4:
                    $sql = "SELECT * FROM lista_entradas WHERE cod_produto = :cod_produto ORDER BY cod DESC LIMIT 1";
                    $param = array(
                        ":cod_produto" => $status
                    );
                    break;
                
                case 5:
                    $sql = "SELECT * FROM lista_entradas WHERE ata_pregao = :ata_pregao ORDER BY cod DESC";
                    $param = array(
                        ":ata_pregao" => $status
                    );
                    break;
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $ListaEntradas = [];

            foreach ($dataTable as $resultado) {
                $listaentradas = new ListaEntradas();

                $listaentradas->setCod($resultado["cod"]);
                $listaentradas->setCod_entrada($resultado["cod_entrada"]);
                $listaentradas->setAta_pregao($resultado["ata_pregao"]);
                $listaentradas->setCod_produto($resultado["cod_produto"]);
                $listaentradas->setLote($resultado["lote"]);
                $listaentradas->setMes_validade($resultado["mes_validade"]);
                $listaentradas->setAno_validade($resultado["ano_validade"]);
                $listaentradas->setQtd($resultado["qtd"]);
                $listaentradas->setValor_total($resultado["valor_total"]);
                $ListaEntradas[] = $listaentradas;
            }

            return $ListaEntradas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarProdutosVencidos(string $mes, string $ano, int $cod_produto) {
        try {
             $sql = "SELECT * FROM lista_entradas WHERE mes_validade =:mes AND ano_validade = :ano AND cod_produto = :cod_produto ORDER BY cod DESC";
                    $param = array(
                        ":mes" => $mes,
                        ":ano" => $ano,
                        ":cod_produto" => $cod_produto
                    );
                    
            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $ListaEntradas = [];

            foreach ($dataTable as $resultado) {
                $listaentradas = new ListaEntradas();

                $listaentradas->setCod($resultado["cod"]);
                $listaentradas->setCod_entrada($resultado["cod_entrada"]);
                $listaentradas->setAta_pregao($resultado["ata_pregao"]);
                $listaentradas->setCod_produto($resultado["cod_produto"]);
                $listaentradas->setLote($resultado["lote"]);
                $listaentradas->setMes_validade($resultado["mes_validade"]);
                $listaentradas->setAno_validade($resultado["ano_validade"]);
                $listaentradas->setQtd($resultado["qtd"]);
                $listaentradas->setValor_total($resultado["valor_total"]);
                $ListaEntradas[] = $listaentradas;
            }

            return $ListaEntradas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function Alterar(ListaEntradas $listaentradas) {
        try {
            $sql = "UPDATE lista_entradas SET lote = :lote, mes_validade= :mes_validade, ano_validade= :ano_validade, qtd = :qtd, valor_total = :valor_total WHERE cod= :cod";
            $param = array(
                ":cod" => $listaentradas->getCod(),
                ":lote" => $listaentradas->getLote(),
                ":mes_validade" => $listaentradas->getMes_validade(),
                ":ano_validade" => $listaentradas->getAno_validade(),
                ":qtd" => $listaentradas->getQtd(),
                ":valor_total" => $listaentradas->getValor_total()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarUltimoListaEntradas(int $cod_entrada) {
        try {
            $sql = "SELECT * FROM lista_entradas WHERE cod_entrada = :cod_entrada ORDER BY cod DESC LIMIT 1";
            $param = array(
                ":cod_entrada" => $cod_entrada
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $cod = 0;

            foreach ($dataTable as $resultado) {

                $cod_cli = $resultado["cod"];

                $cod = $cod_cli;
            }

            return $cod;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarNomeListaEntradas(int $id) {
        try {

            $sql = "SELECT * FROM lista_entradas WHERE cod = :cod  ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $nome = $resultado["lote"];

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

    public function Deletar(int $coddeletar) {
        try {

            $sql = "DELETE FROM `lista_entradas` WHERE cod = :coddeletar";

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

    public function DeletarTodos(int $coddeletar) {
        try {

            $sql = "DELETE FROM `lista_entradas` WHERE cod_entrada = :coddeletar";

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

}
