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


$param1=$_GET['categoria'];

if($param1==0){
    $nomecategoria = "TODAS CATEGORIAS";
}else{
    $nomecategoria = $categoriaserfinController->RetornarNomeCat($param1);
}
?>      

                <table class="table table-borderless" style="margin-top:-20px; border: none;   width: 100%;">  
                    <td style="width:15%; text-align: center; ">
                        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%; border-left: none;"> </br>
                    </td>
                    <td colspan="" style="width: 30%; text-align: right; font-size: 14pt; ">
                        <h4>Saldo de Produtos Por Categoria - <?=$nomecategoria ?> </h4>
                        <div style="width: 100%; text-align: right; font-size: 14pt;">

                            <h3><b><?= $nome; ?></b></h3>

                            <h3>CNPJ:<b><?= $CNPJ; ?></b></h3>

                            <p style="font-size: 10pt;"><b>End:</b> <?= $rua; ?>, nº: <?= $numero; ?> - <?= $bairro; ?></br>
                                <b>E-mail</b>: <?= $email; ?></br>
                                <b>Celular</b>: <?= $celularem; ?></p>
                        </div>
                    </td>


                </table>

<table class="table table-light table-bordered">

    <?php
    Error_reporting(0);
    $banco = new Banco();

    $ordem = 0;

    $valortotalfinalporatasai = 0;
    $valortotalfinalporataqtd = 0;
    $codfornecedor2 = 0;
    ?>


    <tr style='text-align:center;'>
        <td><b>Ordem</b></td>
        <td><b>Cod</b></td>
        <td style='width: 60%;'><b>Produto</b></td>
        <td><b>Est máx</b></td>
        <td><b>Est. min.</b></td>
        <td colspan=''><b>Qtd</b></td>
        <td colspan=''><b>Valor Unt</b></td>
        <td colspan=''><b>Valor Total</b></td>
    </tr>



    <?php
    $valorunt=0;
    $valortotalporcat=0;
    $valortotalfinal=0;
    
    $truorfalsevalorfornecedor = 0;

    $qtdtotalentfinal = 0;
    $qtdtotalsaifinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;
    //$sqlCat = mysqli_query($conn, "SELECT * FROM categoria_produto WHERE cod_orgao = $cod_orgao ORDER BY cod ASC");
    $saldoqtd = 0;

    $qtdtotalent = 0;
    $valortotalent = 0;
    $qtdtotalsai = 0;
    $valortotalsai = 0;
    $trueorfalsecat = 0;
    $totalfinalporata = 0;
    $valortotalfinalporata = 0;

    $valortotalfinalporatasai = 0;

    $valortotalfinalporataqtd = 0;
    $disponivel = 0;
    //$sqlProdutos = mysqli_query($conn, "SELECT * FROM produtos ORDER BY cod ASC");
//while ($prod = mysqli_fetch_object($sqlProdutos)) {


    $categoria = $_GET['categoria'];

    if ($categoria == 0) {

        $sqlProdutos = "SELECT * FROM servicos WHERE tipo = 1 ORDER BY nome ASC";
        $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    } else {
        $sqlProdutos = "SELECT * FROM servicos WHERE categoria = :categoria AND tipo = 1 ORDER BY nome ASC";
        $paramProdutos = array(
            ":categoria" => $categoria
        );
        $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos, $paramProdutos);
    }

    foreach ($dataTableProdutos as $resultadoprodutos) {
        $disponivel = 0;
        $valorprod = 0;
        $trueorfalsecat = 1;
        $codproduto = $resultadoprodutos['cod'];
        $ordem ++;

        $codproduto = $resultadoprodutos['cod'];
        $nomeproduto = $resultadoprodutos['nome'];
        $codbuscaproduto = $resultadoprodutos['codbusca'];
        $codbarraproduto = $resultadoprodutos['codbarra'];
        $apresentacao = $resultadoprodutos['apresentacao'];
        $qtd = $resultadoprodutos['qtd'];
        $valorunt = $resultadoprodutos['valor'];
        $categoria = $resultadoprodutos['categoria'];
        $est_min = $resultadoprodutos['est_mim'];
        $est_max = $resultadoprodutos['est_max'];

        $sqlCat = "SELECT * FROM categoriaserfin WHERE cod = :categoria ORDER BY cod ASC LIMIT 1";
        $paramCat = array(
            ":categoria" => $categoria
        );

        $dataTableCat = $banco->ExecuteQuery($sqlCat, $paramCat);
        foreach ($dataTableCat as $resultadocat) {
            $nomecategoria = $resultadocat['nome'];
        }



        if ($apresentacao == 1) {
            $textoapresentacao = "Unidade";
        } else if ($apresentacao == 2) {
            $textoapresentacao = "Comprimido";
        } else if ($apresentacao == 3) {
            $textoapresentacao = "Ampola";
        } else if ($apresentacao == 4) {
            $textoapresentacao = "Frasco Ampola";
        } else if ($apresentacao == 5) {
            $textoapresentacao = "Frasco";
        } else if ($apresentacao == 6) {
            $textoapresentacao = "Caixa";
        } else if ($apresentacao == 7) {
            $textoapresentacao = "Pacote";
        } else if ($apresentacao == 8) {
            $textoapresentacao = "Kit";
        } else if ($apresentacao == 9) {
            $textoapresentacao = "Outros";
        }



        $totalporprodutoent = 0;
        $totalporprodutosaid = 0;
        $valortotalporprodutoent = 0;

        $saldoqtd = $qtdtotalent - $qtdtotalsai;

        $saldoqtd2 = 0;
        $saldovalor2 = 0;


        $saldoqtd2 = $totalporprodutoent - $totalporprodutosaid;
        $saldovalor2 = ($totalporprodutoent * $valorprod) - ($totalporprodutosaid * $valorprod);

    
    $valortotalporcat=$valorunt*$qtd;
    $valortotalfinal=$valortotalfinal+$valortotalporcat;
    if($qtd==null){
        $qtd = 0;
    }
        echo " 
                        <tr style='text-align:center;'>
                            <td>$ordem</td>
			    <td>$codproduto</td>
			    
                            <td>$nomeproduto </br>
                                <B>Cod. Busca:</b> $codbuscaproduto / <B>Cod. Barra:</b> $codbarraproduto
                                </td>
                            <td>$est_max</td>
                            <td>$est_min</td>
                          
                            <td colspan=''>" . $qtd . "</td>
                              <td>R$ 
                              




".number_format($valorunt, 2, ',', '.')."</td>
                            <td>R$ ".number_format($valortotalporcat, 2, ',', '.')."</td>
                                   </tr>

                                            ";
        $valortotalporcat = 0;
        $valortotalfinalporatasai = $valortotalfinalporatasai + ($totalporprodutosaid * $valorprod);
    }
    $valortotalporcat = 0;
     echo " 
                        <tr>
                          
                            <td colspan='7'>Total Final</td>
                            <td>R$ ".number_format($valortotalfinal, 2, ',', '.')."</td>
                                   </tr>

                                            ";
    
    $totalporprodutoent = 0;
    $totalporprodutosaid = 0;



    $valortotalfinalporata = 0;
    $totalfinalporata = 0;
    $valortotalfinalporatasai = 0;
    $valortotalfinalporataqtd = 0;



    $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;
    
    //         echo "
    //                                                                                                                                                   <tr id='ResultadoValidacao888' style='text-align:center; background-color:#337AB7; color:#fff;'>
    //                                                                        <td style='width:5%;'><b>Total Final</b>.</td>
    //                                                                        <td>$qtdtotalentfinal</td>
    //                                                                         <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
    //                                                                       <td>$qtdtotalsaifinal</td>
    //                                                                     <td>R$ " . number_format($valortotalsaifinal, 2, ',', '.') . "</td>
    //                                                                   <td>$saldoqtdfinal</td>
    //                                                                 <td>R$ " . number_format($valortotalentfinal - $valortotalsaifinal, 2, ',', '.') . "</td>
    //                                                                  </tr></table>    ";
    ?>
</table>

<?PHP
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


