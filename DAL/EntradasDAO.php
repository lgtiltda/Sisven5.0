<?php

require_once("Banco.php");

class EntradasDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Entradas $entradas) {
        try {

            $sql = "INSERT INTO `entradas` (n_notafiscal, ata_pregao, cod_solicitacao, fornecedor, cod_produto, cod_funcionario, dia, mes, ano, cod_orgao, status, conferidor1, conferidor2, conferidor3) VALUES (:n_notafiscal, :ata_pregao, :cod_solicitacao, :fornecedor, :cod_produto, :cod_funcionario, :dia, :mes, :ano, :cod_orgao, :status, :conferidor1, :conferidor2, :conferidor3)";
            $param = array(
                ":n_notafiscal" => $entradas->getN_notafiscal(),
                ":ata_pregao" => $entradas->getAta_pregao(),
                 ":cod_solicitacao" => $entradas->getCod_solicitacao(),
                ":fornecedor" => $entradas->getFornecedor(),
                ":cod_produto" => $entradas->getCod_produto(),
                ":cod_funcionario" => $entradas->getCod_funcionario(),
                ":dia" => $entradas->getDia(),
                ":mes" => $entradas->getMes(),
                ":ano" => $entradas->getAno(),
                ":cod_orgao" => $entradas->getCod_orgao(),
                ":status" => 1,
                ":conferidor1" => $entradas->getConferidor1(),
                ":conferidor2" => $entradas->getConferidor2(),
                ":conferidor3" => $entradas->getConferidor3()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarEntradas(string $termo, int $tipo, int $status) {
        try {
            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM entradas WHERE n_notafiscal LIKE :termo ORDER BY cod DESC";
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                    break;
                case 2:
                    $sql = "SELECT * FROM entradas WHERE cod = :cod ORDER BY cod DESC";
                    $param = array(
                        ":cod" => $status
                    );
                    break;
                case 3:
                    $sql = "SELECT * FROM entradas WHERE status = :status ORDER BY cod DESC";
                    $param = array(
                        ":status" => $status
                    );
                    break;
                case 4:
                    $sql = "SELECT * FROM entradas WHERE status = :status ORDER BY cod DESC LIMIT 1";
                    $param = array(
                        ":status" => $status
                    );
                    break;
		case 5:
                    $sql = "SELECT * FROM entradas WHERE cod_funcionario = :status AND status = 1 ORDER BY cod DESC";
                    $param = array(
                        ":status" => $status
                    );
                    break;
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaEntradas = [];

            foreach ($dataTable as $resultado) {
                $entradas = new Entradas();

                $entradas->setCod($resultado["cod"]);
                $entradas->setN_notafiscal($resultado["n_notafiscal"]);
                $entradas->setAta_pregao($resultado["ata_pregao"]);
                $entradas->setFornecedor($resultado["fornecedor"]);
                $entradas->setCod_produto($resultado["cod_produto"]);
                $entradas->setCod_funcionario($resultado["cod_funcionario"]);
                $entradas->setDia($resultado["dia"]);
                $entradas->setMes($resultado["mes"]);
                $entradas->setAno($resultado["ano"]);
                $entradas->setCod_orgao($resultado["cod_orgao"]);
                $entradas->setImg($resultado["img"]);
                $entradas->setStatus($resultado["status"]);
                $entradas->setConferidor1($resultado["conferidor1"]);
                $entradas->setConferidor2($resultado["conferidor2"]);
                $entradas->setConferidor3($resultado["conferidor3"]);
                $listaEntradas[] = $entradas;
            }

            return $listaEntradas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarEntradasMes(string $mes, string $ano, int $cod_orgao, int $tipo) {
        try {
            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM entradas WHERE mes =:mes AND ano = :ano AND cod_orgao = :cod_orgao ORDER BY cod DESC";
                    $param = array(
                        ":mes" => $mes,
                        ":ano" => $ano,
                        ":cod_orgao" => $cod_orgao
                    );
                    break;
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaEntradas = [];

            foreach ($dataTable as $resultado) {
                $entradas = new Entradas();

                $entradas->setCod($resultado["cod"]);
                $entradas->setN_notafiscal($resultado["n_notafiscal"]);
                $entradas->setAta_pregao($resultado["ata_pregao"]);
                $entradas->setFornecedor($resultado["fornecedor"]);
                $entradas->setCod_produto($resultado["cod_produto"]);
                $entradas->setCod_funcionario($resultado["cod_funcionario"]);
                $entradas->setDia($resultado["dia"]);
                $entradas->setMes($resultado["mes"]);
                $entradas->setAno($resultado["ano"]);
                $entradas->setCod_orgao($resultado["cod_orgao"]);
                $entradas->setImg($resultado["img"]);
                $entradas->setStatus($resultado["status"]);
                $entradas->setConferidor1($resultado["conferidor1"]);
                $entradas->setConferidor2($resultado["conferidor2"]);
                $entradas->setConferidor3($resultado["conferidor3"]);
                $listaEntradas[] = $entradas;
            }

            return $listaEntradas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarEntradasDiaMesAno(string $dia, string $mes, string $ano, int $cod_orgao, int $tipo) {
        try {
            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM entradas WHERE dia= :dia AND mes =:mes AND ano = :ano AND cod_orgao = :cod_orgao ORDER BY cod DESC";
                    $param = array(
                        ":dia" => $dia,
                        ":mes" => $mes,
                        ":ano" => $ano,
                        ":cod_orgao" => $cod_orgao
                    );
                    break;
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaEntradas = [];

            foreach ($dataTable as $resultado) {
                $entradas = new Entradas();

                $entradas->setCod($resultado["cod"]);
                $entradas->setN_notafiscal($resultado["n_notafiscal"]);
                $entradas->setAta_pregao($resultado["ata_pregao"]);
                $entradas->setFornecedor($resultado["fornecedor"]);
                $entradas->setCod_produto($resultado["cod_produto"]);
                $entradas->setCod_funcionario($resultado["cod_funcionario"]);
                $entradas->setDia($resultado["dia"]);
                $entradas->setMes($resultado["mes"]);
                $entradas->setAno($resultado["ano"]);
                $entradas->setCod_orgao($resultado["cod_orgao"]);
                $entradas->setImg($resultado["img"]);
                $entradas->setStatus($resultado["status"]);
                $entradas->setConferidor1($resultado["conferidor1"]);
                $entradas->setConferidor2($resultado["conferidor2"]);
                $entradas->setConferidor3($resultado["conferidor3"]);
                $listaEntradas[] = $entradas;
            }

            return $listaEntradas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function Alterar(Entradas $entradas) {
        try {
            $sql = "UPDATE entradas SET n_notafiscal = :n_notafiscal WHERE cod= :cod";
            $param = array(
                ":cod" => $entradas->getCod(),
                ":n_notafiscal" => $entradas->getN_notafiscal()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarUltimoEntradas(int $cod_orgao) {
        try {
            $sql = "SELECT * FROM entradas WHERE cod_orgao = :cod_orgao ORDER BY cod DESC LIMIT 1";
            $param = array(
                ":cod_orgao" => $cod_orgao
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

    public function RetornarNomeEntradas(int $id) {
        try {

            $sql = "SELECT * FROM entradas WHERE cod = :cod  ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);
            $nomefulano = "";

            foreach ($dataTable as $resultado) {
                $nome = $resultado["n_notafiscal"];

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

    public function AlterStatus(int $status, int $cod) {
        $sql = "UPDATE entradas SET status = :status WHERE cod = :cod";
        $param = array(
            ":status" => $status,
            ":cod" => $cod
        );
        return $this->pdo->ExecuteNonQuery($sql, $param);
    }

    public function Deletar(int $coddeletar) {
        try {

            $sql = "DELETE FROM `entradas` WHERE cod = :coddeletar";

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

    public function AlterarImagem(string $thumb, int $cod) {
        try {
            $sql = "UPDATE entradas SET img = :thumb WHERE cod = :cod";
            $param = array(
                ":thumb" => $thumb,
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

}
