<?php


include("conn.php");
include("functions.php");

require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Pagamento.php");
require_once("Model/PagParPro.php");
require_once ("Model/Servicos.php");


require_once("Controller/ClientesController.php");
require_once("Controller/NotasController.php");
require_once("Controller/PedidosController.php");
require_once("Controller/MovimentoClientesController.php");
require_once("Controller/PagParProController.php");
require_once("Controller/ServicoController.php");
require_once("Model/Usuarios.php");
require_once("Controller/UsuariosController.php");

$usuarioController = new UsuarioController();

$clienteController = new ClientesController();
$orcamentoController = new NotasController();
$movimentoClientesController = new MovimentoClientesController();
$pagparproController = new PagParProController();
$pedidosController = new PedidosController();
$servicoController = new ServicoController();

$listaUsuariosBusca = [];

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
        $celularem = $user->getCelular();
        $CNPJ = $user->getCpf();
    }
}
?>
<div class="row">
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-body">
                    
                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                        <h4>Extrato Mensal de Entradas  - <?= mostraMes($_GET['mes']);?>/<?= $_GET['ano'];?></h4>
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
                set_time_limit(500);
        $totalfinal = 0;
        $ata_pregao = 1;
        $qtdentradas = 0;

        $codentrada = 0;

        $dia_hoje = $_GET['dia'];
        $mes_hoje = $_GET['mes'];
        $ano_hoje = $_GET['ano'];


        

        echo"<table class='table table-bordered' style='width:100%; font-size:12pt;'>
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
                                                                                             </tr></table>    ";

        echo "      
                                                                                                    ";
        echo "  
                                                                                                                            </table>
                                                                        ";



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
            </div>
        </div>
    </div>
</div>


