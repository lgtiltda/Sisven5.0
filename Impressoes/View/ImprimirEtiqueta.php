<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
.container-etiquetas{
    text-align:center;
    display:flex;
    flex-wrap:wrap;
}

.etiqueta{
    margin:0.5%;
    border:1px solid #000;
    text-align:center;
    box-sizing:border-box;
}

/* Impressão */
@media print {

    .container-etiquetas{
        display:block;
    }

    .etiqueta{
        float:left;
        margin:0.5%;
        page-break-inside: avoid;
    }

}
</style>

<?php
$erros = [];

include("Action/conn.php");
require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Pagamento.php");
require_once("Model/PagParPro.php");
require_once ("Model/Servicos.php");
require_once ("Model/MovimentoClientes.php");

$cpfcliente = "";

require_once("Controller/ClientesController.php");
require_once("Controller/NotasController.php");
require_once("Controller/PedidosController.php");
require_once("Controller/MovimentoClientesController.php");
require_once("Controller/PagParProController.php");
require_once("Controller/ServicoController.php");
require_once("Model/Usuarios.php");
require_once("Controller/UsuariosController.php");

include_once(__DIR__ . "/../../Action/conn.php");

require_once __DIR__ . '/../../vendor/autoload.php';


use Picqer\Barcode\BarcodeGeneratorPNG;

$generator = new BarcodeGeneratorPNG();


$banco = new Banco();


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

$Textopagamento="";
$listaUsuariosBusca = [];

$termo = "";
$tipo = 4;
$status = 1;




 $tipobusca = $_GET['tipoetiqueta'];
    $qtdetiqueta = $_GET['qtdetiqueta'];
    $codservico = $_GET['codservico'];

    $codbarra = $_GET['codbarra'];
    $codbusca = $_GET['codbusca'];



    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM servicos WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $tiposervico = $resultadoservicos['tipo'];
      $catservico = $resultadoservicos['categoria'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];
      $cod_buscaservico = (int) $resultadoservicos['codbusca'];
      $categoriaservico = (float) $resultadoservicos['categoria'];
      //$sqlCategorias = mysqli_query($conn, "SELECT * FROM categoriaserfin WHERE cod = $categoriaservico ORDER BY cod ASC LIMIT 1");
      // Exibe todos os valores encontrados
      $sqlCat = "SELECT * FROM categoriaserfin WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCat = array(
        ":cod" => $categoriaservico
      );

      $dataTableCat = $banco->ExecuteQuery($sqlCat, $paramCat);
      foreach ($dataTableCat as $resultadocat) {
        $nomecategoria = $resultadocat['nome'];
      }
      $nomeservico = $resultadoservicos['nome'];
      $descricaoservico = $resultadoservicos['descricao'];
      $valorunt = number_format($resultadoservicos['valor'], 2, ',', '.');
      $img = $resultadoservicos['img'];
      if ($img == null) {
        $img = "imagem mostrar pedido sem foto";
      } else {
        $img = "Interface/img/Servicos/$img";
      }

      $contadorindex = 4;




      $tiposervico = $resultadoservicos['tipo'];
      $qtdservico = $resultadoservicos['qtd'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];
      $cod_buscaservico = $resultadoservicos['codbusca'];
      $valorservico = $resultadoservicos['valor'];
      $nome = $resultadoservicos['nome'];

    }
    $codigoImagem = 0;


      if($cod_barraservico==null){
        $cod_barraservico=0;
      }




    if ($tipobusca == 1) {
      $codigoImagem = '<img src="data:image/png;base64,' .
        base64_encode(
          $generator->getBarcode(
            $cod_buscaservico,
              $generator::TYPE_CODE_128
          )
        ) . '">';
    } else {
      $codigoImagem = '<img src="data:image/png;base64,' .
        base64_encode(
          $generator->getBarcode(
            $cod_barraservico,
              $generator::TYPE_CODE_128
          )
        ) . '">';
    }



    echo "<div class='container-etiquetas'></br></br>";

for ($i = 1; $i <= $qtdetiqueta; $i++) {

    echo "
    <div class='etiqueta' style='width:30%;'>

        <div style='font-size:6pt;'>
            {$nome} - R$ {$valorservico}
        </div>

        {$codigoImagem}

        <div style='font-size:6pt;'>";
        
        echo ($tipobusca == 1)
            ? $cod_buscaservico
            : $cod_barraservico;

    echo "
        </div>

    </div>";
}

echo "</div>";
