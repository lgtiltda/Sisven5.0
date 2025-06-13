<?php
require_once("Controller/ServicoController.php");
require_once("Model/Servicos.php");
require_once ("Controller/CategoriaSerFinController.php");
require_once ("Model/CategoriaSerFin.php");
require_once ("Controller/FornecedoresController.php");
require_once ("Model/Fornecedores.php");
require_once ("Controller/EntradasController.php");
require_once ("Model/Entradas.php");
require_once ("Controller/PedidosController.php");
require_once ("Model/Pedidos.php");
require_once ("Controller/ListaEntradasController.php");
require_once ("Model/ListaEntradas.php");
require_once ("Controller/ListaSaidasController.php");
require_once ("Model/ListaSaidas.php");
require_once ("Controller/UsuariosController.php");
require_once ("Model/Usuarios.php");


require_once ("Impressoes/conn.php");

require_once("Impressoes/functions.php");

$banco = new Banco();

function retiraAcentos($string) {
    // pegando a extensao do arquivo
    $partes = explode(".", $string);
    $extensao = $partes[count($partes) - 1];
    // somente o nome do arquivo
    $nome = preg_replace('/\.[^.]*$/', '', $string);
    // removendo simbolos, acentos etc
    $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
    $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyybyRr-';
    $nome = strtr($nome, utf8_decode($a), $b);
    $nome = str_replace(".", "-", $nome);
    $nome = preg_replace("/[^0-9a-zA-Z\.]+/", ' ', $nome);
    return utf8_decode(strtoupper($nome));
}

    set_time_limit(500);

$produtosController = new ServicoController();
$categoriasController = new CategoriaSerFinController();
$fornecedoresController = new FornecedoresController();
$entradasController = new EntradasController();
$listaentradasControler = new ListaEntradasController();
$usuarioController = new UsuarioController();
$saidasController = new PedidosController();
$listasaidasControler = new ListaSaidasController(); 
$apresentacao = 0;
$descricao = "";
$qtd = 0;
$valor = "";
$est_mim = 0;
$est_max = 0;
$tipo = 0;
$status = 0;
$categoria = 0;
$fornecedor = 0;
$img = "";
$cod_orgaoFg = (int) $_SESSION['cod_orgaoF'];
$descricaofor = "";
$cnpjfor = "";
$enderecofor = "";
$telefonefor = "";
$n_notafiscal = "";
$cod_produto = 0;
$cod_funcionario = 0;
$nomecategoria = "";
$resultadomedia = 0;

$cod_entrada = 0;
$lote = "";
$mes_validade = "";
$ano_validade = "";
$qtd = 1;
$validade = "";
$valor_total = "";

$ListaCategorias = [];

$nomefulano = "";

$resultado = "";
$erros = [];

$cod_orgaoF = $_SESSION['cod_orgaoF'];
$ListaCategorias = $categoriasController->RetornarCategorias($cod_orgaoF);

$termo = "";
$tipo = 2;
$status = 1;
$listaentradas2 = $entradasController->RetornarEntradas($termo, $tipo, $status);
//var_dump($listaentradas);


$termo = "";
$tipo = 1;
$status2 = 1;
$listaentradas3 = $listaentradasControler->RetornarListaEntradas($termo, $tipo, $status2);
//var_dump($listaentradas3);
//var_dump($listaentradas3);

$codfornecedor = 0;
if ($listaentradas2 != NULL) {
    foreach ($listaentradas2 as $entradas2) {
        $cod_entrada = $entradas2->getCod();
        $n_notafiscal = $entradas2->getN_notafiscal();
        $ata_pregao = $entradas2->getAta_pregao();
        $conferidor1 = $entradas2->getConferidor1();
        $conferidor2 = $entradas2->getConferidor2();
        $conferidor3 = $entradas2->getConferidor3();
        $cod_produto = $entradas2->getCod_produto();
        $cod_funcionario = $entradas2->getCod_funcionario();
        $dia = $entradas2->getDia();
        $mes = $entradas2->getMes();
        $ano = $entradas2->getAno();
        $cod_orgao = $entradas2->getCod_orgao();
        $codfornecedor = $entradas2->getFornecedor();
        $img = $entradas2->getImg();
        $status = $entradas2->getStatus();
    }
}
$termo = "";
$tipo = 2;
$status2 = (int) $codfornecedor;
$listafornecedor = $fornecedoresController->RetornarFornecedores($termo, $tipo, $status2);

$nomefornecedor = "";
$cnpjfornecedor = "";
$enderecofornecedor = "";
$fonefornecedor = "";
if ($listafornecedor != NULL) {
    foreach ($listafornecedor as $for) {
        $nomefornecedor = $for->getDescricao();
        $cnpjfornecedor = $for->getCnpj();
        $enderecofornecedor = $for->getEndereco();
        $fonefornecedor = $for->getTelefone();
    }
}

if ($status == 1) {
    $textostatus = "Aberto";
} else {
    $textostatus = "Concluído";
}

$nomefunc = $usuarioController->RetornarNomeUsuarios($_SESSION['codF']);
$nomefunc2 = $nomefunc;


$nomecategoria = $categoriasController->RetornarNomeCat($_GET['categoria']);
if($_GET['categoria']==0){
    $nomecategoria="TODAS AS CATEGORIAS";
}
$termo = "";
$tipo = 4;
$status = 1;

$listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo, $status);



if ($listaUsuariosBusca != null) {
    foreach ($listaUsuariosBusca as $user) {
        $nome = $user->getNome();
        $email = $user->getEmail();
        $foto = $user->getFoto();
        $rua = $user->getRua();
        $bairro = $user->getBairro();
        $numero = $user->getNumero();
        $celular = $user->getCelular();
    }
}

//Define o tempo máximo de execução do script em 60 segundos
set_time_limit(120);

if ($listaUsuariosBusca != null) {
    foreach ($listaUsuariosBusca as $user) {
        $nome = $user->getNome();
        $email = $user->getEmail();
        $foto = $user->getFoto();
        $rua = $user->getRua();
        $bairro = $user->getBairro();
        $numero = $user->getNumero();
        $celularem = $user->getCelular();
        $CNPJ = $user->getCpf();
    }
}


?>      

                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                        <h4>Histórico de Demanda </h4>
                        <div style="width: 100%; text-align: right; font-size: 14pt;">

                            <h3><b><?= $nome; ?></b></h3>

                            <h3>CNPJ:<b><?= $CNPJ; ?></b></h3>

                            <p style="font-size: 10pt;"><b>End:</b> <?= $rua; ?>, nº: <?= $numero; ?> - <?= $bairro; ?></br>
                                <b>E-mail</b>: <?= $email; ?></br>
                                <b>Celular</b>: <?= $celularem; ?></p>
                        </div>
                    </td>


                </table>

                <table class="table table-light table-bordered">

                    <tr>
                        <td colspan='2'style="text-align: right;"><b>Categoria</b></td>
                        <td  style='text-align: center;'>      
                            <?= $nomecategoria ?>
                        </td>
                        <td colspan='2'style="text-align: right;"><b>Mês/Ano</b></td>
                        <td  style='text-align: center;'>      
                            <?= $_GET['mes']; ?>/<?= $_GET['ano']; ?>
                        </td>
                        <td colspan='2'style="text-align: right;"><b>Período</b></td>
                        <td  style='text-align: center;'> 
                            <?php 
                            $secao = $_GET['tiposecao'];
                            if($secao==1){
                                $textoperidosecao= "1º Semana- ". mostraMes($_GET['mes']);;
                            
                            }else if($secao==2){
                            $textoperidosecao ="2º Semana- ". mostraMes($_GET['mes']);
                                
                            }else if($secao==3){
                            $textoperidosecao= "3º Semana- ". mostraMes($_GET['mes']);;
                                
                            }else if($secao==4){
                            $textoperidosecao= "4º Semana- ". mostraMes($_GET['mes']);;
                                
                            }else if($secao==5){
                            $textoperidosecao= "Mês Completo";
                                
                            }else if($secao==6){
                            $textoperidosecao ="Mês Relatório Resumido";
                                
                            }
                            else if($secao==7){
                            $textoperidosecao ="Ano Relatório Completo";
                                
                            }
                            else if($secao==8){
                            $textoperidosecao ="Ano Relatório Resumido";
                                
                            }
                            ?>
                            <?= $textoperidosecao; ?>
                        </td>
                        
                    </tr>
                </table>
                <?php
                $descricao = $_GET['descricao'];
        $ata = $_GET['ata'];
        $categoria = $_GET['categoria'];
        $mes = $_GET['mes'];
        $ano = $_GET['ano'];
        $tiposecao = $_GET['tiposecao'];
        $contindex = 0;
       
         if ($categoria == 0) {
            //   $sqlProdutos = mysqli_query($conn, "SELECT * FROM produtos WHERE descricao LIKE '%" . $descricao . "%' ORDER BY cod ASC");
            $sqlProdutos = "SELECT * FROM servicos WHERE nome LIKE :nome ORDER BY nome ASC";
            $paramProdutos = array(
                ":nome" => "%{$descricao}%"
            );

            $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos, $paramProdutos);
        } else {
            // $sqlProdutos = mysqli_query($conn, "SELECT * FROM produtos WHERE descricao LIKE '%" . $descricao . "%' AND fornecedor = $categoria ORDER BY cod ASC");
            $sqlProdutos = "SELECT * FROM servicos WHERE nome LIKE :nome AND categoria = :fornecedor ORDER BY nome ASC";
            $paramProdutos = array(
                ":nome" => "%{$descricao}%",
                ":fornecedor" => $categoria
            );

            $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos, $paramProdutos);
        }
      


        if ($tiposecao == 1) {
            echo "<table class='table table-bordered'>
                <tr style='text-align:center;'>
                    <td><b>Produto</b></td>";
            for ($i = 1; $i <= 7; $i++) {
                echo "<td><b>Dia $i</b></td>";
            }
            echo "<td><b>Total</b></td>
                
                    <td><b>Qtd Atual</b></td>
                    <td><b>Est. máx</b></td>
                    <td><b>Est. min.</b></td>
                </tr>

        ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];
                $valor = (float)$valor;
                $valor = number_format($valor, 2, ',', '.');

                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }

                echo " <tr style='text-align:center;'>
                    <td style='width:25%;'>" . $descricao . '-' . $textoapresentacao . "</td>";
                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;
                for ($i = 1; $i <= 7; $i++) {
                    $totalSaidasDia = 0;
                    $saldo = 0;


                    $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND dia = $i AND mes = $mes AND ano = $ano ORDER BY cod ASC");
                    while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                        $listasaidascod_saida = $saidaslista->usuario;
                        $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                        while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                            $listasaidasqtd = $saidaslista->qtd;
                            $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                            $totalfinal = $totalfinal + $listasaidasqtd;
                        }
                    }
                    if ($totalSaidasDia != 0) {
                        $mediasemanal = $mediasemanal + $totalSaidasDia;
                        $contadormedia++;
                    }


                    echo "<td>$totalSaidasDia</td>";
                }
                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
                
                $qtdsolic  = 0 ;
                
                $qtdsolic = $est_max - $qtd;
                
                if($qtdsolic < 0){
                    $qtdsolic = "<small style='color:red;'>NÃO SOLICITADO</small>";
                }
                
                echo "<td>$totalfinal</td>
                 
                    <td>$qtd</td>
                        <td>$est_max</td>
                            <td>$est_min</td>
                </tr>
                    ";
            }
            echo "</table>";
        } else if ($tiposecao == 2) {
            echo "<table class='table table-bordered'>
                <tr style='text-align:center;'>
                    <td><b>Produto</b></td>";
            for ($i = 8; $i <= 14; $i++) {
                echo "<td><b>Dia $i</b></td>";
            }
            echo "<td><b>Total</b></td>
               
                 
                    <td><b>Qtd</b></td>
                    <td><b>Est. máx</b></td>
                    <td><b>Est. min.</b></td>
                </tr>

        ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];
 $valor = (float)$valor;
                $valor = number_format($valor, 2, ',', '.');

                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }

                echo " <tr style='text-align:center;'>
                    <td style='width:25%;'>" . $descricao . '-' . $textoapresentacao . "</td>";
                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;
                for ($i = 8; $i <= 14; $i++) {
                    $totalSaidasDia = 0;
                    $saldo = 0;

                    
                    $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND dia = $i AND mes = $mes AND ano = $ano ORDER BY cod ASC");
                    while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                        $listasaidascod_saida = $saidaslista->usuario;
                       echo $listasaidasqtd = $saidaslista->qtd;
                            $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                            $totalfinal = $totalfinal + $listasaidasqtd;
                        $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                        while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                            
                        }
                    }
                    if ($totalSaidasDia != 0) {
                        $mediasemanal = $mediasemanal + $totalSaidasDia;
                        $contadormedia++;
                    }


                    echo "<td>$totalSaidasDia</td>";
                }
                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
                $qtdsolic  = 0 ;
                
                $qtdsolic = $est_max - $qtd;
                
                if($qtdsolic < 0){
                    $qtdsolic = "<small style='color:red;'>NÃO SOLICITADO</small>";
                }
                
                echo "<td>$totalfinal</td>
                  
                    <td>$qtd</td>
                        <td>$est_max</td>
                            <td>$est_min</td>
                </tr>
                    ";
            }
            echo "</table>";
        }if ($tiposecao == 3) {
            echo "<table class='table table-bordered'>
                <tr style='text-align:center;'>
                    <td><b>Produto</b></td>";
            for ($i = 15; $i <= 21; $i++) {
                echo "<td><b>Dia $i</b></td>";
            }
            echo "<td><b>Total</b></td>
                
                    <td><b>Qtd Atual</b></td>
                    <td><b>Est. máx</b></td>
                    <td><b>Est. min.</b></td>
                </tr>

        ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];
 $valor = (float)$valor;
                $valor = number_format($valor, 2, ',', '.');

                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }

                echo " <tr style='text-align:center;'>
                    <td style='width:25%;'>" . $descricao . '-' . $textoapresentacao . "</td>";
                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;
                for ($i = 15; $i <= 21; $i++) {
                    $totalSaidasDia = 0;
                    $saldo = 0;


                    $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND dia = $i AND mes = $mes AND ano = $ano ORDER BY cod ASC");
                    while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                        $listasaidascod_saida = $saidaslista->usuario;
                        $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                        while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                            $listasaidasqtd = $saidaslista->qtd;
                            $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                            $totalfinal = $totalfinal + $listasaidasqtd;
                        }
                    }
                    if ($totalSaidasDia != 0) {
                        $mediasemanal = $mediasemanal + $totalSaidasDia;
                        $contadormedia++;
                    }


                    echo "<td>$totalSaidasDia</td>";
                }
                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
                $qtdsolic  = 0 ;
                
                $qtdsolic = $est_max - $qtd;
                
                if($qtdsolic < 0){
                    $qtdsolic = "<small style='color:red;'>NÃO SOLICITADO</small>";
                }
                
                echo "<td>$totalfinal</td>
                   
                    <td>$qtd</td>
                        <td>$est_max</td>
                            <td>$est_min</td>
                </tr>
                    ";
            }
            echo "</table>";
        }if ($tiposecao == 4) {
            echo "<table class='table table-bordered'>
                <tr style='text-align:center;'>
                    <td><b>Produto</b></td>";
            for ($i = 22; $i <= 28; $i++) {
                echo "<td><b>Dia $i</b></td>";
            }
            echo "<td><b>Total</b></td>
                 
                    <td><b>Qtd Atual</b></td>
                    <td><b>Est. máx</b></td>
                    <td><b>Est. min.</b></td>
                </tr>

        ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];
 $valor = (float)$valor;
                $valor = number_format($valor, 2, ',', '.');

                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }

                echo " <tr style='text-align:center;'>
                    <td style='width:25%;'>" . $descricao . '-' . $textoapresentacao . "</td>";
                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;
                for ($i = 22; $i <= 28; $i++) {
                    $totalSaidasDia = 0;
                    $saldo = 0;


                    $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND dia = $i AND mes = $mes AND ano = $ano ORDER BY cod ASC");
                    while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                        $listasaidascod_saida = $saidaslista->usuario;
                        $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                        while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                            $listasaidasqtd = $saidaslista->qtd;
                            $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                            $totalfinal = $totalfinal + $listasaidasqtd;
                        }
                    }
                    if ($totalSaidasDia != 0) {
                        $mediasemanal = $mediasemanal + $totalSaidasDia;
                        $contadormedia++;
                    }


                    echo "<td>$totalSaidasDia</td>";
                }
                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
                $qtdsolic  = 0 ;
                
                $qtdsolic = $est_max - $qtd;
                
                if($qtdsolic < 0){
                    $qtdsolic = "<small style='color:red;'>NÃO SOLICITADO</small>";
                }
                
                echo "<td>$totalfinal</td>
                   
                    <td>$qtd</td>
                        <td>$est_max</td>
                            <td>$est_min</td>
                </tr>
                    ";
            }
            echo "</table>";
        } else if ($tiposecao == 5) {
            echo "<table class='table table-bordered'>
                ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];
 $valor = (float)$valor;
                $valor = number_format($valor, 2, ',', '.');
                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }


                echo "<tr style='text-align:center;'>
                    <td colspan='2'><b>" . $descricao . "-" . $textoapresentacao . "</b></td>
                   </tr>
                   ";
                echo "<tr style='text-align:center;'>
                    <td><b>Dia</b></td>
                    <td><b>Total Dia</b></td>
                </tr>
                   
        ";


                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;
                for ($i = 1; $i <= 31; $i++) {
                    $totalSaidasDia = 0;
                    $saldo = 0;


                    $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND dia = $i AND mes = $mes AND ano = $ano ORDER BY cod ASC");
                    while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                        $listasaidascod_saida = $saidaslista->usuario;
                        $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                        while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                            $listasaidasqtd = $saidaslista->qtd;
                            $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                            $totalfinal = $totalfinal + $listasaidasqtd;
                        }
                    }
                    if ($totalSaidasDia != 0) {
                        $mediasemanal = $mediasemanal + $totalSaidasDia;
                        $contadormedia++;

                        echo "<tr style='text-align:center;'>
                    <td>Dia $i</td>
                    <td>$totalSaidasDia</td>
                </tr>
                   ";
                    }
                }
                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
                echo "<tr style='text-align:center;'>
                    <td><b>Total: </b></td><td>$totalfinal</td>
                    
                </tr>
                    ";

                echo "<tr style='text-align:center;'>
                    <td><b>Média Diária: </b></td><td>" . (int) $resultadomedia . "</td>
                    
                </tr>
                    ";
            }
            echo "</table>";
        } else if ($tiposecao == 6) {
            echo "<table class='table table-bordered'>
                ";
            echo "<tr style='text-align:center;'>
                    <td colspan='2'><b>Produto</b></td>
                    <td colspan='2'><b>Total Mensal</b></td>
                    <td colspan='2'><b>Estoque Atual</td>
     
                   </tr>
                   ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $qtdatual22 = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];
 $valor = (float)$valor;
                $valor = number_format($valor, 2, ',', '.');
                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }

                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;

                $totalSaidasDia = 0;
                $saldo = 0;


                $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND mes = $mes AND ano = $ano ORDER BY cod ASC");
                while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                    $listasaidascod_saida = $saidaslista->usuario;
                    $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                    while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                        $listasaidasqtd = $saidaslista->qtd;
                        $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                        $totalfinal = $totalfinal + $listasaidasqtd;
                    }
                }
                if ($totalSaidasDia != 0) {
                    $mediasemanal = $mediasemanal + $totalSaidasDia;
                    $contadormedia++;
                }

                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }


                echo "<tr style='text-align:center;'>
                    <td colspan='2'>" . $descricao . "-" . $textoapresentacao . "</td>
                    <td colspan='2'> $totalfinal</td>
                    <td colspan='2'>" . (int) $qtdatual22 . "</td>
     
                   </tr>
                   ";
            }
            echo "</table>";
        } else if ($tiposecao == 7) {
            echo "<table class='table table-bordered'>
                ";
            $textoapresentacao = "";
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];


                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }


                echo "<tr style='text-align:center;'>
                    <td colspan='2'><b>" . $descricao . "-" . $textoapresentacao . "</b></td>
                   </tr>
                   ";
                echo "<tr style='text-align:center;'>
                    <td><b>Mês</b></td>
                    <td><b>Total Dia</b></td>
                </tr>
                   
        ";

                $resultadomedia = 0;
                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $totalSaidasDia = 0;
                    $saldo = 0;


                    $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND mes = $i AND ano = $ano ORDER BY cod ASC");
                    while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                        $listasaidascod_saida = $saidaslista->usuario;
                        $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                        while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                            $listasaidasqtd = $saidaslista->qtd;
                            $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                            $totalfinal = $totalfinal + $listasaidasqtd;
                        }
                    }
                    if ($totalSaidasDia != 0) {
                        $mediasemanal = $mediasemanal + $totalSaidasDia;
                        $contadormedia++;

                        echo "<tr style='text-align:center;'>
                    <td>" . mostraMes($i) . "</td>
                    <td>$totalSaidasDia</td>
                </tr>
                   ";
                    }
                }
                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
                echo "<tr style='text-align:center;'>
                    <td><b>Total: </b></td><td>$totalfinal</td>
                    
                </tr>
                    ";

                echo "<tr style='text-align:center;'>
                    <td><b>Média Anual: </b></td><td>" . (int) $resultadomedia . "</td>
                    
                </tr>
                    ";
            }
            echo "</table>";
            $resultadomedia = 0;
        } else if ($tiposecao == 8) {
            echo "<table class='table table-bordered'>
                
                ";

            echo "<tr style='text-align:center;'>
                    <td colspan='2'><b>Produto</b></td>
                         <td colspan='2'><b>Total Anual</td>
                               <td colspan='2'><b>Média Mensal</td>
                   </tr>
                   ";
            $textoapresentacao = "";
            $totalfinal = 0;
            foreach ($dataTableProdutos as $resultadoprodutos) {
                $codproduto = $resultadoprodutos['cod'];
                $apresentacao = $resultadoprodutos['apresentacao'];
                $descricao = $resultadoprodutos['nome'];
                $qtd = $resultadoprodutos['qtd'];
                $valor = $resultadoprodutos['valor'];
                $categoria = $resultadoprodutos['categoria'];
                $fornecedor = $resultadoprodutos['fornecedor'];
                $est_min = $resultadoprodutos['est_mim'];
                $est_max = $resultadoprodutos['est_max'];


                if ($apresentacao == 1) {
                    $textoapresentacao = "Unidade";
                } else if ($apresentacao == 2) {
                    $textoapresentacao = "Comprimido";
                } else if ($apresentacao == 3) {
                    $textoapresentacao = "Ampola";
                } else if ($apresentacao == 4) {
                    $textoapresentacao = "Frasco Ampola";
                } else if ($apresentacao == 5) {
                    $textoapresentacao = "Frasco";
                } else if ($apresentacao == 6) {
                    $textoapresentacao = "Caixa";
                } else if ($apresentacao == 7) {
                    $textoapresentacao = "Pacote";
                } else if ($apresentacao == 8) {
                    $textoapresentacao = "Kit";
                } else if ($apresentacao == 9) {
                    $textoapresentacao = "Outros";
                }


                echo "<tr style='text-align:center;'>
                    <td colspan='2'>" . $descricao . "-" . $textoapresentacao . "</td>
                          <td colspan='2'>$totalfinal</td>
                         <td colspan='2'>" . (int) $resultadomedia . "</td>
                             
                   </tr>
                   ";



                $mediasemanal = 0;
                $contadormedia = 0;
                $totalfinal = 0;
                $resultadomedia = 0;

                $totalSaidasDia = 0;
                $saldo = 0;


                $sqlSaidasLista = mysqli_query($conn, "SELECT * FROM pedidos WHERE servico = $codproduto AND ano = $ano ORDER BY cod ASC");
                while ($saidaslista = mysqli_fetch_object($sqlSaidasLista)) {
                    $listasaidascod_saida = $saidaslista->usuario;
                    $sqlSaidas = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $listasaidascod_saida ORDER BY cod ASC LIMIT 1");
                    while ($saidas = mysqli_fetch_object($sqlSaidas)) {
                        $listasaidasqtd = $saidaslista->qtd;
                        $totalSaidasDia = $totalSaidasDia + $listasaidasqtd;
                        $totalfinal = $totalfinal + $listasaidasqtd;
                    }
                }
                if ($totalSaidasDia != 0) {
                    $mediasemanal = $mediasemanal + $totalSaidasDia;
                    $contadormedia++;
                }

                if ($contadormedia != 0) {
                    $resultadomedia = $mediasemanal / $contadormedia;
                }
            }
            echo "</table>";
        }

        
        ?>
                <table class="table table-light table-bordered">

                      <tr>
                        <td colspan="7" style="text-align: center;">
                            </br>
                            </br>
                            </br>
                            <p>
                                </br>_________________________________________________________________
                                </br>
                                <?= $nomefunc2 ?>
                                </br>
                            </p>
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: right;">Data</td>
                        <th colspan="2"><?= date('d/m/Y h:i:s'); ?></th>
                    </tr>
                </table>
               
            </div>
        </div>
    </div>
</div>


