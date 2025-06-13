    <?php

require_once("Banco.php");

class MovimentoClientesDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(MovimentoClientes $movimentoclientes) {

        try {
            $sql = "INSERT financeiro_clientes (cod_orcamento, tipo, subtotal, total, descricao, numparcelas, tipopag, categoria, dia, mes, ano, gorjeta, dinheiro, debito, credito, pix, desconto) VALUES (:cod_orcamento, :tipo, :subtotal, :total, :descricao, :numparcelas, :tipopag, :categoria, :dia, :mes, :ano, :gorjeta, :dinheiro, :debito, :credito, :pix, :desconto)";

            $param = array(
                ":cod_orcamento" => $movimentoclientes->getCod_orcamento(),
                ":tipo" => $movimentoclientes->getTipo(),
                ":subtotal" => $movimentoclientes->getSubtotal(),
                ":total" => $movimentoclientes->getTotal(),
                ":descricao" => $movimentoclientes->getDescricao(),
                ":numparcelas" => $movimentoclientes->getNumparcelas(),
                ":tipopag" => $movimentoclientes->getTipopag(),
                ":categoria" => $movimentoclientes->getCategoria(),
                ":dia" => $movimentoclientes->getDia(),
                ":mes" => $movimentoclientes->getMes(),
                ":ano" => $movimentoclientes->getAno(),
                ":gorjeta" => $movimentoclientes->getBonus(),
                ":dinheiro" => $movimentoclientes->getDinheiro(),
                ":debito" => $movimentoclientes->getCartaodebito(),
                ":credito" => $movimentoclientes->getCartaocredito(),
                ":pix" => $movimentoclientes->getPix(),
                ":desconto" => $movimentoclientes->getDesconto()

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
                $movimentoclientes->setGorjeta($resultado["gorjeta"]);


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

    
	public function RetornarAtraso() {
        
        try {
            
            $sql = "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 ORDER BY cod ASC";
            
            $dt = $this->pdo->ExecuteQuery($sql);
            $listaPontos = [];

            foreach ($dt as $dr) {
                $movclientes = new MovimentoClientes();

                $movclientes->setCod($dr["cod"]);
                $movclientes->setCod_orcamento($dr["cod_orcamento"]);
                $movclientes->setTipo($dr["tipo"]);
                $movclientes->setTotal($dr["total"]);
                $movclientes->setNumparcelas($dr["numparcelas"]);
                $movclientes->setTipopag($dr["tipopag"]);
                $movclientes->setDia($dr["dia"]);
                $movclientes->setMes($dr["mes"]);
                $movclientes->setAno($dr["ano"]);
				$movclientes->setGorjeta($dr["gorjeta"]);

                $listaPontos[] = $movclientes;
            }

            return $listaPontos;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }
    public function Deletar(int $coddeletar) {
        try {

            $sql = "DELETE FROM financeiro_clientes WHERE cod_orcamento = :coddeletar";

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

?>