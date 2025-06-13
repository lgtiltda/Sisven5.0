<?php
require_once ("Controller/MovimentoClientesController.php");
require_once ("Model/MovimentoClientes.php");

require_once ("Controller/PedidosController.php");
require_once ("Model/Pedidos.php");

require_once("Util/conn.php");


require_once ("Controller/CategoriaSerFinController.php");
require_once ("Model/CategoriaSerFin.php");

require_once("Controller/PagParProController.php");
require_once("Model/PagParPro.php");

require_once("Controller/CategoriaFinanceiroController.php");
require_once("Model/CategoriaFinanceiro.php");

require_once("Controller/ClientesController.php");
require_once("Model/Clientes.php");

require_once("Controller/MovimentoEmpresaController.php");
require_once("Model/MovimentoEmpresa.php");

require_once ("Controller/PagamentoController.php");
require_once ("Model/Pagamento.php");

require_once("Model/Notas.php");
require_once("Controller/NotasController.php");

require_once("Controller/ServicoController.php");
require_once ("Model/Servicos.php");

require_once("Util/functions.php");


require_once("Model/Usuarios.php");
require_once("Controller/UsuariosController.php");

$usuarioController = new UsuarioController();
$clienteController = new ClientesController();
$movimentoClienteController = new MovimentoClientesController();

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

$termo = "";
$tipo = 4;
$status = 1;
$listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo, $status);


$termo = "";
$tipo = 4;
$status = 1;
$listaUsuariosBusca2 = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

//var_dump($listaUsuariosBusca2);


$totalfinaldia = 0;
$totalfinalmes = 0;
$totalfinalano = 0;

$totalfinaldiatipo1 = 0;
$totalfinaldiatipo2 = 0;
$totalfinaldiatipo3 = 0;

$totalfinalmestipo1 = 0;
$totalfinalmestipo2 = 0;
$totalfinalmestipo3 = 0;

$totalfinalanotipo1 = 0;
$totalfinalanotipo2 = 0;
$totalfinalanotipo3 = 0;

$totalfinaldiatipopag1 = 0;
$totalfinaldiatipopag2 = 0;
$totalfinaldiatipopag3 = 0;

$totalfinalmestipopag1 = 0;
$totalfinalmestipopag2 = 0;
$totalfinalmestipopag3 = 0;

$totalfinalanotipopag1 = 0;
$totalfinalanotipopag2 = 0;
$totalfinalanotipopag3 = 0;

$cont = 0;
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

$resultado = '';

$valorfinal = 0;

if (isset($_GET['dia'])) {
    $dia_hoje = $_GET['dia'];
} else {
    $dia_hoje = date('d');
}

if (isset($_GET['mes'])) {
    $mes_hoje = $_GET['mes'];
} else {
    $mes_hoje = date('m');
}

if (isset($_GET['ano'])) {
    $ano_hoje = $_GET['ano'];
} else {
    $ano_hoje = date('Y');
}

$pacienteController = new ClientesController();
$notasController = new NotasController();
$pagamentoController = new PagamentoController();
$movimentopacController = new MovimentoClientesController();
$categoriaserfinController = new CategoriaSerFinController();
$movimentoEmpresaController = new MovimentoEmpresaController();
$categoriaController = new CategoriaFinanceiroController();
$pagparproController = new PagParProController();
$orcamentoController = new NotasController();
$pedidosController = new PedidosController();
$servicoController = new ServicoController();

$listamovimentosPorMes = [];
$listamovimentosPorAno = [];
$ListaCategoriass = [];
$ListaCatMovPac = [];


$ListaCategorias = $categoriaserfinController->RetornarCategorias();

$ListaCategoriass = [];
$ListaCategoriass = $categoriaController->RetornarCategorias(1);

$listamovimentosPorMes = $movimentopacController->RetornarMes($mes_hoje, $ano_hoje);

$listamovimentosPorAno = $movimentopacController->RetornarAno($ano_hoje);
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
    $banco = new Banco();


    ?>      

                    <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                        <td style="width:15%; text-align: center; ">
                            <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                        </td>
                        <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                            <h4>FECHAMENTO DE CAIXA - <?= $_GET['dia'];?>/<?= mostraMes($_GET['mes']);?>/<?= $_GET['ano'];?> </h4>
                            <div style="width: 100%; text-align: right; font-size: 14pt;">

                                <h3><b><?= $nome; ?></b></h3>

                                <h3>CNPJ:<b><?= $CNPJ; ?></b></h3>

                                <p style="font-size: 10pt;"><b>End:</b> <?= $rua; ?>, nº: <?= $numero; ?> - <?= $bairro; ?></br>
                                    <b>E-mail</b>: <?= $email; ?></br>
                                    <b>Celular</b>: <?= $celularem; ?></p>
                            </div>
                        </td>


                    </table>
<?php

Error_reporting(0);
        $dia = $_GET['dia'];
        $mes = $_GET['mes'];
        $ano = $_GET['ano'];
     


        echo "</br><table class='table' style='font-size:10pt;'> 
            <tr style='text-align:center;'>
                <td colspan='7'><b>Faturamento por Funcionário:</b></td>
            </tr>
            <tr style='text-align:left; '>
                <td colspan=''><b>FUNCIONÁRIO</b></td>
                <td colspan=''><b>PAGAMENTO EM DINHEIRO</b></td>
                <td colspan=''><b>PAGAMENTO CARTÃO DÉBITO </b></td>
                <td colspan=''><b>PAGAMENTO PIX </b></td>
                <td colspan=''><b>PAGAMENTO CARTÃO CRÉDITO </b></td>
                <td colspan=''><b>PAGAMENTO CREDIÁRIO </b></td>
                <td colspan=''><b>TOTAL</b></td>
            </tr>
            ";

        $sqlNota2 = "SELECT * FROM usuarios ORDER BY cod ASC";


        $valorTotalFinal2 = 0;
        $valorTotalFinalDinheiro2 = 0;
        $valorTotalFinalCartao2 = 0;
        $valorTotalFinalCartaoDeb2 = 0;
        $valorTotalFinalCartaoCred2 = 0;
        $valorTotalFinalPix2 = 0;
        $valorTotalFinalCrediario2 = 0;
        $dataTableNota2 = $banco->ExecuteQuery($sqlNota2);
        foreach ($dataTableNota2 as $resultadonota2) {
            //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 3 AND dia = $dia AND mes = $mes AND ano = $ano ORDER BY cod ASC");
            $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = :func  ORDER BY cod DESC";
            $paramNota = array(
                ":status" => 3,
                ":dia" => $dia,
                ":mes" => $mes,
                ":ano" => $ano,
                ":func" => $resultadonota2['cod'],
            );
            $valorTotalFinal = 0;
            $valorTotalFinalDinheiro = 0;
            $valorTotalFinalCartao = 0;
            $valorTotalFinalCartaoDeb = 0;
            $valorTotalFinalCartaoCred = 0;
            $valorTotalFinalPix = 0;
            $valorTotalFinalCrediario = 0;

            $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
            foreach ($dataTableNota as $resultadonota) {
                $qtdTotalFinal = 0;
                $nomecli = "";
                $codnota = $resultadonota['cod'];

                $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod DESC";
                $paramNotaPag = array(
                    ":cod" => $codnota
                );

                $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
                foreach ($dataTableNotaPag as $resultadonotaPag) {
                    if ($resultadonotaPag['tipo'] == 1 && $resultadonotaPag['tipopag'] == 1) {
                        $valorTotalFinalDinheiro = $valorTotalFinalDinheiro + $resultadonotaPag['total'];
                        $valorTotalFinalDinheiro2 = $valorTotalFinalDinheiro2 + $resultadonotaPag['total'];
                    }
                    if ($resultadonotaPag['tipo'] == 1 && $resultadonotaPag['tipopag'] == 2) {
                        $valorTotalFinalCartaoDeb = $valorTotalFinalCartaoDeb + $resultadonotaPag['total'];
                        $valorTotalFinalCartaoDeb2 = $valorTotalFinalCartaoDeb2 + $resultadonotaPag['total'];
                    }
                    if ($resultadonotaPag['tipo'] == 1 && $resultadonotaPag['tipopag'] == 3) {
                        $valorTotalFinalPix = $valorTotalFinalPix + $resultadonotaPag['total'];
                        $valorTotalFinalPix2 = $valorTotalFinalPix2 + $resultadonotaPag['total'];
                    }
                    if ($resultadonotaPag['tipo'] == 2 && $resultadonotaPag['tipopag'] == 1) {
                        $valorTotalFinalCartaoCred = $valorTotalFinalCartaoCred + $resultadonotaPag['total'];
                        $valorTotalFinalCartaoCred2 = $valorTotalFinalCartaoCred2 + $resultadonotaPag['total'];
                    }
                    if ($resultadonotaPag['tipo'] == 2 && $resultadonotaPag['tipopag'] == 2) {
                        $valorTotalFinalCrediario = $valorTotalFinalCrediario + $resultadonotaPag['total'];
                        $valorTotalFinalCrediario2 = $valorTotalFinalCrediario2 + $resultadonotaPag['total'];
                    }


                    $valorTotalFinal = $valorTotalFinal + $resultadonotaPag['total'];
                    $valorTotalFinal2 = $valorTotalFinal2 + $resultadonotaPag['total'];
                }
            }


            echo "
            <tr style='text-align:left;  '>
                <td>" . $resultadonota2['nome'] . "</td>
                
                <td>R$ " . number_format($valorTotalFinalDinheiro, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalCartaoDeb, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalPix, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalCartaoCred, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalCrediario, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
               
            </tr>
        ";
        }
        echo "
            <tr style='text-align:left; font-size:12pt; '>
                <td></b>TOTAIS FINAL</td>
                
                <td><b>R$ " . number_format($valorTotalFinalDinheiro2, 2, ',', '.') . "</b></td>
                <td><b>R$ " . number_format($valorTotalFinalCartaoDeb2, 2, ',', '.') . "</b></td>
                <td><b>R$ " . number_format($valorTotalFinalPix2, 2, ',', '.') . "</td>
                <td><b>R$ " . number_format($valorTotalFinalCartaoCred2, 2, ',', '.') . "</b></td>
                <td><b>R$ " . number_format($valorTotalFinalCrediario2, 2, ',', '.') . "</b></td>
                <td><b>R$ " . number_format($valorTotalFinal2, 2, ',', '.') . "</b></td>
               
            </tr>
        ";

        $sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE dia = $dia AND mes = $mes AND ano = $ano ORDER BY id ASC");
// Exibe todos os valores encontrados
        $totalfinaldiacat = 0;
        while ($dividadia = mysqli_fetch_object($sqlDividasDia)) {
            $cod = $dividadia->id;
            $pontos = ',';
            $result = str_replace($pontos, "", $dividadia->valor);
            $valor_total = (float) $result;
            $total = $valor_total;

            $totalfinaldiacat = $totalfinaldiacat + $total;
        }


        echo " <tr style='text-align:left; font-size:12pt; '>
                <td colspan='6'><b>DESPESAS DO DIA</b></td>
                <td colspan=''><b>R$ " . number_format($totalfinaldiacat, 2, ',', '.') . "</b></td>
            </tr>";
        $SALDODIA = 0;
        $SALDODIA = $valorTotalFinal2 - $totalfinaldiacat;
        echo " <tr style='text-align:left; font-size:12pt; '>
                <td colspan='6'><b>SALDO DO DIA</b></td>
                <td colspan=''><b>R$ " . number_format($SALDODIA, 2, ',', '.') . "</b></td>
            </tr>";
        ECHO "</table>";

      
        echo "<div id='ResultadoValidacao1500'>
            
            ";
        echo "</br><table class='table' style='font-size:12pt;'>
            <tr style='text-align:center;'>
                <td><b>COD. NOTA</b></td>
                <td><b>INFORMAÇÕES DO PEDIDO</b></td>
                <td><b>INFORMAÇÕES DO PAGAMENTO</b></td>
                <td><b>CLIENTE</b></td>
                <td><b>FUNCIONÁRIO</b></td>
            
            </tr>
            ";

        //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 3 AND dia = $dia AND mes = $mes AND ano = $ano ORDER BY cod ASC");
        $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod DESC";
        $paramNota = array(
            ":status" => 3,
            ":dia" => $dia,
            ":mes" => $mes,
            ":ano" => $ano,
        );

        $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
        foreach ($dataTableNota as $resultadonota) {
            $qtdTotalFinal = 0;
            $valorTotalFinal = 0;
            $nomecli = "";
            $textotipopag = "";
            $codnota = $resultadonota['cod'];
            $cod_usuarionota = $resultadonota['usuario'];
            $datadia_nota = $resultadonota['dia'];
            $datames_nota = $resultadonota['mes'];
            $dataano_nota = $resultadonota['ano'];
            $codfuncionario_nota = $resultadonota['func'];

            if ($cod_usuarionota == 0) {
                $nomecli = $resultadonota['nomeCli'];
            } else {
                $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
                $paramCli = array(
                    ":id" => $cod_usuarionota
                );

                $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
                foreach ($dataTableCli as $resultadocli) {
                    $nomecli = $resultadocli['nome'];
                    $enderecocli = $resultadocli['endereco'];
                    $numerocli = $resultadocli['numero'];
                    $complementocli = $resultadocli['complemento'];
                    $celularcli = $resultadocli['celular'];
                }
            }
            //$sqlFunc = mysqli_query($conn, "SELECT * FROM usuarios WHERE cod = $codfuncionario_nota ORDER BY cod ASC LIMIT 1");
            $sqlFunc = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
            $paramFunc = array(
                ":cod" => $codfuncionario_nota
            );

            $dataTableFunc = $banco->ExecuteQuery($sqlFunc, $paramFunc);
            foreach ($dataTableFunc as $resultadofunc) {
                $nomefunc = $resultadofunc['nome'];
            }
  $textopedidos = "";
            $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
            while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
                $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
                $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
                $codnota = $pedidos->servico;
              
                $sqlCli = "SELECT * FROM servicos WHERE cod = :id ORDER BY cod ASC";
                $paramCli = array(
                    ":id" => $codnota
                );

                $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
                foreach ($dataTableCli as $resultadocli) {
                    $codproduto = $resultadocli['nome'];

                    $textopedidos = $textopedidos . $codproduto . " | ";
                }
            }


            $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
            while ($pag = mysqli_fetch_object($sqlPagCli)) {
                $valorTotalFinal = (float) $pag->total;
                if ($pag->tipo == 1 && $pag->tipopag == 1) {
                    $textotipopag = "Pagamento em Dinheiro";
                }
                if ($pag->tipo == 1 && $pag->tipopag == 2) {
                    $textotipopag = "Pagamento em Cartão Débito";
                }
                if ($pag->tipo == 1 && $pag->tipopag == 3) {
                    $textotipopag = "Pagamento em Pix";
                }
                if ($pag->tipo == 2 && $pag->tipopag == 1) {

                    $textotipopag = "Pagamento no Cartão de Crédito";
                }

                if ($pag->tipo == 2 && $pag->tipopag == 2) {

                    $textotipopag = "Pagamento no Crediário";
                }
            }

            echo "
            <tr style='text-align:justify;'>
            <td>$codnota</td>
            <td style='font-size:9pt;'>$textopedidos</td>
            <td>
            $textotipopag </BR>
            VALOR TOTAL:</br><b> R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</br></td>
         
            <td>$nomecli</td>
            <td>$nomefunc</td>
           </tr>";
        }
        echo "</table></div></div>";
            

$nomefunc2 = $usuarioController->RetornarNomeUsuarios($_SESSION['codF']);
                ?>
               
                <table style='font-size:9pt;' class="table table-light table-bordered">

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
