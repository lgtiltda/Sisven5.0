<?php

require_once("Banco.php");

class UsuarioDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    } 

    public function Cadastrar(Usuarios $usuarios) {
        try {

            $sql = "INSERT INTO `usuarios` (nome, usuario, rg, cpf, email, foto, permissao, rua, bairro, numero, celular, senha, comissao) VALUES (:nome, :usuario, :rg, :cpf, :email, :foto, :permissao, :rua, :bairro, :numero, :celular, :senha, :comissao)";
            $param = array(
                ":nome" => $usuarios->getNome(),
                ":usuario" => $usuarios->getUsuario(),
                ":rg" => $usuarios->getRg(),
                ":cpf" => $usuarios->getCpf(),
                ":email" => $usuarios->getEmail(),
                ":foto" => $usuarios->getFoto(),
                ":permissao" => $usuarios->getPermissao(),
                ":rua" => $usuarios->getRua(),
                ":bairro" => $usuarios->getBairro(),
                ":numero" => $usuarios->getNumero(),
                ":celular" => $usuarios->getCelular(),
                ":senha" => $usuarios->getSenha(),
                ":comissao" => $usuarios->getComissao()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarUsuarios(string $termo, int $tipo, int $status) {
        try {
            $sql = "";
            $param = [];
            switch ($tipo) {
                case 1:
                    $sql = "SELECT * FROM usuarios WHERE nome LIKE :termo ORDER BY cod ASC";
                    $param = array(
                        ":termo" => "%{$termo}%"
                    );
                    break;
                case 2:
                    $sql = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod DESC";
                    $param = array(
                        ":cod" => $status
                    );
                    break;
                case 3:
                    $sql = "SELECT * FROM usuarios WHERE nome LIKE :termo AND permissao= :permissao ORDER BY cod DESC";
                    $param = array(
                        ":termo" => "%{$termo}%",
                        ":permissao" => $status
                    );
                    break;
                case 4:
                    $sql = "SELECT * FROM usuarios WHERE permissao= :permissao ORDER BY cod DESC";
                    $param = array(
                        ":permissao" => $status
                    );
                    break; 
                     case 5:
                    $sql = "SELECT * FROM usuarios WHERE permissao= :permissao ORDER BY cod DESC LIMIT 1";
                    $param = array(
                        ":permissao" => $status
                    );
                    break; 
                
            }

            $dataTable = $this->pdo->ExecuteQuery($sql, $param);

            $listaUsuarios = [];

            foreach ($dataTable as $resultado) {
                $usuarios = new Usuarios();

                $usuarios->setCod($resultado["cod"]);
                $usuarios->setNome($resultado["nome"]);
                $usuarios->setUsuario($resultado["usuario"]);
                $usuarios->setRg(($resultado["rg"]));
                $usuarios->setCpf(($resultado["cpf"]));
                $usuarios->setEmail(($resultado["email"]));
                $usuarios->setFoto($resultado["foto"]);
                $usuarios->setPermissao($resultado["permissao"]);
                $usuarios->setRua(($resultado["rua"]));
                $usuarios->setBairro($resultado["bairro"]);
                $usuarios->setNumero(($resultado["numero"]));
                $usuarios->setCelular(($resultado["celular"]));
                $usuarios->setSenha(($resultado["senha"]));
                $usuarios->setComissao(($resultado["comissao"]));

                $listaUsuarios[] = $usuarios;
            }

            return $listaUsuarios;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }
           
    public function Alterar(Usuarios $usuarios) {
        try {
            $sql = "UPDATE usuarios SET nome = :nome, usuario = :usuario, cpf= :cpf, email = :email, rua = :rua, bairro= :bairro, numero= :numero, celular= :celular WHERE cod= :cod";
            $param = array(
                ":cod" => $usuarios->getCod(),
                ":nome" => $usuarios->getNome(),
                ":usuario" => $usuarios->getUsuario(),
                ":cpf" => $usuarios->getCpf(),
                ":email" => $usuarios->getEmail(),
                ":rua" => $usuarios->getRua(),
                ":bairro" => $usuarios->getBairro(),
                ":numero" => $usuarios->getNumero(),
                ":celular" => $usuarios->getCelular()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
            
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Deletar2(int $coddeletar) {
        try {

            $sql = "DELETE FROM `usuarios` WHERE cod = :coddeletar";

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

     public function AutenticarUsuario(string $usu, string $senha, int $permissao) {
        try {
               $sql = "SELECT cod, nome, permissao FROM usuarios WHERE usuario = :usuario AND senha = :senha";
                $param = array(
                    ":usuario" => $usu,
                    ":senha" => $senha
                );

            $dt = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if ($dt != null) {
                $usuarios = new Usuarios();
                $usuarios->setCod($dt["cod"]);
                $usuarios->setNome($dt["nome"]);
                $usuarios->setPermissao($dt["permissao"]);

                return $usuarios;
            } else {
                return null;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

     public function RetornarNomeUsuarios(int $id) {
        try {

            $sql = "SELECT * FROM usuarios WHERE cod = :cod  ORDER BY cod ASC";
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

        
	public function AlterarImagemUsu(string $thumb, int $cod) {
        echo "<h1>deu auqi</h1>";
        echo "<h1>$thumb</h1>";
        echo "<h1>$cod</h1>";
        try {
            $sql = "UPDATE usuarios SET foto = :foto WHERE cod = :cod";
            $param = array(
                ":foto" => $thumb,
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
