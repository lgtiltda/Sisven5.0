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
                
        $dia_hoje = $_GET['dia'];
        $mes_hoje = $_GET['mes'];
        $ano_hoje = $_GET['ano'];

$banco = new Banco();
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
                        <h4>Extrato Diário de Entradas  - <?php echo $dia_hoje.'/'. mostraMes($_GET['mes']).'/'.$ano_hoje ?></h4>
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
                
                
        echo"
            <table class='table table-bordered' style='width:100%; font-size:12pt;'>
                                                                <tr style='text-align:center;'>
                                                                    <td><b>Cod. Nota</b></td>
                                                                    <td colspan=''><b>nº Nota Fiscal</b></td>
                                                                    <td colspan=''><b>Funcionário</b></td>
                                                                    <td colspan=''><b>Qtd</b></td>
                                                                    <td colspan=''><b>Valor Total</b></td>
                                                                    
                                                                    
                                                                </tr>

                                                                ";
        $cod_orgao = $_SESSION['cod_orgaoF'];
        $qtdtotalentfinal = 0;
        $qtdtotalsaifinal = 0;
        $valortotalentfinal = 0;
        $valortotalsaifinal = 0;

        $saldoqtd = 0;
        $qtdtotalent = 0;
        $qtdtotalsai = 0;
        $valortotalent = 0;
        $qtdtotalentfinal = 0;
        $valortotalentfinal = 0;
        $valortotalsaifinal = 0;
        $saldoqtdfinal = 0;
        //  $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE dia = $dia_hoje AND mes = $mes_hoje AND ano = $ano_hoje AND ata_pregao = $codentrada ORDER BY cod ASC");
        $sqlEntradas = "SELECT * FROM entradas WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod ASC";
        $paramEntradas = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje
        );
        
        $dataTableEntradas = $banco->ExecuteQuery($sqlEntradas, $paramEntradas);
        foreach ($dataTableEntradas as $resultadoentradas) {
            $codentrada2 = $resultadoentradas['cod'];
            $notafiscal = $resultadoentradas['n_notafiscal'];
            $fornecedor = $resultadoentradas['fornecedor'];
            $cod_funcionario = $resultadoentradas['cod_funcionario'];
            //$sqlFornecedor = mysqli_query($conn, "SELECT * FROM fornecedores WHERE cod = $fornecedor ORDER BY cod ASC LIMIT 1");
            $nomefornecedor = "";
            $sqlFornecedores = "SELECT * FROM fornecedores WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
            $paramFornecedores = array(
                ":cod" => $fornecedor
            );
            $dataTableFornecedores = $banco->ExecuteQuery($sqlFornecedores, $paramFornecedores);
            foreach ($dataTableFornecedores as $resultadofornecedores) {
                $codfornecedor = $resultadofornecedores['cod'];
                $nomefornecedor = $resultadofornecedores['descricao'];
            }

            //$sqlFuncionario = mysqli_query($conn, "SELECT * FROM usuarios WHERE cod = $cod_funcionario ORDER BY cod ASC LIMIT 1");
            $nomefuncionario = "";
            $sqlUsu = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
            $paramUsu = array(
                ":cod" => $cod_funcionario
            );
            $dataTableUsu = $banco->ExecuteQuery($sqlUsu, $paramUsu);
            foreach ($dataTableUsu as $resultadousu) {
                $nomefuncionario = $resultadousu['nome'];
            }
            $qtdtotalent = 0;
            $valortotalent = 0;
            $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE cod_entrada = $codentrada2 ORDER BY cod ASC");
            while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
                $qtdtotalent = $qtdtotalent + $listent->qtd;
                $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;
                $valortotalent = $valortotalent + $listent->valor_total;
                $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
            }
            echo "                       <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:5%;'><b>$codentrada2</b>.</td>
                                                                                             <td>$notafiscal</td>
                                                                                                 
                                                                                             <td style='width:40%;'>$nomefuncionario</td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                          
                                                                              </tr>";
        }
        echo "<tr id='ResultadoValidacao888' style='text-align:center; '>
                                                                                            <td colspan='3' style='width:5%; text-align:rigth;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                           
                                                                                            </tr></table>";

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


