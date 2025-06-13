<?php

require_once("Banco.php");

class NotasDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Notas $notas) {
        try {
            $hora = date('H:i:s');
            $sql = "INSERT INTO `notas` (status,usuario, nomeCli, dia, mes, ano, func, ordem, tipo_pedido, cod_caixa, hora) VALUES (:status, :usuario, :nomeCli, :dia, :mes, :ano, :func, :ordem, :tipo_pedido, :cod_caixa, :hora)";
            $param = array(
                ":status" => $notas->getStatus(),
                ":usuario" => $notas->getUsuario(),
                ":nomeCli" => $notas->getNomeCli(),
                ":dia" => $notas->getDia(),
                ":mes" => $notas->getMes(),
                ":ano" => $notas->getAno(),
                ":ordem" => $notas->getOrdem(),
                ":tipo_pedido" => $notas->getTipo_entrega(),
                ":cod_caixa" => $notas->getCod_caixa(),
                ":func" => $notas->getFunc(),
                ":hora" => $hora
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

            $sql = "DELETE FROM `notas` WHERE cod = :coddeletar";

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

    public function RetornarNotas(int $tipo, int $id) {
        try {

            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM notas WHERE usuario = :cod   ORDER BY cod DESC";
                    $param = array(
                        ":cod" => $id
                    );
                    break;
                case 2:
                    $sql = "SELECT * FROM notas WHERE cod = :cod   ORDER BY cod DESC";
                    $param = array(
                        ":cod" => $id
                    );
                    break;

                case 3:
                    $sql = "SELECT * FROM notas WHERE status=:cod ORDER BY cod ASC  ";
                    $param = array(
                        ":cod" => $id
                    );
                    break;

                case 4:
                    $sql = "SELECT * FROM notas WHERE func=:cod ORDER BY cod ASC  ";
                    $param = array(
                        ":cod" => $id
                    );
                    break;
            }



            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaNotas = [];

            foreach ($dataTable as $resultado) {
                $notas = new Notas();

                $notas->setCod($resultado["cod"]);
                $notas->setStatus($resultado["status"]);
                $notas->setUsuario($resultado["usuario"]);
                $notas->setNomeCli($resultado["nomeCli"]);
                $notas->setDia($resultado["dia"]);
                $notas->setMes($resultado["mes"]);
                $notas->setAno($resultado["ano"]);
                $notas->setFunc($resultado["func"]);
                $notas->setHora($resultado["hora"]);
                $notas->setTipo_entrega($resultado["tipo_pedido"]);



                $listaNotas[] = $notas;
            }

            return $listaNotas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarUltimaNotaFunc($cod) {
        try {
            $sql = "SELECT * FROM notas WHERE status = 1 AND func = $cod ORDER BY COD DESC LIMIT 1";

            $dataTable = $this->pdo->ExecuteQuery($sql);

            $listaNotas = [];

            foreach ($dataTable as $resultado) {
              $cod_nota =   $resultado["cod"];
            }

            return $cod_nota;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function AlterStatusTodos(int $status, int $id) {
        $sql = "UPDATE notas SET status = :nvStatus WHERE cod = :id";
        $param = array(
            ":nvStatus" => $status,
            ":id" => $id
        );
        return $this->pdo->ExecuteNonQuery($sql, $param);
    }

 	
}
