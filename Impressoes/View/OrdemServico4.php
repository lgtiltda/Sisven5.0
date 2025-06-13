<?php
$erros = [];

require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Pagamento.php");
require_once("Model/PagParPro.php");
require_once ("Model/Servicos.php");


$numparcelas = "";
$Subtotal = 0;
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

$banco = new Banco();
date_default_timezone_set('America/Manaus');
$codnota22 = $id;
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

$troco = 0;
$sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
$paramTestePag = array(
    ":cod" => $codnota22
);
$nomecategoria = "Avulso";
$dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);
if ($dataTableTestePag != null) {


    foreach ($dataTableTestePag as $resultadonomecli) {
        $nomecli = $resultadonomecli['tipo'];
        if ($nomecli == 1) {
            $troco = $resultadonomecli['gorjeta'];
            if ($troco > 0) {
                $trocovalor = "Troco: R$ " . number_format($troco, 2, ',', '.');
            } else {
                $trocovalor = "";
            }
            $textopagamento = "PAGAMENTO REALIZADO EM DINHEIRO. $trocovalor";
        } else if ($nomecli == 2) {
            $tipag = $resultadonomecli['tipopag'];
            if ($tipag == 1) {
                $textopagamento = "PAGAMENTO REALIZADO NO CARTÃO.</h2>";
            } else if ($tipag == 2) {
                $pagamentototal = (float) $resultadonomecli['total'];
                $pagamentototal2 = $pagamentototal;
                $numparcelas = (float) $resultadonomecli['numparcelas'];
                $valorparcela = $pagamentototal / $numparcelas;
                $valorparcela = number_format($valorparcela, 2, ',', '.');
            }
            $textopagamento = "Pagamento Parcelado em $numparcelas" . "x" . " no Crediário ";
        }
    }
} else {

    $textopagamento = "";
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
        $datapag = $dia . "/" . $mes . "/" . $ano;
        $valor_total = 0;
        if ($cod_paciente2 == 0) {
            $nomecli = $user4->getNomeCli();
        } else {
            $nomecli = $clienteController->RetornarNomeClientes($cod_paciente2);
        }
        ?>

        <?php
        $id2 = $user4->getCod();

//        $listaPagamentos = $movimentoClientesController->RetornarPagamentos3($id2);
        $listaSociosBusca = $clienteController->RetornarClientes("", 2, $cod_paciente2);
        if ($listaSociosBusca != null) {
            foreach ($listaSociosBusca as $soc) {
                $endereco = $soc->getEndereco();
                $CPF = $soc->getCpf();

                $bairro = $soc->getBairro();
                $numero = $soc->getNumero();
                $complemento = $soc->getComplemento();
                $celular = $soc->getCelular();
            }
        }
        ?>
        <TABLE style='font-size:12pt; color:#000' class='table table-bordered striped centered highlight responsive-table'>
            <TR>
                <TD style='width: 40%;'>
                    <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>

                </TD>
                <Td>
                    <H1>CARNÊ CREDIÁRIO nº <?=$_GET['cod'];?>  </BR>     <b><?= $nome; ?> </br> CNPJ:<b><?= $CNPJ; ?></b></h3>


                            <p style="font-size: 10pt;"><b>End:</b> <?= $rua; ?>, nº: <?= $numero; ?> - <?= $bairro; ?></br>
                                <b>E-mail</b>: <?= $email; ?></br>
                                <b>Celular</b>: <?= $celularem; ?></p></H1>
                </TD>
            </TR>
        </TABLE>
        <?PHP
        
        $CONTADOR1 = 0;
        for ($i = 0; $i < $numparcelas; $i++) {
              $datapag;
            $CONTADOR1++;
           
            $DIAS = 30 * $CONTADOR1;
            $timestamp = strtotime("+30 days");
// Exibe o resultado
            $novadata = date($datapag, $timestamp);
            
            // Calcula a data daqui 3 dias
$timestamp = strtotime("+$CONTADOR1 month");
// Exibe o resultado
 $novadata = date('d/m/Y', $timestamp); // 27/03/2009 05:02
            ?>

            <TABLE style='font-size:12pt; color:#000' class='table table-bordered striped centered highlight responsive-table'>
                <TR>
                    <Td></br>
                        <b>Parcela nº <?= $CONTADOR1; ?> </b></br>
                        <small>Nome Cliente:</small> 
                        <b style='color:#000'><?= $nomecli ?></b> </br> 				
                        <small>CPF:</small> <b style='color:#000'><?= $CPF; ?></b> </br>				
                        <small>Endereço:</small> <b style='color:#000'><?= $endereco ?></b> / 				
                        <small>Nº:</small> <b style='color:#000'><?= $numero ?></b> / 				
                        <small>Bairro:</small> <b style='color:#000'><<?= $bairro; ?></b>  </br>				
                        <small>Complemento:</small> <b style='color:#000'><?= $complemento ?></b>  </br>				
                        <small>Contato:</small> <b style='color:#000'><?= $celular ?></b>				
                        </br><small>Tipo:</small> <b style='color:#000'><?= $textopagamento ?></b></br>
                        </br>
                    </td>
                    <Td></br>
                        VALOR PARCELA: <B> R$ <?= $valorparcela ?></B> </BR></BR>
                        VALOR PAGO:________________ </BR></BR>
                        VENCIMENTO:<b><?=$novadata?></b></br></br>
                        DATA :______/______/______</BR></BR>
        
                    </td>
                </TR>
            </TABLE>

            <?php
        }
        ?>
        </div>
        </div>

        <?php
    }
}
?>

