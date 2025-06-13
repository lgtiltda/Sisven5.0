
<?php
$erros = [];

require_once("Model/Clientes.php");
require_once("Controller/ClientesController.php");

require_once("Model/Usuarios.php");
require_once("Controller/UsuariosController.php");

include("Action/conn.php");
include("Action/functions.php");


Error_reporting(0);
$banco = new Banco();

$clienteController = new ClientesController();
$usuarioController = new UsuarioController();

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
$usuario = "";
$rg = "";
$cpf = "";
$email = "";
$foto = "";
$permissao = 0;
$rua = "";
$bairro = "";
$numero = "";
$celular = "";
$senha = "";

$resultado = "";

$listaUsuariosBusca = [];

$termo = "";
$tipo = 4;
$status = 1;

$listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

//var_dump($listaUsuariosBusca);
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
$resultado = "";
$listaClientesBusca = [];

$id = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$tipo = 1;
if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
    $termo = "";
    $status = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
    $tipo = 2;
    $listaClientesBusca = $clienteController->RetornarClientes($termo, $tipo, $status);
//var_dump($listaClientesBusca);
}

$listaSociosBusca = $clienteController->RetornarClientes("", 2, $id);
if ($listaSociosBusca != null) {
    foreach ($listaSociosBusca as $soc) {
        $nomePaciente = $soc->getNome();
        $endereco = $soc->getEndereco();
        $bairro = $soc->getBairro();
        $numero = $soc->getNumero();
        $complemento = $soc->getComplemento();
        $celular = $soc->getCelular();
    }
}

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
?>
<hr> 

<table class="table table-bordered" style="margin-top:-20px;   width: 100%;">  
    <td style="width:15%; text-align: center;">
        <img src="Interface/img/Usuarios/<?= $foto; ?>" class="img-fluid" style="width: 100%;"> </br>
    </td>
    <td colspan="" style="width: 30%; text-align: left; font-size: 10pt;">
        <div style="width: 100%; text-align: left;">

            <P style="font-size: 9pt;"><b><?= $nome; ?></b></P>

            <p style="font-size: 9pt;"><b>End:</b> <?= $rua; ?>/ <b>nº:</b> <?= $numero; ?>. <b>Bairro:</b> <?= $bairro; ?></b></br>
                <b>E-mail</b>: <?= $email; ?></p>
        </div>
    </td>

    <td colspan="4" style="text-align: left; margin-left: 10px; font-size: 9pt;">
        Cliente: <b><?= retiraAcentos($nomePaciente); ?> </b> </br>
        Endereço: <b><?= $endereco; ?></b> nº: <b> <?= $numero; ?></b></br>
        Bairro: <b><?= $bairro; ?></b> </br>
        Complemento: <?= $complemento; ?></br>    
        Contato: <?= $celular; ?>    

    </td>

</table>
<?php
$codcliente = $id;







$codcliente = $id;
$contasareceber = 0;
$contaspagas = 0;
$sqlNotasMes = mysqli_query($conn, "SELECT * FROM notas WHERE usuario = $codcliente AND status = 3 ORDER BY cod ASC");
// Exibe todos os valores encontrados
while ($notasmes = mysqli_fetch_object($sqlNotasMes)) {
    $codnota = $notasmes->cod;
    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota AND tipo = 2 AND tipopag = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    $valortotalfinanceiro2 = 0;

    while ($financeirocli = mysqli_fetch_object($sql)) {
        $contadorparcelas = 0;
        $codfin = $financeirocli->cod;
        $codnota = $financeirocli->cod_orcamento;
        $valortotalfinanceiro = (float) $financeirocli->total;
        $valortotalfinanceiro2 = (float) $financeirocli->total;
        $diaatual = date('d');
        $anoatual = date('Y');
        $mesatual = date('m');
        $diapag = 0;
        $mespag = 0;
        $anopag = 0;
        $valortotalparcelas = 0;
        $codparcela = 0;

        $troco = $financeirocli->gorjeta;
        $pagamentototal = (float) $financeirocli->total;
        $pagamentototal2 = $pagamentototal;
        $numparcelas = (float) $financeirocli->numparcelas;
        $valorparcela = $pagamentototal / $numparcelas;
        $valorparcela = number_format($valorparcela, 2, ',', '.');
        $valorparcela2 = $valorparcela;
        $pagamentototal = number_format($pagamentototal, 2, ',', '.');
        $tipopag1 = $financeirocli->tipo;
        $tipopag2 = $financeirocli->tipopag;

        if ($financeirocli->tipo == 1) {// a vista
            if ($financeirocli->tipopag == 1) {// dinheiro
                $textopagamentotipo = "Pagamento á Vista no Dinheiro";
            } else {//debito
                $textopagamentotipo = "Pagamento á Vista no Débito";
            }
        } else {//parcelado
            if ($financeirocli->tipopag == 1) {// credito
                $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Cartão de Crédito";
            } else {//crediario
                $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Crediário";
            }
        }

        //$sqlNota = mysqli_query($conn, "SELECT * FROM notas WHERE cod = " . $codnota . " ORDER BY cod ASC LIMIT 1");
        $sqlNotas = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramNotas = array(
            ":cod" => $codnota
        );

        $dataTableNotas = $banco->ExecuteQuery($sqlNotas, $paramNotas);
        foreach ($dataTableNotas as $resultadonotas) {

            $usuarionota = $resultadonotas['usuario'];
            $nomecli = $resultadonotas['nomeCli'];
            //$sqlNomecli = mysqli_query($conn, "SELECT * FROM clientes WHERE id = $usuarionota ORDER BY id ASC LIMIT 1");
            $sqlClientes = "SELECT * FROM clientes WHERE id = :cod ORDER BY id ASC LIMIT 1";
            $paramClientes = array(
                ":cod" => $usuarionota
            );

            $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
            foreach ($dataTableClientes as $resultadoclientes) {

                $codcliente = $resultadoclientes['id'];
                $nomecli = $resultadoclientes['nome'];
                $celular = $resultadoclientes['celular'];
            }
        }

        $sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
        $total = 0;
        $codpedido = 0;
        $qtdpedidos = 0;
        $totalfinal = 0;
        $valor = 0;
        while ($pedidos = mysqli_fetch_object($sqlPedido)) {
            $qtdpedidos = $qtdpedidos + (float) $pedidos->qtd;
            $valor = (float) $pedidos->valor;
            $totalfinal = $totalfinal + $valor;
        }



        $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($finpar = mysqli_fetch_object($sqlPar)) {
            $valortotalparcelas = $valortotalparcelas + (float) $finpar->valor;
            $diapag = $finpar->dia;
            $mespag = $finpar->mes;
            $anopag = $finpar->ano;
            $codparcela = $finpar->cod;
            $contadorparcelas++;
        }


        $contasareceber = $contasareceber + ($valortotalfinanceiro2 - $valortotalparcelas);

        $areceber = 0;
        $areceber = number_format($valortotalfinanceiro2 - $valortotalparcelas, 2, ',', '.');
        $valortotalparcelas = number_format($valortotalparcelas, 2, ',', '.');

        $textostatus = "";
        if ($diapag == 0) {
            $textostatus = " Nenhuma Parcela Paga";
        }
        if ($numparcelas != $contadorparcelas) {
            //        echo "
            //<td>$textopagamentotipo</td>
            //									<td><span style='color:red'>Crediário em Aberto.";
            //if ($diapag != 0) {
            //  echo "Ultimo Pagamento:$diapag/$mespag/$anopag</span> ";
            //} else {
            //     echo $textostatus;
            //  }
            //        echo "      </td>
            //	<td> <a href='javascript: func' onclick='PagVerTudo(10, $codnota, 44)' class='btn btn-primary btn-sm btn-block' style='color:#fff;'><span class='glyphicon glyphicon-play'></span> Ver Tudo </a></td>
            //</tr>
            //   ";
        }
    }


    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    $valortotalfinanceiro2 = 0;

    while ($financeirocli = mysqli_fetch_object($sql)) {
        $contadorparcelas = 0;
        $codfin = $financeirocli->cod;
        $codnota = $financeirocli->cod_orcamento;
        $valortotalfinanceiro = (float) $financeirocli->total;
        $contaspagas = $contaspagas + $valortotalfinanceiro;
        $valortotalfinanceiro2 = (float) $financeirocli->total;
        $diaatual = date('d');
        $anoatual = date('Y');
        $mesatual = date('m');
        $diapag = 0;
        $mespag = 0;
        $anopag = 0;
        $valortotalparcelas = 0;
        $codparcela = 0;

        $troco = $financeirocli->gorjeta;
        $pagamentototal = (float) $financeirocli->total;
        $pagamentototal2 = $pagamentototal;
        $numparcelas = (float) $financeirocli->numparcelas;
        $valorparcela = $pagamentototal / $numparcelas;
        $valorparcela = number_format($valorparcela, 2, ',', '.');
        $valorparcela2 = $valorparcela;
        $pagamentototal = number_format($pagamentototal, 2, ',', '.');
        $tipopag1 = $financeirocli->tipo;
        $tipopag2 = $financeirocli->tipopag;

        if ($financeirocli->tipo == 1) {// a vista
            if ($financeirocli->tipopag == 1) {// dinheiro
                $textopagamentotipo = "Pagamento á Vista no Dinheiro";
            } else {//debito
                $textopagamentotipo = "Pagamento á Vista no Débito";
            }
        } else {//parcelado
            if ($financeirocli->tipopag == 1) {// credito
                $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Cartão de Crédito";
            } else {//crediario
                $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Crediário";
            }
        }

        //$sqlNota = mysqli_query($conn, "SELECT * FROM notas WHERE cod = " . $codnota . " ORDER BY cod ASC LIMIT 1");
        $sqlNotas = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramNotas = array(
            ":cod" => $codnota
        );

        $dataTableNotas = $banco->ExecuteQuery($sqlNotas, $paramNotas);
        foreach ($dataTableNotas as $resultadonotas) {

            $usuarionota = $resultadonotas['usuario'];
            $nomecli = $resultadonotas['nomeCli'];
            //$sqlNomecli = mysqli_query($conn, "SELECT * FROM clientes WHERE id = $usuarionota ORDER BY id ASC LIMIT 1");
            $sqlClientes = "SELECT * FROM clientes WHERE id = :cod ORDER BY id ASC LIMIT 1";
            $paramClientes = array(
                ":cod" => $usuarionota
            );

            $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
            foreach ($dataTableClientes as $resultadoclientes) {

                $codcliente = $resultadoclientes['id'];
                $nomecli = $resultadoclientes['nome'];
                $celular = $resultadoclientes['celular'];
            }
        }

        $sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
        $total = 0;
        $codpedido = 0;
        $qtdpedidos = 0;
        $totalfinal = 0;
        $valor = 0;
        while ($pedidos = mysqli_fetch_object($sqlPedido)) {
            $qtdpedidos = $qtdpedidos + (float) $pedidos->qtd;
            $valor = (float) $pedidos->valor;
            $totalfinal = $totalfinal + $valor;
        }



        $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($finpar = mysqli_fetch_object($sqlPar)) {
            $valortotalparcelas = $valortotalparcelas + (float) $finpar->valor;
            $diapag = $finpar->dia;
            $mespag = $finpar->mes;
            $anopag = $finpar->ano;
            $codparcela = $finpar->cod;
            $contadorparcelas++;
        }
        $areceber = 0;
        $areceber = number_format($valortotalfinanceiro2 - $valortotalparcelas, 2, ',', '.');
        $valortotalparcelas = number_format($valortotalparcelas, 2, ',', '.');

        $textostatus = "";
        if ($diapag == 0) {
            $textostatus = " Nenhuma Parcela Paga";
        }
        if ($numparcelas != $contadorparcelas) {
            
        }
    }
}

$contaspagas = number_format($contaspagas - $contasareceber, 2, ',', '.');
$contasareceber = number_format($contasareceber, 2, ',', '.');

echo "<h4 style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; '>Contas a receber: R$ $contasareceber<span class='blog-post-meta'></h4>
        ";
echo "<h4 style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; '>Contas Pagas: $contaspagas<span class='blog-post-meta'></h4>
        ";
?>