<?php
require_once ("Controller/MovimentoClientesController.php");
require_once ("Model/MovimentoClientes.php");

require_once ("Controller/PedidosController.php");
require_once ("Model/Pedidos.php");


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

require_once("Util/conn.php");

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


?>      

                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                        <h4>Balanço Mensal - <?= mostraMes($_GET['mes']) ?>/<?= $_GET['ano']; ?> </h4>
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
$cod_usu = 1;
$totalfinaldiatipopag1 = 0;
$totalfinaldiatipopag2 = 0;
$totalfinaldiatipopag3 = 0;
$totalfinaldiatipopag4 = 0;
$listaNotasDia = $notasController->RetornarNotasDiaMesAnoF(3, 1, $dia_hoje, $mes_hoje, $ano_hoje);
if ($listaNotasDia != null) {
    foreach ($listaNotasDia as $user4) {
        $cod_func = $user4->getFunc();
        $listaPagamentos = $movimentoClienteController->RetornarPagamentos4($user4->getCod());
        if ($listaPagamentos != null) {
            foreach ($listaPagamentos as $user5) {

                $parcelas1 = $user5->getNumparcelas();
                $pontos = ',';
                $result = str_replace($pontos, "", $user5->getTotal());
                $valor_total = (float) $result;
                $total = $valor_total;

                $totalfinaldia = $totalfinaldia + $total;
                if ($user5->getTipo() == 1) {
                    $totalfinaldiatipo1 = $totalfinaldiatipo1 + $total;

                    if ($user5->getTipopag() == 1) {
                        $totalfinaldiatipopag1 = $totalfinaldiatipopag1 + $total;
                    } else if ($user5->getTipopag() == 2) {
                        $totalfinaldiatipopag2 = $totalfinaldiatipopag2 + $total;
                    }
                } else if ($user5->getTipo() == 2) {
                    $totalfinaldiatipo2 = $totalfinaldiatipo2 + $total;

                    if ($user5->getTipopag() == 1) {
                        $totalfinaldiatipopag3 = $totalfinaldiatipopag3 + $total;
                    } else if ($user5->getTipopag() == 2) {
                        $totalfinaldiatipopag4 = $totalfinaldiatipopag4 + $total;
                    }
                }
            }
        }
    }
}
$cod_orgao = $_SESSION['cod_orgaoF'];
$qtdtotalentfinal = 0;
$qtdtotalsaifinal = 0;
$valortotalentfinal = 0;
$valortotalsaifinal = 0;
$qtdtotalent = 0;
$valortotalent = 0;

$banco2 = new Banco();
$sqlCat = "SELECT * FROM lista_entradas WHERE mes = $mes_hoje AND ano = $ano_hoje ORDER BY cod ASC";

$dataTableCat = $banco2->ExecuteQuery($sqlCat);
foreach ($dataTableCat as $resultadocat) {
    $cod_entrada = $resultadocat['cod_entrada'];

    $sqlCat2 = "SELECT * FROM entradas WHERE cod = $cod_entrada ORDER BY cod ASC LIMIT 1";

    $dataTableCat2 = $banco2->ExecuteQuery($sqlCat2);
    foreach ($dataTableCat2 as $resultadocat2) {
        $qtdtotalent = $qtdtotalent + $resultadocat['qtd'];
        $qtdtotalentfinal = $qtdtotalentfinal + $resultadocat['qtd'];

        $valortotalent = $valortotalent + $resultadocat['valor_total'];
        $valortotalentfinal = $valortotalentfinal + $resultadocat['valor_total'];
    }
}


$totaldesp = 0;

if ($ListaCategoriass != null) {
    foreach ($ListaCategoriass as $user4) {

        $cont = 0;
        $conttotal = 0;
        $totalfinal = 0;
        $listamovimentosPorDia = $movimentoEmpresaController->RetornarPorCategoriaDia(2, $dia_hoje, $mes_hoje, $ano_hoje, $user4->getId());

        if ($listamovimentosPorDia != null) {
            foreach ($listamovimentosPorDia as $despesas) {
                $total = 0;
                $pontos = ',';
                $result = str_replace($pontos, "", $despesas->getValor());
                $valor_total = (float) $result;
                $total = $valor_total;
                $totalfinal = $totalfinal + $total;
            }
        }

        $totaldesp = $totaldesp + $totalfinal;
        ?>

        <?php
        $totalfinal = 0;
        $cont = 0;
    }
}
?>
<table class="table table-bordered" style="width: 100%;">  

    <tr>
        <td colspan="" style="text-align: center;">
            <b>FATURAMENTO</b>
        </td>
        <td colspan="" style="text-align: center;">
            <b>INVESTIMENTO EM ESTOQUE</b>
        </td>
        <td colspan="" style="text-align: center;">
            <b>DESPESAS</b>
        </td>
        <td colspan="" style="text-align: center;">
            <b>LUCRO FINAL</b>
        </td>
    </tr>
    <tr>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($totalfinaldia, 2, ',', '.') ?>
        </td>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($valortotalent, 2, ',', '.') ?>
        </td>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($totaldesp, 2, ',', '.') ?>

        </td>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($totalfinaldia - $valortotalent - $totaldesp, 2, ',', '.') ?>
        </td>
    </tr>
</table>

<?php
$cod_usu = 1;
$totalfinaldiatipopag1 = 0;
$totalfinaldiatipopag2 = 0;
$totalfinaldiatipopag3 = 0;
$totalfinaldiatipopag4 = 0;
?>
<table class="table table-bordered" style="width: 100%;">  

    <tr>
        <td colspan="8" style="text-align: center;">
            <b>Faturamento</b>
        </td>
    </tr>
    <tr style="text-align: center;">
        <td rowspan="2">
            </br>
            Total
        </td>
        <td colspan="2">
            Forma de Pagamento
        </td>
        <td colspan="5">
            Tipo Pagamento
        </td>

    </tr>
    <tr>
        <th>Á vista</th>
        <th>Parcelado</th>
        <th>Dinheiro</th>
        <th>Débito</th>
        <th>Pix</th>
        <th>Crédito</th>
        <th>Crediário</th>
    </tr>   
    <?php
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
    $cod_usu = 1;
    $totalfinaldiatipopag1 = 0;
    $totalfinaldiatipopag2 = 0;
    $totalfinaldiatipopag3 = 0;
    $totalfinaldiatipopag4 = 0;
    $totalfinaldiatipopag5 = 0;
    $listaNotasDia = $notasController->RetornarNotasDiaMesAnoF(3, 1, $dia_hoje, $mes_hoje, $ano_hoje);
    if ($listaNotasDia != null) {
        foreach ($listaNotasDia as $user4) {
            $cod_func = $user4->getFunc();
            $listaPagamentos = $movimentoClienteController->RetornarPagamentos4($user4->getCod());
            if ($listaPagamentos != null) {
                foreach ($listaPagamentos as $user5) {

                    $parcelas1 = $user5->getNumparcelas();
                    $pontos = ',';
                    $result = str_replace($pontos, "", $user5->getTotal());
                    $valor_total = (float) $result;
                    $total = $valor_total;

                    $totalfinaldia = $totalfinaldia + $total;
                    if ($user5->getTipo() == 1) {
                        $totalfinaldiatipo1 = $totalfinaldiatipo1 + $total;

                        if ($user5->getTipopag() == 1) {
                            $totalfinaldiatipopag1 = $totalfinaldiatipopag1 + $total;
                        } else if ($user5->getTipopag() == 2) {
                            $totalfinaldiatipopag2 = $totalfinaldiatipopag2 + $total;
                        } else if ($user5->getTipopag() == 3) {
                            $totalfinaldiatipopag5 = $totalfinaldiatipopag5 + $total;
                        }
                    } else if ($user5->getTipo() == 2) {
                        $totalfinaldiatipo2 = $totalfinaldiatipo2 + $total;

                        if ($user5->getTipopag() == 1) {
                            $totalfinaldiatipopag3 = $totalfinaldiatipopag3 + $total;
                        } else if ($user5->getTipopag() == 2) {
                            $totalfinaldiatipopag4 = $totalfinaldiatipopag4 + $total;
                        }
                    }
                }
            }
        }
    }
    ?>
    <tr>
        <td style="text-align: center;"><b>R$ <?= number_format($totalfinaldia, 2, ',', '.'); ?></b></td>
        <td>R$ <?= number_format($totalfinaldiatipo1, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipo2, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipopag1, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipopag2, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipopag5, 2, ',', '.') ?></td>
        <td > R$ <?= number_format($totalfinaldiatipopag3, 2, ',', '.') ?></td>
        <td > R$ <?= number_format($totalfinaldiatipopag4, 2, ',', '.') ?></td>
    </tr>
</table>
<table class="table table-bordered" style="width: 100%; ">  

    <tr>
        <td colspan="8" style="text-align: center;">
            <b>Crediários em Aberto</b>
        </td>
    </tr>
    <?php
    $mes = $mes_hoje;
    $ano = $ano_hoje;

    echo "
			<tr style='text-align:center;'>
				<td colspan='2'><b>nº de Contas a Receber</b></td>
				<td colspan='2'><b>Total em Crediário</b></td>
				<td colspan='2'><b>Total Recebido</b></td>
				<td colspan='2'><b>Total a Receber</b></td>
                                </tr>
                                ";


    $contadorcontas = 0;
    $totalemcrediario = 0;
    $valortotalparcelas = 0;

    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 AND mes = $mes_hoje AND ano = $ano_hoje ORDER BY cod ASC");
    // Exibe todos os valores encontrados

    while ($financeirocli = mysqli_fetch_object($sql)) {
        $contadorparcelas = 0;
        $codfin = $financeirocli->cod;
        $codnota = $financeirocli->cod_orcamento;
        $valortotalfinanceiro = (float) $financeirocli->total;
        $diaatual = date('d');
        $anoatual = date('Y');
        $mesatual = date('m');

        $diapag = 0;
        $mespag = 0;
        $anopag = 0;
        $codparcela = 0;

        $troco = $financeirocli->gorjeta;
        $pagamentototal = (float) $financeirocli->total;
        $pagamentototal2 = $pagamentototal;
        $numparcelas = (float) $financeirocli->numparcelas;
        if ($numparcelas != 0) {
            $valorparcela = $pagamentototal / $numparcelas;
        } else {
            $valorparcela = $pagamentototal;
        }
        $valorparcela = number_format($valorparcela, 2, ',', '.');
        $valorparcela2 = $valorparcela;
        $pagamentototal = number_format($pagamentototal, 2, ',', '.');
        $tipopag1 = $financeirocli->tipo;
        $tipopag2 = $financeirocli->tipopag;

        $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($finpar = mysqli_fetch_object($sqlPar)) {
            $contadorparcelas++;
        }

        if ($numparcelas != $contadorparcelas) {
            $contadorcontas++;
            $totalemcrediario = $totalemcrediario + $valortotalfinanceiro;

            $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
            // Exibe todos os valores encontrados
            while ($finpar = mysqli_fetch_object($sqlPar)) {
                $valortotalparcelas = $valortotalparcelas + (float) $finpar->valor;
                $diapag = $finpar->dia;
                $mespag = $finpar->mes;
                $anopag = $finpar->ano;
            }


            $textostatus = "";
            if ($diapag == 0) {
                $textostatus = " Nenhuma Parcela Paga";
            }
        }
    }
    $VALORTOTALARECEBER = 0;
    if ($contadorcontas != 0) {
        echo " <tr style='text-align:center;'>
				
				<td colspan='2'>$contadorcontas</td>
				<td colspan='2'>" . number_format($totalemcrediario, 2, ',', '.') . "</td>
				<td colspan='2'>" . number_format($valortotalparcelas, 2, ',', '.') . "</td>
				<td colspan='2'>" . number_format($totalemcrediario - $valortotalparcelas, 2, ',', '.') . "</td>
				
                                </tr>
                                ";
        $VALORTOTALARECEBER = $totalemcrediario - $valortotalparcelas;
    }


    echo " 
		
			<tr style='text-align:center;'>
				<td ><b>Cod. Nota</b></td>
				<td><b>Cliente</b></td>
				<td><b>Qtd Pedidos</b></td>
				<td><b>Valor Total</b></td>
				<td><b>Recebido</b></td>
				<td><b>A receber</b></td>
				<td><b>Pagamento</b></td>
				<td><b>Status</b></td>
			</tr>
		";
    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 AND mes = $mes AND ano = $ano ORDER BY cod ASC");
// Exibe todos os valores encontrados
    $valortotalfinanceiro2 = 0;
    while ($financeirocli = mysqli_fetch_object($sql)) {
        $contadorparcelas = 0;
        $codfin = $financeirocli->cod;
        $codnota = $financeirocli->cod_orcamento;
        $valortotalfinanceiro = (float) $financeirocli->total;
        $valortotalfinanceiro2 = (float) $financeirocli->total;
        $diaatual = date('d');
        $anoatual = date('Y');
        $mesatual = date('m');
        $diapag = 0;
        $mespag = 0;
        $anopag = 0;
        $valortotalparcelas = 0;
        $codparcela = 0;

        $troco = $financeirocli->gorjeta;
        $pagamentototal = (float) $financeirocli->total;
        $pagamentototal2 = $pagamentototal;
        $numparcelas = (float) $financeirocli->numparcelas;
        IF ($numparcelas != 0) {
            $valorparcela = $pagamentototal / $numparcelas;
        } ELSE {
            $valorparcela = $pagamentototal;
        } $valorparcela = number_format($valorparcela, 2, ',', '.');
        $valorparcela2 = $valorparcela;
        $pagamentototal = number_format($pagamentototal, 2, ',', '.');
        $tipopag1 = $financeirocli->tipo;
        $tipopag2 = $financeirocli->tipopag;

        if ($financeirocli->tipo == 1) {// a vista
            if ($financeirocli->tipopag == 1) {// dinheiro
                $textopagamentotipo = "Pagamento á Vista no Dinheiro";
            } else {//debito
                $textopagamentotipo = "Pagamento á Vista no Débito";
            }
        } else {//parcelado
            if ($financeirocli->tipopag == 1) {// credito
                $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Cartão de Crédito";
            } else {//crediario
                $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Crediário";
            }
        }

        //$sqlNota = mysqli_query($conn, "SELECT * FROM notas WHERE cod = " . $codnota . " ORDER BY cod ASC LIMIT 1");
        $sqlNotas = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramNotas = array(
            ":cod" => $codnota
        );

        $dataTableNotas = $banco2->ExecuteQuery($sqlNotas, $paramNotas);
        foreach ($dataTableNotas as $resultadonotas) {

            $usuarionota = $resultadonotas['usuario'];
            $nomecli = $resultadonotas['nomeCli'];
            //$sqlNomecli = mysqli_query($conn, "SELECT * FROM clientes WHERE id = $usuarionota ORDER BY id ASC LIMIT 1");
            $sqlClientes = "SELECT * FROM clientes WHERE id = :cod ORDER BY id ASC LIMIT 1";
            $paramClientes = array(
                ":cod" => $usuarionota
            );

            $dataTableClientes = $banco2->ExecuteQuery($sqlClientes, $paramClientes);
            foreach ($dataTableClientes as $resultadoclientes) {

                $codcliente = $resultadoclientes['id'];
                $nomecli = $resultadoclientes['nome'];
                $celular = $resultadoclientes['celular'];
            }
        }

        $sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
        $total = 0;
        $codpedido = 0;
        $qtdpedidos = 0;
        $totalfinal = 0;
        $valor = 0;
        while ($pedidos = mysqli_fetch_object($sqlPedido)) {
            $qtdpedidos = $qtdpedidos + (float) $pedidos->qtd;
            $valor = (float) $pedidos->valor;
            $totalfinal = $totalfinal + $valor;
        }



        $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($finpar = mysqli_fetch_object($sqlPar)) {
            $valortotalparcelas = $valortotalparcelas + (float) $finpar->valor;
            $diapag = $finpar->dia;
            $mespag = $finpar->mes;
            $anopag = $finpar->ano;
            $codparcela = $finpar->cod;
            $contadorparcelas++;
        }
        $areceber = 0;
        $areceber = number_format($valortotalfinanceiro2 - $valortotalparcelas, 2, ',', '.');
        $valortotalparcelas = number_format($valortotalparcelas, 2, ',', '.');

        $textostatus = "";
        if ($diapag == 0) {
            $textostatus = " Nenhuma Parcela Paga";
        }
        if ($numparcelas != $contadorparcelas) {
            echo "
									<tr style='text-align:center;'>
										<td>$codnota</td>
										<td>$nomecli</td>
										<td>$qtdpedidos</td>
										<td>$pagamentototal</td>
										<td>$valortotalparcelas</td>
										<td>$areceber</td>
										<td>$textopagamentotipo</td>
										<td><span style='color:red'>Crediário em Aberto.";
            if ($diapag != 0) {
                echo " 
                                                                                    Ultimo Pagamento:$diapag/$mespag/$anopag</span> ";
            } else {
                echo $textostatus;
            }
            echo "      </td>
										</td>
									</tr>
            ";
        }
    }
    echo "";
    ?>
</table>


<table class="table table-bordered" style="width: 90%; margin-left: 5%; margin-right: 5%;">  

    <tr>
        <td colspan="" style="text-align: center;">
            <b>TOTAL EM CREDIÁRIO</b>
        </td>
        <td colspan="" style="text-align: center;">
            <b>TOTAL A RECEBER</b>
        </td>
        <td colspan="" style="text-align: center;">
            <b>TOTAL RECEBIDO</b>
        </td>
       
    </tr>
    <tr>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($totalfinaldiatipopag4, 2, ',', '.') ?>
        </td>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($VALORTOTALARECEBER, 2, ',', '.') ?>
        </td>
        <td colspan="" style="text-align: center;">
            R$ <?= number_format($totalfinaldiatipopag4 - $VALORTOTALARECEBER, 2, ',', '.') ?>

        </td>
        
    </tr>
</table>

<table class="table table-bordered" style="width: 90%; margin-left: 5%; margin-right: 5%;">  

    <?php
    echo"
                                                                <tr style='text-align:center;'>
                                                                    <td colspan='3'><b>Entradas</b></td>
                                                                </tr>
                                                                <tr style='text-align:center;'>
                                                                    <td><b></b></td>
                                                                    <td><b>Qtd</b></td>
                                                                    <td><b>Valor Total</b></td>

                                                                </tr>

                                                                ";
    $cod_orgao = $_SESSION['cod_orgaoF'];
    $qtdtotalentfinal = 0;
    $qtdtotalsaifinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;
    for ($i = 1; $i <= 31; $i++) {
        $qtdtotalent = 0;
        $valortotalent = 0;
        $qtdtotalsai = 0;
        $valortotalsai = 0;
        $trueorfalsecat = 0;
        $trueorfalsecat = 1;

        $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE dia = $i AND mes = $mes_hoje AND ano = $ano_hoje ORDER BY cod ASC");
        while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
            $cod_entrada = $listent->cod_entrada;
            $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE cod = $cod_entrada ORDER BY cod ASC LIMIT 1");
            while ($ent = mysqli_fetch_object($sqlEntradas)) {
                $qtdtotalent = $qtdtotalent + $listent->qtd;
                $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;

                $valortotalent = $valortotalent + $listent->valor_total;
                $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
            }
        }
        $saldoqtd = $qtdtotalent;

        if ($saldoqtd != 0) {
            echo "
                                                                                                                                                                <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'>Dia " . $i . "</td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                             </tr>    ";
        }
    }
    $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;

    echo "                                                                           <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                             </tr>    ";

    echo "      
                                                                                                    ";
    echo "  
                                                                                                                           
                                                                     ";
    ?></table>
<table class="table table-bordered" style="width: 90%; margin-left: 5%; margin-right: 5%;">  

    <tr>
        <td colspan="7" style="text-align: center;">
            <b>Despesas</b>
        </td>
    </tr>


    <tr>
        <td colspan="2" style="text-align: center;">
            <b>Descrição Categoria</b>
        </td>
        <td colspan="2" style="text-align: center;">
            <b>Total</b>
        </td>
    </tr>

    <?php
    $totaldesp = 0;

    if ($ListaCategoriass != null) {
        foreach ($ListaCategoriass as $user4) {
            ?>
            <tr>
                <td colspan="2">
                    <?= $user4->getNome(); ?>  
                </td>
                <?php
                $cont = 0;
                $conttotal = 0;
                $totalfinal = 0;
                $listamovimentosPorDia = $movimentoEmpresaController->RetornarPorCategoriaDia(2, $dia_hoje, $mes_hoje, $ano_hoje, $user4->getId());

                if ($listamovimentosPorDia != null) {
                    foreach ($listamovimentosPorDia as $despesas) {
                        $total = 0;
                        $pontos = ',';
                        $result = str_replace($pontos, "", $despesas->getValor());
                        $valor_total = (float) $result;
                        $total = $valor_total;
                        $totalfinal = $totalfinal + $total;
                    }
                }

                $totaldesp = $totaldesp + $totalfinal;
                ?>
                <td colspan="3">R$ <?= number_format($totalfinal, 2, ',', '.') ?></td>
            </tr>
            <?php
            $totalfinal = 0;
            $cont = 0;
        }
    }
    ?>
    <tr>
        <th colspan="2" style="text-align: right;">Total Final:</th>
        <td colspan="2">R$ <?= number_format($totaldesp, 2, ',', '.') ?></td>
    </tr>
</table>    
<?php 

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