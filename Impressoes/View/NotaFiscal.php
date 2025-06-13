<?php
require_once ("Controller/UsuariosController.php");
require_once ("Model/Usuarios.php");
require_once ("Controller/FornecedoresController.php");
require_once ("Model/Fornecedores.php");
require_once("Controller/ServicoController.php");
require_once("Model/Servicos.php");
require_once ("Controller/CategoriaSerFinController.php");
require_once ("Model/CategoriaSerFin.php");
require_once ("Controller/EntradasController.php");
require_once ("Model/Entradas.php");
require_once ("Controller/ListaEntradasController.php");
require_once ("Model/ListaEntradas.php");
require_once ("Controller/SaidasController.php");
require_once ("Model/Saidas.php");
require_once ("Controller/ListaSaidasController.php");
require_once ("Model/ListaSaidas.php");
require_once("Util/UploadFile.php");
require_once("Util/functions.php");

$usuarioController = new UsuarioController();
$fornecedoresController = new FornecedoresController();
$servicoController = new ServicoController();
$categoriasController = new CategoriaSerFinController();


$entradasController = new EntradasController();
$listaentradasControler = new ListaEntradasController();
$saidasController = new SaidasController();
$listasaidasControler = new ListaSaidasController();


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


$cod_entrada = 0;
$lote = "";
$mes_validade = "";
$ano_validade = "";
$qtd = 1;
$validade = "";
$valor_total = "";

$ListaCategorias = [];

$resultado = "";
$erros = [];

$codfornecedor = 0;

$cod_orgaoF = $_SESSION['cod_orgaoF'];
$ListaCategorias = $categoriasController->RetornarCategorias($cod_orgaoF);

$termo = "";
$tipo = 2;
$status = $_GET['cod'];
$listaentradas2 = $entradasController->RetornarEntradas($termo, $tipo, $status);
//var_dump($listaentradas);


$termo = "";
$tipo = 1;
$status2 = $_GET['cod'];
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
        $codfornecedor = (int) $entradas2->getFornecedor();
        $img = $entradas2->getImg();
        $status = $entradas2->getStatus();
    }
}



$termo = "";
$tipo = 4;
$status = 1;

$listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

if ($listaUsuariosBusca != null) {
    foreach ($listaUsuariosBusca as $user) {
        $nomeusu = $user->getNome();
        $email = $user->getEmail();
        $foto = $user->getFoto();
        $rua = $user->getRua();
        $bairro = $user->getBairro();
        $numero = $user->getNumero();
        $cpfusu = $user->getCpf();
        $celular = $user->getCelular();
        
        $enderecocompl = "<b></b>".$rua."<b>, nº:</b>".$numero."<b>, Bairro:</b>$bairro";
    }
}


$termo = "";
$tipo = 2;

$listafornecedor = $fornecedoresController->RetornarFornecedores($termo, $tipo, $codfornecedor);

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
$nomeata = (INT) 0;

if ($status == 1) {
    $textostatus = "Aberto";
} else {
    $textostatus = "Concluído";
}

$nomefunc = $usuarioController->RetornarNomeUsuarios($cod_funcionario);
$nomefunc2 = $nomefunc;
?>
<div class="row">
    <div class="col-12 col-md-12">
        <div class="card">
            <div class="card-body">
                
                
                <table class="table table-light table-bordered">

                    <tr>
                        <TD style="width:15%;;"><img style='width: 100%; margin-top:5px;' src="Interface/img/Usuarios/<?= $foto;?>" alt=""/>
                                </TD>
                        <td style="text-align: right;"><b>Empresa:</br>CNPJ:</BR>Endereço:</br>Fone:</br>Email:</b></td>
                        <td colspan='2'>        
                            <?= $nomeusu; ?>
                            </br><?= $cpfusu; ?>
                            </br><?= $enderecocompl; ?>
                            </br><?= $celular; ?>
                            </br><?= $email; ?>
                        </td>
                     
                    </tr>
                </table>
                <table class="table table-light table-bordered">
                    <tr style='text-align: center;'>
                        
                        <td colspan="4"><b>Extrato de Nota Fiscal</b></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Cod. Entrada</td>
                        <th><?= $cod_entrada; ?></th>
                        <td style="text-align: right;">Funcionário</td>
                        <th colspan="2"><?= ($nomefunc2); ?></th>
                    </tr>

                    <tr>
                        <td style="text-align: right;" colspan="">Nota Fiscal Nº</td>
                        <th colspan="4"> <?= $n_notafiscal; ?> </th>
                    </tr>
                </table>
                <h4 style='text-align: center;'>Lista de Produtos</h4>
                <table class="table table-light table-bordered">

                    <tr>
                       
                        <th colspan="2">Produto</th>
                        <th>Lote</th>
                        <th>Validade</th>
                        <th>Qtd</th>
                        <th>Valor Unt. de Compra</th>
                        <th>Total</th>
                    </tr>
                    <?php
                    $qtd_final = 0;
                    $valor_final = 0;
                    $valorunt ="";
                    $termo = "";
                    $tipo = 1;
                    $status = $cod_entrada;
                    $listaentradas3 = $listaentradasControler->RetornarListaEntradas($termo, $tipo, $status);
//var_dump($listaentradas3);
                    if ($listaentradas3 != NULL) {
                        foreach ($listaentradas3 as $entradas3) {
                            $ata_pregao = $entradas3->getAta_pregao();
                            $pontos = ',';
                            $result = str_replace($pontos, "", $entradas3->getValor_total());
                            $valor_total2 = $result;
                            $valor_total2 = (float) $valor_total2;
                            $valorunt = $entradas3->getValor_total() / $entradas3->getQtd();

                            $valor_total2 = number_format($valor_total2, 2, ',', '.');
                            $valorunt = number_format($valorunt, 2, ',', '.');
                            $codproduto = $entradas3->getCod_produto();
                            $nomeproduto = $servicoController->RetornarNomeServico($codproduto);
                            
                            
                            ?>
                            <tr>
                                
                                <td colspan="2">
                                    <?= $nomeproduto; ?>
                                </td>
                                <td>
                                    <?= $entradas3->getLote(); ?>
                                </td>
                                <td>
                                    <?= $entradas3->getMes_validade(); ?>/<?= $entradas3->getAno_validade(); ?>
                                </td>
                                <td>
                                    <?= $entradas3->getQtd(); ?>
                                </td>
                                <td>R$ <?= $valorunt;?></td>
                                <td>
                                    R$ <?= $valor_total2; ?>
                                </td>

                            </tr>

                            <?php
                            $pontos = ',';
                            $result = str_replace($pontos, "", $entradas3->getValor_total());
                            $valor = (float) $result;
                            $valor_final = $valor_final + $valor;
                            $qtd_final = $qtd_final + $entradas3->getQtd();
                        }
                    }
                    ?>
                    <tr>
                        <td colspan="4" style="text-align: right;">Totais</td>
                        <th colspan='2'><?= $qtd_final; ?></th>
                        
                        <th> R$ <?= number_format($valor_final, 2, ',', '.'); ?></th>
                    </tr>
                    <tr>
                        <td colspan="8" style="text-align: center;">
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
                        <td colspan="6" style="text-align: right;">Data</td>
                        <th colspan="2"><?= $dia; ?>/<?= $mes; ?>/<?= $ano; ?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


