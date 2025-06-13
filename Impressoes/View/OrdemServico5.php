<?php
$erros = [];

include("conn.php");
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

$id = filter_input(INPUT_GET, "codnota", FILTER_SANITIZE_NUMBER_INT);
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
                
                $contadorlogicoparcelas = 0;
                $contadorvalorparcelas =0;
                $descricaoparcela = "";
                $codpagamento = $_GET['codrecibo'];
                  $sqlParcelas = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE cod = " . $codpagamento . " ORDER BY cod ASC");
                while ($parcelaspesquisa = mysqli_fetch_object($sqlParcelas)) {
                    $contadorlogicoparcelas++;
                    $contadorvalorparcelas = $contadorvalorparcelas + $parcelaspesquisa->valor;
                    $valorparcela = $parcelaspesquisa->valor;
                    $codparcela = $parcelaspesquisa->cod;
                    $valorparcela = number_format($valorparcela, 2, ',', '.');
                    $descricaoparcela = $parcelaspesquisa->descricao;
                    $data = $parcelaspesquisa->dia . "/" . $parcelaspesquisa->mes . "/" . $parcelaspesquisa->ano;
                }
                ?>
                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 9pt; ">
                        <B style="font-size: 10pt;">Recibo Crediário nº <?=$_GET['codnota'];?></B></BR>
                        NOME CLIENTE: <B style="font-size: 10pt;"><?= retiraAcentos($nomePaciente); ?></B></BR>
                            CPF: <B style="font-size: 10pt;"><?= $cpfcliente; ?></B></BR>
                            CONTATO: <B style="font-size: 10pt;"><?= $celular; ?> </B></BR>
                            <B style="font-size: 10pt;"><?= $user5->getDescricao(); ?></B></BR>
                          
                                </br></br>_________________________________________</br>
            <?= $nomefunc = $usuarioController->RetornarNomeUsuarios($_SESSION['codF']);?>
        						

                          
                    </td>
                    <td style='width: 30%;'>
                          COD. FATURA: <B style="font-size: 10pt;">00<?= $_GET['codnota']; ?></B>
                            </br>
                            TIPO DE PAGAMENTO:  <B style="font-size: 10pt;">
                                <?php
                                if ($user5->getTipo() == 1) {
                                    $valor_total = $user5->getTotal();
                                    $pontos = ",";
                                    $result = str_replace($pontos, "", $valor_total);
                                    $valor_total = (float) $result;
                                    $valor_total = number_format($valor_total, 2, ',', '.');
                                    if ($user5->getTipopag() == 1) {
                                        echo "Á vista no Dinheiro. </br>Troco: R$ " . number_format($user5->getGorjeta(), 2, ',', '.');
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
                            </b>
                                
                        </br><b><?=$descricaoparcela; ?></b>
                            </BR>
                            VALOR PAGO:<b> R$ <?=$valorparcela; ?></b>
                            </BR>
                            DATA DE PAGAMENTO:<B><?= $data;?></B>
                        
                    </td>


                </table>

   <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 9pt; ">
                        <B style="font-size: 10pt;">Recibo Crediário nº <?=$_GET['codnota'];?></B></BR>
                        NOME CLIENTE: <B style="font-size: 10pt;"><?= retiraAcentos($nomePaciente); ?></B></BR>
                            CPF: <B style="font-size: 10pt;"><?= $cpfcliente; ?></B></BR>
                            CONTATO: <B style="font-size: 10pt;"><?= $celular; ?> </B></BR>
                            <B style="font-size: 10pt;"><?= $user5->getDescricao(); ?></B></BR>
                          
                                </br></br>_________________________________________</br>
            <?= $nomefunc = $usuarioController->RetornarNomeUsuarios($_SESSION['codF']);?>
        						

                          
                    </td>
                    <td style='width: 30%;'>
                          COD. FATURA: <B style="font-size: 10pt;">00<?= $_GET['codnota']; ?></B>
                            </br>
                            TIPO DE PAGAMENTO:  <B style="font-size: 10pt;">
                                <?php
                                if ($user5->getTipo() == 1) {
                                    $valor_total = $user5->getTotal();
                                    $pontos = ",";
                                    $result = str_replace($pontos, "", $valor_total);
                                    $valor_total = (float) $result;
                                    $valor_total = number_format($valor_total, 2, ',', '.');
                                    if ($user5->getTipopag() == 1) {
                                        echo "Á vista no Dinheiro. </br>Troco: R$ " . number_format($user5->getGorjeta(), 2, ',', '.');
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
                            </b>
                                
                        </br><b><?=$descricaoparcela; ?></b>
                            </BR>
                            VALOR PAGO:<b> R$ <?=$valorparcela; ?></b>
                            </BR>
                            DATA DE PAGAMENTO:<B><?= $data;?></B>
                        
                    </td>


                </table>
            <?php }?>
            <?php }?>
        
        </div>
        </div>

        <?php
    }
}
?>

