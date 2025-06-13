<?php
$erros = [];

require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Pagamento.php");
require_once("Model/PagParPro.php");
require_once ("Model/Servicos.php");

$Subtotal  = 0;
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

$cod = 0;
$nome = "";
$nascimento = "";
$rg = "";
$cpf = "";
$endereco = "";
$numero = "";
$complemento = "";
$celular = "";
$residencial = "";
$responsavel = "";
$indicacao = "";
$data = "";
$status = "";

$id = 0;
$cod_usu = 0;
$dentista_antes = "";
$reacao_anestesia = "";
$como = "";
$alergia_medicamento = "";
$qual = "";
$outras_alergias = "";
$doencas = "";
$outra_doenca = "";
$doenca_familia = "";
$medicamento = "";
$data2 = "";
$resultado = "";


$listaUsuariosBusca = [];

$termo = "";
$tipo = 4;
$status = 1;

$listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

$listaClientesBusca = [];
$listaNotas = [];
$listaPagamentos = [];
$listaPagParPro = [];

$id = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$tipo = 2;
$listaNotas = $orcamentoController->RetornarNotas($tipo, $id);


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
<?php
if ($listaNotas != null) {
    foreach ($listaNotas as $user4) {
        $cod_paciente2 = $user4->getUsuario();
        $o = $user4->getCod();
        $dia = $user4->getDia();
        $mes = $user4->getMes();
        $ano = $user4->getAno();
        $datapag = $dia."/".$mes."/".$ano;
        $valor_total = 0;
        if ($cod_paciente2 == 0) {
            $nomePaciente = $user4->getNomeCli();
        } else {
            $nomePaciente = $clienteController->RetornarNomeClientes($cod_paciente2);
        }
        ?>

        <?php
        $id2 = $user4->getCod();

//        $listaPagamentos = $movimentoClientesController->RetornarPagamentos3($id2);
              $listaSociosBusca = $clienteController->RetornarClientes("", 2, $cod_paciente2);
                if ($listaSociosBusca != null) {
                    foreach ($listaSociosBusca as $soc) {
                        $endereco = $soc->getEndereco();

                        $bairro = $soc->getBairro();
                        $numero = $soc->getNumero();
                        $complemento = $soc->getComplemento();
                        $celular = $soc->getCelular();
                    }
                }
                ?>
                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                        <h4>Orçamento</h4>
                        <div style="width: 100%; text-align: right; font-size: 14pt;">

                            <h3><b><?= $nome; ?></b></h3>

                            <h3>CNPJ:<b><?= $CNPJ; ?></b></h3>

                            <p style="font-size: 10pt;"><b>End:</b> <?= $rua; ?>, nº: <?= $numero; ?> - <?= $bairro; ?></br>
                                <b>E-mail</b>: <?= $email; ?></br>
                                <b>Celular</b>: <?= $celularem; ?></p>
                        </div>
                    </td>


                </table>
                <table class="table-borderless" style="width: 100%; float: left; font-size: 10pt; background-color: #CCC;">  


                    <tr style="">
                        <td scope="col" style="width: 40%;">
                            NOME CLIENTE: <B style="font-size: 10pt;"><?= retiraAcentos($nomePaciente); ?></B></BR>
                            ENDEREÇO: <B style="font-size: 10pt;"><?= $endereco; ?>,  nº:  <?= $numero; ?></B></BR>
                            BAIRRO: <B style="font-size: 10pt;"><?= $bairro; ?></B></BR>
                            COMPLEMENTO: <B style="font-size: 10pt;"><?= $complemento; ?></B></BR>
                            CONTATO: <B style="font-size: 10pt;"><?= $celular; ?> </B></BR>
                        
                        </td>



                        <td  style="width: 40%;">
                            COD. ORÇAMENTO: <B style="font-size: 10pt;">00<?= $_GET['cod']; ?></B>
                            </br>
                            DATA:  <B style="font-size: 10pt;"><?= $datapag; ?></B></BR>

                                </div>
                        </td>
                    </tr>
                </table>
                <table class="table table-borderless" style="width: 100%; float: left; font-size: 12pt;">  

                    <tr style="">
                        <td scope="col" style="width: 40%;">Item</td>
                        <td scope="col" STYLE="text-align: center">Qtd</td>
                        <td scope="col">Valor Unt.</td>
                        <td scope="col">Valor Total.</td>

                    </tr>
                    <?php
                    $Subtotal = 0;
                    $valorunt = 0;
                    $valortotal = 0;
                    $qtdtotal = 0;
                    $o = $id;
                    $cod_pac = $id;
                    $termo = "--";
                    $tipo = 1;
                    $status = $o;
                    $cont = 0;
                    $listaPedidos = $pedidosController->RetornarPedidos($termo, $tipo, $status);
                    // var_dump($listaProcedimentos); 
                    if ($listaPedidos != null) {
                        $listaPagParPro = $pagparproController->RetornarTodos($cod_pac);
                        ?>
                        <?php
                        foreach ($listaPedidos as $user10) {
                            $cont++;
                            $cod_procedimento3 = $user10->getCod();
                            $qtdpedidos = $user10->getQtd();
                            $valor = $user10->getValor();
                            $Subtotal = $Subtotal + $valor;
                            $valortotal = $valortotal + $valor;
                            $qtdtotal = $qtdtotal + $qtdpedidos;

                            $valorunt = $valor / $qtdpedidos;
                            $valorunt = number_format($valorunt, 2, ',', '.');
                            $string = $valor;
                            $stringCorrigida = str_replace(',', '', $string);
                            $valor = $stringCorrigida;
                            $valor = (float) $valor;
                            $valor = number_format($valor, 2, ',', '.');
                            $status = $user10->getStatus();
                            $obs = $user10->getObs();


                            $servicocod = $user10->getServico();

                            if ($user10->getStatus() == 1) {
                                $cor = "bg-dark";
                                $texto = "";
                            } else if ($user10->getStatus() == 2) {
                                $cor = "bg-primary";
                                $texto = "";
                            } else if ($user10->getStatus() == 3) {
                                $cor = "bg-success";
                                $texto = "Pago";
                            } else if ($user10->getStatus() == 0) {
                                $cor = "bg-danger";
                                $texto = "Cancelado";
                            } else {
                                $cor = "";
                                $text = "";
                            }

                            if ($servicocod != 0) {
                                $listaServicos = $servicoController->RetornarServicos2($servicocod);

                                if ($listaServicos != null) {
                                    foreach ($listaServicos as $user1) {
                                        $nome_s = $user1->getNome();
                                        $descricao = $user1->getDescricao();
                                        $valor_padrao = $user1->getValor();
                                    }
                                }
                            } else {
                                $nome_s = $obs;
                            }
                            ?>
                            <tr class="" style="padding: -10px;"  > 
                                <td style="padding-top: -10px;" ><?= $nome_s; ?></td>
                                <td style="text-align: center;"><?= $qtdpedidos ?></td>
                                <td >R$ <?= $valorunt; ?></td>
                                <td >R$ <?= $valor; ?></td>

                            </tr> 
                            <?php
                        }
                    }
               ?> </table> 
            ?>
        <!--      <tr class="">
        <td ></td>
        <td ></td>
        <td style="text-align: center;"><?= $qtdtotal ?></td>
        <td >R$  <?= number_format($valortotal, 2, ',', '.'); ?></td>


        </tr>-->
       
<table class="table table-borderless" style="width: 50%; float: right; font-size: 14pt;">
    <tr> <?php $Subtotal = number_format($Subtotal, 2, ',', '.');
                                     ?>
        <td>Subtotal:  R$ <?= $Subtotal; ?></td>
    </tr>
    <tr>
        <td>Total: R$ <?= $valor_total; ?></td>
    </tr>
</table>
</table>
<table class="table table-borderless" style="margin-top: 30px; width:100%; float: right; font-size: 14pt;">
    <tr> 
        <td style='text-align: center;'></br></br>_________________________________________</br>
            <?= $nomefunc = $usuarioController->RetornarNomeUsuarios($_SESSION['codF']);?>
        </td>
        
    </tr>
    <tr style="margin-top: 30px;">
          <td style='text-align: center;'></br></br></br>_________________________________________
              </br>
                  RECEBEDOR(A)
          </td>
    </tr>
   
</table>
        
        </div>
        </div>

        <?php
    }
}
?>

