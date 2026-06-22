<?php

require_once("Banco.php");

class PagamentoDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Pagamento $pagamento) {
        
        try {

            $sql = "INSERT INTO `financeiro_pac` (cod_orcamento, tipo, subtotal, total, descricao, numparcelas, tipopag, dia, mes, ano, gorjeta) VALUES (:cod_orcamento, :tipo, :subtotal, :total, :descricao, :numparcelas, :tipopag, :dia, :mes, :ano, :gorjeta)";
            $param = array(
                ":cod_orcamento" => $pagamento->getCod_orcamento(),
                ":tipo" => $pagamento->getTipo(),
                ":subtotal" => $pagamento->getSubtotal(),
                ":total" => $pagamento->getTotal(),
                ":descricao" => $pagamento->getDescricao(),
                ":tipopag" => $pagamento->getTipopag(),
                ":numparcelas" => $pagamento->getNumparcelas(),
                ":dia" => $pagamento->getDia(),
                ":mes" => $pagamento->getMes(),
                ":ano" => $pagamento->getAno(),
                ":gorjeta" => $pagamento->getGorjeta()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarPagamentos(int $id) {
        try {

            $sql = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
            $param = array(
                ":cod" => $id
            );

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaMovimentosClientes = [];

            foreach ($dataTable as $resultado) {
                $movimentoclientes = new MovimentoClientes();

                $movimentoclientes->setCod($resultado["cod"]);
                $movimentoclientes->setCod_orcamento($resultado["cod_orcamento"]);
                $movimentoclientes->setTipo($resultado["tipo"]);
                $movimentoclientes->setSubtotal($resultado["subtotal"]);
                $movimentoclientes->setTotal($resultado["total"]);
                $movimentoclientes->setDescricao($resultado["descricao"]);
                $movimentoclientes->setNumparcelas($resultado["numparcelas"]);
                $movimentoclientes->setTipopag($resultado["tipopag"]);
                $movimentoclientes->setDia($resultado["dia"]);
                $movimentoclientes->setMes($resultado["mes"]);
                $movimentoclientes->setAno($resultado["ano"]);
                $movimentoclientes->setGorjeta($dr["gorjeta"]);


                $listaMovimentosClientes[] = $movimentoclientes;
            }

            return $listaMovimentosClientes;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarPagamentos2(int $mes, int $ano, int $categoria) {
        try {

            $sql = "SELECT * FROM financeiro_pac WHERE mes = :mes, ano = :ano, categoria = :categoria ORDER BY cod ASC";
            $param = array(
                ":mes" => $mes,
                ":ano" => $ano,
                ":categoria" => $categoria
            );


            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaMovimentosClientes = [];

            foreach ($dataTable as $resultado) {
                $movimentoclientes = new MovimentoClientes();

                $movimentoclientes->setCod($resultado["cod"]);
                $movimentoclientes->setCod_orcamento($resultado["cod_orcamento"]);
                $movimentoclientes->setTipo($resultado["tipo"]);
                $movimentoclientes->setValor($resultado["valor"]);
                $movimentoclientes->setDescricao($resultado["descricao"]);
                $movimentoclientes->setNumparcelas($resultado["numparcelas"]);
                $movimentoclientes->setCategoria($resultado["categoria"]);
                $movimentoclientes->setDia($resultado["dia"]);
                $movimentoclientes->setMes($resultado["mes"]);
                $movimentoclientes->setAno($resultado["ano"]);
                $movimentoclientes->setGorjeta($dr["gorjeta"]);


                $listaMovimentosClientes[] = $movimentoclientes;
            }

            return $listaMovimentosClientes;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}
