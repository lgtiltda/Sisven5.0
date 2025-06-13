<?php
$erros = [];

require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Pagamento.php");
require_once("Model/PagParPro.php");
require_once ("Model/Servicos.php");

$cpfcliente = "";
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
        $valor_total = 0;
        if ($cod_paciente2 == 0) {
            $nomePaciente = $user4->getNomeCli();
        } else {
            $nomePaciente = $clienteController->RetornarNomeClientes($cod_paciente2);
        }
        ?>

        <?php
        $id2 = $user4->getCod();

        $listaPagamentos = $movimentoClientesController->RetornarPagamentos3($id2);


        if ($listaPagamentos != null) {
            foreach ($listaPagamentos as $user5) {
                $datapag = $user5->getDia() . '/' . $user5->getMes() . '/' . $user5->getAno();

                $user5->getTotal();
                if ($user5->getTipo() == 1) {
                    $valor_total = $user5->getTotal();
                    $pontos = ",";
                    $result = str_replace($pontos, "", $valor_total);
                    $valor_total = (float) $result;
                    $valor_total = number_format($valor_total, 2, ',', '.');
                } else if ($user5->getTipo() == 2) {
                    $valor_total = $user5->getTotal();
                    $pontos = ",";
                    $result = str_replace($pontos, "", $valor_total);
                    $valor_total = (float) $result;
                    $qtdParcelas = (int) $user5->getNumparcelas();
                    $valor_total = $valor_total;

                    $valor_total = number_format($valor_total, 2, ',', '.');
                }

                $cod_pac = $user5->getCod();
                ?>             

                <?php
                $listaSociosBusca = $clienteController->RetornarClientes("", 2, $cod_paciente2);
                if ($listaSociosBusca != null) {
                    foreach ($listaSociosBusca as $soc) {
                        $endereco = $soc->getEndereco();

                        $bairro = $soc->getBairro();
                        $numero = $soc->getNumero();
                        $complemento = $soc->getComplemento();
                        $celular = $soc->getCelular();
                        $cpfcliente = $soc->getCpf();
                    }
                }
                ?>
                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                        <h4>Fatura</h4>
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
                            CPF: <B style="font-size: 10pt;"><?= $cpfcliente; ?></B></BR>
                            ENDEREÇO: <B style="font-size: 10pt;"><?= $endereco; ?>,  nº:  <?= $numero; ?></B></BR>
                            BAIRRO: <B style="font-size: 10pt;"><?= $bairro; ?></B></BR>
                            COMPLEMENTO: <B style="font-size: 10pt;"><?= $complemento; ?></B></BR>
                            CONTATO: <B style="font-size: 10pt;"><?= $celular; ?> </B></BR>
                            <B style="font-size: 10pt;"><?= $user5->getDescricao(); ?></B></BR>
                        </td>



                        <td  style="width: 40%;">
                            COD. FATURA: <B style="font-size: 10pt;">00<?= $_GET['cod']; ?></B>
                            </br>
                            DATA:  <B style="font-size: 10pt;"><?= $datapag; ?></B></BR>
                            TIPO DE PAGAMENTO:  <B style="font-size: 10pt;">
                                <?php
                                if ($user5->getTipo() == 1) {
                                    $valor_total = $user5->getTotal();
                                    $pontos = ",";
                                    $result = str_replace($pontos, "", $valor_total);
                                    $valor_total = (float) $result;
                                    $valor_total = number_format($valor_total, 2, ',', '.');
                                    $troco = $user5->getGorjeta();
                                    if ($user5->getTipopag() == 1) {
                                        echo "Á vista no Dinheiro. </br>Troco: R$ " . $troco;
                                    } else if ($user5->getTipopag() == 2) { 
                                        echo "Á vista no Cartão de Débito.";
                                    }else if ($user5->getTipopag() == 3) { 
                                        echo "Á vista no Pix.";
                                    }
                                } else if ($user5->getTipo() == 2) {
                                    $valor_total = $user5->getTotal();
                                    $pontos = ",";
                                    $result = str_replace($pontos, "", $valor_total);
                                    $valor_total = (float) $result;
                                    $qtdParcelas = (int) $user5->getNumparcelas();
                                    $valor_total = $valor_total;

                                    $valor_total = number_format($valor_total, 2, ',', '.');
                                    if ($user5->getTipopag() == 1) {
                                        echo "Parcelado no Cartão";
                                    } else {
                                        echo "Parcelado no Crediário em " . $qtdParcelas . "x.";
                                    }
                                }
                                ?>

                                </div>
                        </td>
                    </tr>
                </table>
                <table class="table table-borderless" style="width: 100%; float: left; font-size: 9pt;">  

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
                    $o = $user4->getCod();
                    $cod_pac = $user5->getCod();
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
                }
            }
            ?>
        <!--      <tr class="">
        <td ></td>
        <td ></td>
        <td style="text-align: center;"><?= $qtdtotal ?></td>
        <td >R$  <?= number_format($valortotal, 2, ',', '.'); ?></td>


        </tr>-->
        </table>
<table class="table table-borderless" style="width: 50%; float: right; font-size: 9pt;">
    <tr> <?php $Subtotal = number_format($Subtotal, 2, ',', '.');
                                     ?>
        <td>Subtotal:  R$ <?= $Subtotal; ?></td>
    </tr>
    <tr>
        <td>Total: R$ <?= $valor_total; ?></td>
    </tr>
<table class="table table-borderless" style="margin-top: 30px; width:100%; float: right; font-size: 10pt;">
    <tr> 
        <td style='text-align: center;'></br></br></br>_________________________________________</br>
            <?= $nomefunc = $usuarioController->RetornarNomeUsuarios($_SESSION['codF']);?>
        </td>
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

