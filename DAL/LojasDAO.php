<?php

require_once("Banco.php");

class LojasDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
        $this->CriarTabelaSeNaoExistir();
    }

    private function CriarTabelaSeNaoExistir() {
        $sql = "CREATE TABLE IF NOT EXISTS lojas (
            cod INT NOT NULL AUTO_INCREMENT,
            nome VARCHAR(150) NOT NULL,
            tipo TINYINT NOT NULL DEFAULT 1,
            cnpj VARCHAR(30) NULL,
            responsavel VARCHAR(120) NULL,
            telefone VARCHAR(30) NULL,
            email VARCHAR(120) NULL,
            cep VARCHAR(20) NULL,
            endereco VARCHAR(180) NULL,
            numero VARCHAR(20) NULL,
            bairro VARCHAR(100) NULL,
            cidade VARCHAR(100) NULL,
            estado VARCHAR(2) NULL,
            observacao TEXT NULL,
            status TINYINT NOT NULL DEFAULT 1,
            data_cadastro DATE NULL,
            PRIMARY KEY (cod)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $this->pdo->ExecuteNonQuery($sql);
    }

    public function Cadastrar(Lojas $lojas) {
        try {
            $sql = "INSERT INTO lojas (nome, tipo, cnpj, responsavel, telefone, email, cep, endereco, numero, bairro, cidade, estado, observacao, status, data_cadastro) VALUES (:nome, :tipo, :cnpj, :responsavel, :telefone, :email, :cep, :endereco, :numero, :bairro, :cidade, :estado, :observacao, :status, :data_cadastro)";
            $param = array(
                ":nome" => $lojas->getNome(),
                ":tipo" => $lojas->getTipo(),
                ":cnpj" => $lojas->getCnpj(),
                ":responsavel" => $lojas->getResponsavel(),
                ":telefone" => $lojas->getTelefone(),
                ":email" => $lojas->getEmail(),
                ":cep" => $lojas->getCep(),
                ":endereco" => $lojas->getEndereco(),
                ":numero" => $lojas->getNumero(),
                ":bairro" => $lojas->getBairro(),
                ":cidade" => $lojas->getCidade(),
                ":estado" => $lojas->getEstado(),
                ":observacao" => $lojas->getObservacao(),
                ":status" => $lojas->getStatus(),
                ":data_cadastro" => $lojas->getData_cadastro()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarLojas(string $termo, int $tipo, int $status) {
        try {
            $sql = "";
            $param = [];

            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM lojas WHERE nome LIKE :termo ORDER BY cod DESC";
                    $param = array(":termo" => "%{$termo}%");
                    break;
                case 2:
                    $sql = "SELECT * FROM lojas WHERE cod = :cod ORDER BY cod DESC";
                    $param = array(":cod" => $status);
                    break;
                case 3:
                    $sql = "SELECT * FROM lojas WHERE status = :status ORDER BY nome ASC";
                    $param = array(":status" => $status);
                    break;
                default:
                    $sql = "SELECT * FROM lojas ORDER BY nome ASC";
                    break;
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);
            $listaLojas = [];

            foreach ($dataTable as $resultado) {
                $lojas = new Lojas();
                $lojas->setCod($resultado["cod"]);
                $lojas->setNome($resultado["nome"]);
                $lojas->setTipo($resultado["tipo"]);
                $lojas->setCnpj($resultado["cnpj"]);
                $lojas->setResponsavel($resultado["responsavel"]);
                $lojas->setTelefone($resultado["telefone"]);
                $lojas->setEmail($resultado["email"]);
                $lojas->setCep($resultado["cep"]);
                $lojas->setEndereco($resultado["endereco"]);
                $lojas->setNumero($resultado["numero"]);
                $lojas->setBairro($resultado["bairro"]);
                $lojas->setCidade($resultado["cidade"]);
                $lojas->setEstado($resultado["estado"]);
                $lojas->setObservacao($resultado["observacao"]);
                $lojas->setStatus($resultado["status"]);
                $lojas->setData_cadastro($resultado["data_cadastro"]);
                $listaLojas[] = $lojas;
            }

            return $listaLojas;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
}
?>
