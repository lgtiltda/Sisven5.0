<?php

require_once("Banco.php");

class ClientesDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Clientes $clientes) {
        try {
            $sql = "INSERT INTO `clientes` (nome, nascimento, rg, cpf, endereco, bairro, numero, complemento, celular, residencial, email, indicacao, data, status, dr) VALUES (:nome, :nascimento, :rg, :cpf, :endereco, :bairro, :numero, :complemento, :celular, :residencial, :email, :indicacao, :data, :status, :dr)";
            $param = array(
                ":nome" => $clientes->getNome(),
                ":nascimento" => $clientes->getNascimento(),
                ":rg" => $clientes->getRg(),
                ":cpf" => $clientes->getCpf(),
                ":endereco" => $clientes->getEndereco(),
                ":bairro" => $clientes->getBairro(),
                ":numero" => $clientes->getNumero(),
                ":complemento" => $clientes->getComplemento(),
                ":celular" => $clientes->getCelular(),
                ":residencial" => $clientes->getResidencial(),
                ":email" => $clientes->getEmail(),
                ":indicacao" => $clientes->getIndicacao(),
                ":data" => $clientes->getData(),
                ":status" => 1,
                ":dr" => $clientes->getDr()
            );
            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarClientes(string $termo, int $tipo, int $status) {
        try {
            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM clientes WHERE nome LIKE :termo ORDER BY id DESC";
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                    break;
                case 2:
                    $sql = "SELECT * FROM clientes WHERE id = :id ORDER BY data DESC";
                    $param = array(
                        ":id" => $status
                    );
                    break;
                case 3:
                    $sql = "SELECT * FROM clientes WHERE nome LIKE :termo ORDER BY id DESC";
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                    break;
                case 4:
                    $sql = "SELECT * FROM clientes WHERE nome LIKE :termo ORDER BY id DESC LIMIT 5";
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                    break;
                case 5:
                    $sql = "SELECT * FROM clientes WHERE nascimento LIKE :termo ORDER BY id DESC";
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                    break;
            }



            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaClientes = [];

            foreach ($dataTable as $resultado) {
                $clientes = new Clientes();

                $clientes->setId($resultado["id"]);
                $clientes->setNome($resultado["nome"]);
                $clientes->setNascimento($resultado["nascimento"]);
                $clientes->setRg($resultado["rg"]);
                $clientes->setCpf($resultado["cpf"]);
                $clientes->setEndereco($resultado["endereco"]);
                $clientes->setBairro($resultado["bairro"]);
                $clientes->setNumero($resultado["numero"]);
                $clientes->setComplemento($resultado["complemento"]);
                $clientes->setCelular($resultado["celular"]);
                $clientes->setResidencial($resultado["residencial"]);
                $clientes->setEmail($resultado["email"]);
                $clientes->setIndicacao($resultado["indicacao"]);
                $clientes->setData($resultado["data"]);
                $clientes->setStatus($resultado["status"]);
                $clientes->setDr($resultado["dr"]);

                $listaClientes[] = $clientes;
            }

            return $listaClientes;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function VerificaCPFExiste(string $cpf) {
        try {
            $sql = "SELECT cpf FROM clientes WHERE cpf = :cpf";

            $param = array(
                ":cpf" => $cpf
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if (!empty($dr)) {
                return 1;
            } else {
                return -1;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function Alterar(Clientes $clientes) {

        try {
            echo "deu aqui";
                $sql = "UPDATE clientes SET nome = :nome, nascimento = :nascimento, cpf= :cpf, endereco = :endereco, bairro =:bairro, numero= :numero, complemento= :complemento, celular= :celular WHERE id= :id";
            $param = array(
                ":id" => $clientes->getId(),
                ":nome" => $clientes->getNome(),
                ":nascimento" => $clientes->getNascimento(),
                ":cpf" => $clientes->getCpf(),
                ":endereco" => $clientes->getEndereco(),
                ":bairro" => $clientes->getBairro(),
                ":numero" => $clientes->getNumero(),
                ":complemento" => $clientes->getComplemento(),
                ":celular" => $clientes->getCelular()   
            );

            
            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarUltimoClientes() {
        try {
            $sql = "SELECT * FROM clientes WHERE status = 1 ORDER BY id DESC LIMIT 1";

            $dataTable = $this->pdo->ExecuteQuery($sql);

            $cod = 0;

            foreach ($dataTable as $resultado) {
                $clientes = new Clientes();

                $cod_cli = $resultado["id"];

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

    public function RetornarNomeClientes(int $id) {
        try {

            $sql = "SELECT * FROM clientes WHERE id = :cod  ORDER BY id ASC";
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

    public function DesativarAtivar(int $status, int $id) {
        $sql = "UPDATE clientes SET status = :status WHERE id = :id";
        $param = array(
            ":status" => $status,
            ":id" => $id
        );
        return $this->pdo->ExecuteNonQuery($sql, $param);
    }

}
