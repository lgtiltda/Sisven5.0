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
                        <h4>Faturamento Anual - <?= $_GET['ano']; ?> </h4>
                        <div style="width: 100%; text-align: right; font-size: 14pt;">

                            <h3><b><?= $nome; ?></b></h3>

                            <h3>CNPJ:<b><?= $CNPJ; ?></b></h3>

                            <p style="font-size: 10pt;"><b>End:</b> <?= $rua; ?>, nº: <?= $numero; ?> - <?= $bairro; ?></br>
                                <b>E-mail</b>: <?= $email; ?></br>
                                <b>Celular</b>: <?= $celularem; ?></p>
                        </div>
                    </td>


                </table>
<table class="table table-bordered" style="width: 100%; ">  

    <tr>
        <td colspan="8" style="text-align: center;">
            <b>Faturamento - Resumo</b>
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
    $cod_usu = 1;
    $totalfinaldiatipopag1 = 0;
    $totalfinaldiatipopag2 = 0;
    $totalfinaldiatipopag3 = 0;
    $totalfinaldiatipopag4 = 0;
    $totalfinaldiatipopag5 = 0;
    $listaNotasDia = $notasController->RetornarNotasDiaMesAnoF(4, 1, $dia_hoje, $mes_hoje, $ano_hoje);
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
        <td style="text-align: center;"><b>R$ <?= number_format($totalfinaldia, 2, ',', '.') ?></b></td>
        <td>R$ <?= number_format($totalfinaldiatipo1, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipo2, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipopag1, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipopag2, 2, ',', '.') ?></td>
        <td> R$ <?= number_format($totalfinaldiatipopag5, 2, ',', '.') ?></td>
        <td > R$ <?= number_format($totalfinaldiatipopag3, 2, ',', '.') ?></td>
        <td > R$ <?= number_format($totalfinaldiatipopag4, 2, ',', '.') ?></td>
    </tr>
    <tr style="text-align: center;">
        <td rowspan="2">
            </br>
            Mês
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
    for ($i = 1; $i <= 12; $i++) {
        $totalfinaldiatipo1 = 0;
        $totalfinaldiatipo2 = 0;
        $totalfinaldiatipopag1 = 0;
        $totalfinaldiatipopag2 = 0;
        $totalfinaldiatipopag3 = 0;
        $totalfinaldiatipopag4 = 0;
        $totalfinaldiatipopag5 = 0;

        $listaNotasDia2 = $notasController->RetornarNotasDiaMesAnoF(3, 1, 0, $i, $ano_hoje);
        if ($listaNotasDia2 != null) {
            foreach ($listaNotasDia2 as $user4) {
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
                            }else if ($user5->getTipopag() == 3) {
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
        if ($listaNotasDia2 != null) {
            ?>

            <tr>
                <td style="text-align: center;"><b><?= mostraMes($i);?></b></td>
                <td>R$ <?= number_format($totalfinaldiatipo1, 2, ',', '.') ?></td>
                <td> R$ <?= number_format($totalfinaldiatipo2, 2, ',', '.') ?></td>
                <td> R$ <?= number_format($totalfinaldiatipopag1, 2, ',', '.') ?></td>
                <td> R$ <?= number_format($totalfinaldiatipopag2, 2, ',', '.') ?></td>
                <td> R$ <?= number_format($totalfinaldiatipopag5, 2, ',', '.') ?></td>
                <td > R$ <?= number_format($totalfinaldiatipopag3, 2, ',', '.') ?></td>
                <td > R$ <?= number_format($totalfinaldiatipopag4, 2, ',', '.') ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <td colspan="8" style="text-align: center;">
            <b>Faturamento por Funcionário</b>
        </td>
    </tr>

    <?php
    $termo = "";
    $tipo = 1;
    $status = 1;

    $listaUsuariosBusca2 = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

    if ($listaUsuariosBusca2 != null) {
        foreach ($listaUsuariosBusca2 as $usu) {
            ?>
            <tr>
                <th>Funcionário:</th>
                <td colspan="3">
                    <?= $usu->getNome(); ?> 
                </td>
                <th>Total:</th>
                <?php
                $totalusu = 0;
                $filtro = $usu->getCod();
                $listaNotas = $notasController->RetornarNotasDiaMesAno(6, $filtro, $dia_hoje, $mes_hoje, $ano_hoje);


                if ($listaNotas != null) {
                    foreach ($listaNotas as $user4) {
                        $cod_func = $user4->getFunc();
                        $listaPagamentos = $movimentoClienteController->RetornarPagamentos4($user4->getCod());
                        if ($listaPagamentos != null) {
                            foreach ($listaPagamentos as $user5) {

                                $parcelas1 = $user5->getNumparcelas();
                                $pontos = ',';
                                $result = str_replace($pontos, "", $user5->getTotal());
                                $valor_total = (float) $result;
                                $total = $valor_total;

                                $totalusu = $totalusu + $total;
                            }
                        }
                    }
                }
                ?>
                <td colspan="3">R$ <?= number_format($totalusu, 2, ',', '.') ?></td>

            </tr>
            <?php
            $totalusu = 0;
        }
    }
    ?>

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




