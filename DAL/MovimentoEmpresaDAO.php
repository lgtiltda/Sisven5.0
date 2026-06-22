<?php

require_once("Banco.php");

class MovimentoEmpresaDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(MovimentoEmpresa $movimentoempresa) {
        try {
            $sql = "INSERT financeiro_empresa ( tipo, dia, mes, ano, cat, descricao, valor, cod_usu, cod_caixa, status) VALUES ( :tipo, :dia, :mes, :ano, :cat, :descricao, :valor, :codusu, :cod_caixa, :status)";

            $param = array(
                ":tipo" => $movimentoempresa->getTipo(),
                ":dia" => $movimentoempresa->getDia(),
                ":mes" => $movimentoempresa->getMes(),
                ":ano" => $movimentoempresa->getAno(),
                ":cat" => $movimentoempresa->getCat(),
                ":descricao" => $movimentoempresa->getDescricao(),
                ":valor" => $movimentoempresa->getValor(),
                ":codusu" => $movimentoempresa->getCod_usu(),
                ":cod_caixa" => $movimentoempresa->getCod_caixa(),
                ":status" => $movimentoempresa->getStatus()
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

            $sql = "DELETE FROM financeiro_empresa WHERE id = :coddeletar";

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

    public function RetornarUltCad(int $id) {
        try {

            $sql = "SELECT * FROM financeiro_empresa WHERE cod_caixa = $id ORDER BY id DESC LIMIT 1";
            

            $dataTable = $this->pdo->ExecuteQuery($sql);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $cod = $resultado["id"];

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

    public function TesteCategoria(int $id) {
        try {

            $sql = "SELECT * FROM financeiro_empresa WHERE cat = $id ORDER BY id DESC LIMIT 1";
            

            $dataTable = $this->pdo->ExecuteQuery($sql);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $cod = $resultado["id"];

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
}

?>