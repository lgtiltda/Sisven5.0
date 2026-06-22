<?php
//INICIO MODELS E CONTROLLERS
require_once("Controller/NotasController.php");
require_once("Model/Notas.php");
require_once("Controller/PedidosController.php");
require_once("Model/Pedidos.php");
require_once("Controller/ServicoController.php");
require_once("Model/Servicos.php");

require_once("Controller/UsuariosController.php");
require_once("Model/Usuarios.php");

//FIM MODELS E CONTROLLERS
//FUNCOES ALTERNATIVAS
require_once("Util/UploadFile.php");
require_once("Util/functions.php");
include("Action/conn.php");

$retorno = "&nbsp;";
$erros = [];
$banco = new Banco();

$notasController = new NotasController();
$usuarioController = new UsuarioController();
$clientesController = new ClientesController();
$pedidosController = new PedidosController();
$servicoController = new ServicoController();

$codnota = $_GET['cod'];




if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
  $cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
  $codentrada = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
  $tipo = 2;
  $termo = "";
  $listaEntradas = $entradasController->RetornarEntradas($termo, $tipo, $cod);
  if ($listaEntradas != null) {
    foreach ($listaEntradas as $user477) {
      $status_nota = $user477->getStatus();
      $dia = $user477->getDia();
      $mes = $user477->getMes();
      $ano = $user477->getAno();
      $fornecedor = $user477->getFornecedor();
      $fornecedor = "FORNECEDOR PADRÃO";
      $n_notafiscal = $user477->getN_notafiscal();
      $codfunc = $user477->getCod_funcionario();

      $data = $dia . '/' . $mes . '/' . $ano;
      $nome_func = $usuarioController->RetornarNomeUsuarios($codfunc);

      $textocliente = "";
      $sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod DESC");
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

      $texto_tipo = "";
      if ($status_nota == 1) {
        $texto_tipo = "ENTRADA EM ABERTO";
      } else if ($status_nota == 3) {
        $texto_tipo = "FINALIZADO";
      }
    }
  }
} else {
  header("location: index.php?msgget=2");
}


if (filter_input(INPUT_POST, "btnAlterarImagem", FILTER_SANITIZE_STRING)) {

  $upload = new Upload();
  $thumb = filter_input(INPUT_POST, "btnImagemAtual", FILTER_SANITIZE_STRING);
  $codentrada = filter_input(INPUT_POST, "txtCodentrada", FILTER_SANITIZE_NUMBER_INT);
  $nomeImagem = $upload->LoadFile("Interface/img/Entradas/", "img", $_FILES["flImagem"]);
  if ($nomeImagem != "" && $nomeImagem != "invalid") {
    if ($entradasController->AlterarImagem($nomeImagem, $codentrada)) {
      echo "deu certo";
      //  unlink("Interface/img/Entradas/" . $thumb);
      header("Location: index.php?pagina=entradas&cod=" . $_GET['cod'] . "&msgget=3");
    } else {
      $resultado = "<div style='width:100%;' class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar alterar a imagem.</div>";
      unlink("Interface/img/Entradas/{$nomeImagem}");
    }
  } else if ($nomeImagem == "invalid") {
    $resultado = "<div style='width:100%;' class=\"alert alert-danger\" role=\"alert\">Formato de imagem inválido.</div>";
  } else {
    $resultado = "<div style='width:100%;' class=\"alert alert-danger\" role=\"alert\">Houve um erro ao tentar carregar a imagem.</div>";
  }
}

if (filter_input(INPUT_POST, "btnCadastrarFinalizarEntrada", FILTER_SANITIZE_STRING)) {
  $cod_entrada = filter_input(INPUT_POST, "txtCod", FILTER_SANITIZE_NUMBER_INT);
  $status = $cod_entrada;
  $entradas = $entradasController->RetornarEntradas("", 2, $cod_entrada);

  if ($entradas != NULL) {
    foreach ($entradas as $entradasteste) {
      if ($entradasteste->getStatus() == 3) {
        header("Location: index.php?pagina=entradas&cod=" . $cod_entrada . "&msgget=4");
      } else {
        if ($entradasController->AlterStatus(3, $cod_entrada)) {
          $qtd_final = 0;
          $cod_produto3 = 0;
          $qtd_final3 = 0;

          $termo = "";
          $tipo = 1;

          $listaentradas3 = $listaentradasControler->RetornarListaEntradas($termo, $tipo, $status);
          $qtd_final3 = 0;
          if ($listaentradas3 != NULL) {
            foreach ($listaentradas3 as $entradas3) {
              $qtd_final++;
              $cod_produto3 = $entradas3->getCod_produto();
              $qtdp = $servicoController->RetornarNomeValorProdutos($cod_produto3);

              $qtd_final3 = $qtdp + $entradas3->getQtd();
              $servicoController->AlterQtdEstoque($qtd_final3, $cod_produto3);
              //$produtosController->AlterStatus(1, $cod_produto3);
              $qtd_final3 = 0;
              $cod_produto3 = 0;
            }
          }
        } else {
          $resultado = "div class='alert alert-danger>Erro ao alterar status da Entrada</div>";
        }
      }
      header("Location: index.php?pagina=entradas&cod=" . $cod_entrada);
    }
  }
}

if (filter_input(INPUT_POST, "btnCadastrarCancelarEntrada", FILTER_SANITIZE_STRING)) {
  $cod_entrada = filter_input(INPUT_POST, "txtCod", FILTER_SANITIZE_NUMBER_INT);
  if ($entradasController->Deletar($cod_entrada)) {
    if ($listaentradasControler->DeletarTodos($cod_entrada)) {
      header("Location: index.php?&msgget=16");
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao deletar Entrada!</span> </div>";
    }
  }
}

if (filter_input(INPUT_POST, "btnCadastrarPausarEntrada", FILTER_SANITIZE_STRING)) {
  $cod_entrada = filter_input(INPUT_POST, "txtCod", FILTER_SANITIZE_NUMBER_INT);

  if ($entradasController->AlterStatus(2, $cod_entrada)) {
    header("Location: index.php?pagina=entradas&cod=" . $_GET['cod'] . "&msgget=2");
    $resultado = " <div class='alert alert-success' role='alert'><span>Entrada Pausada com sucesso!</span> </div>";
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao deletar Entrada!</span> </div>";
  }
}

if (filter_input(INPUT_POST, "btnCadastrarCancelarEntradaDepoisFinalizado", FILTER_SANITIZE_STRING)) {
  $cod_entrada = filter_input(INPUT_POST, "txtCod", FILTER_SANITIZE_NUMBER_INT);
  $entradas = $entradasController->RetornarEntradas("", 2, $cod_entrada);

  if ($entradas != NULL) {

    $qtd_final = 0;
    $cod_produto3 = 0;
    $qtd_final3 = 0;

    $termo = "";
    $tipo = 1;
    $status = $cod_entrada;
    $listaentradas3 = $listaentradasControler->RetornarListaEntradas($termo, $tipo, $status);
    $qtd_final3 = 0;
    if ($listaentradas3 != NULL) {
      foreach ($listaentradas3 as $entradas3) {
        $qtd_final++;
        $cod_produto3 = $entradas3->getCod_produto();
        $qtdp = $produtosController->RetornarNomeValorProdutos($cod_produto3);
        $qtd_final3 = (int) $qtdp - (int) $entradas3->getQtd();

        $produtosController->AlterQtdEstoque($qtd_final3, $cod_produto3);
        $produtosController->AlterStatus(1, $cod_produto3);
        $qtd_final3 = 0;
        $cod_produto3 = 0;
      }
    }
    //    if ($entradasController->Deletar($cod_entrada)) {
    //      if ($listaentradasControler->DeletarTodos($cod_entrada)) {
    //        header("Location: index.php?pagina=entradas&cod=" . $_GET['cod'] . "&msgget=4");
    //  } else {
    //    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao deletar Entrada!</span> </div>";
    //}
    //}
    if ($entradasController->AlterStatus(1, $cod_entrada)) {
      header("Location: index.php?pagina=entradas&cod=" . $cod_entrada . "&msgget=5");
    }
  } else {
    header("Location: index.php?pagina=entradas&cod=" . $_GET['cod'] . "&msgget=4");
  }
}

if (filter_input(INPUT_POST, "btnCadastrarNovoProduto", FILTER_SANITIZE_STRING)) {
  $fornecedor = 0;
  $nome = (filter_input(INPUT_POST, "txtNomeProduto", FILTER_SANITIZE_STRING));
  $descricao = (filter_input(INPUT_POST, "txtDescricaoProduto", FILTER_SANITIZE_STRING));
  $valor = (filter_input(INPUT_POST, "txtValor", FILTER_SANITIZE_STRING));
  $cat = (filter_input(INPUT_POST, "txtCategoria", FILTER_SANITIZE_NUMBER_INT));
  $tipo = 1;
  $estmax = (filter_input(INPUT_POST, "txtEstMax2", FILTER_SANITIZE_NUMBER_INT));
  $estmin = (filter_input(INPUT_POST, "txtEstMin2", FILTER_SANITIZE_NUMBER_INT));
  //  $fornecedor = (filter_input(INPUT_POST, "txtFornecedor", FILTER_SANITIZE_NUMBER_INT));
  // $codbarra = (filter_input(INPUT_POST, "txtCodBarra", FILTER_SANITIZE_NUMBER_INT));

  $pontos = '.';
  $result = str_replace($pontos, "", $valor);
  $result = str_replace(",", ".", $result);
  $valor = $result;



  $erros = Validar2();

  if (empty($erros)) {
    $servicos = new Servicos();
    $servicos->setNome($nome);
    $servicos->setDescricao($descricao);
    $servicos->setValor($valor);
    $servicos->setCategoria($cat);
    $servicos->setTipo($tipo);
    $servicos->setEst_max($estmax);
    $servicos->setEst_mim($estmin);
    $servicos->setFornecedor(0);
    $servicos->setCodbarra(0);


    if ($servicoController->Cadastrar($servicos)) {
      $cod = 0;
      $nome = "";
      $descricao = "";
      $valor = "";
      $categoria = 0;
      $codbarra = "";
      $codultcod = $servicoController->RetornarUltCad(1);
      $cod_entrada = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
      header("Location: index.php?pagina=entradas&cod=$cod_entrada&msgget=6&codprod=$codultcod");

      $resultado = " <div class='alert alert-success' role='alert'><span>Produto cadastrado com sucesso! </span> </div>";
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Serviço!</span> </div>";
    }



  }
}
function Validar2()
{
  $listaErros = [];


  if (strlen(filter_input(INPUT_POST, "txtNomeProduto", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Nome Produto inválido.";
  }
  if (strlen(filter_input(INPUT_POST, "txtValor", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Valor inválido.";
  }

  return $listaErros;
}



//SUBMIT PARA CADASTRAR ITEM NOTA DE ENTRADA
if (filter_input(INPUT_POST, "btnCadastrarItemNota", FILTER_SANITIZE_STRING)) {



  $codentrada = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $codproduto = filter_input(INPUT_POST, "txtCodproduto", FILTER_SANITIZE_NUMBER_INT);
  $qtditem = filter_input(INPUT_POST, "txtQtdAvulso", FILTER_SANITIZE_NUMBER_INT);
  $valorunt = filter_input(INPUT_POST, "txtValoruntAvulso", FILTER_SANITIZE_STRING);
  $lote = filter_input(INPUT_POST, "txtDescricaoAvulso", FILTER_SANITIZE_STRING);
  $validade = filter_input(INPUT_POST, "txtValidade", FILTER_SANITIZE_STRING);


  $partes = explode('-', $validade);

  $mes_validade = $partes['1'];
  $ano_validade = $partes['0'];


  $pontos = '.';
  $result = str_replace($pontos, "", $valorunt);
  $result = str_replace(",", ".", $result);
  $valorunt = (float) $result;

  $valortotal = 0;

  $valortotal = $qtditem * $valorunt;

  $sqlPedidos2 = "SELECT * FROM entradas WHERE cod = :cod ORDER BY cod DESC";
  $paramPedidos2 = array(
    ":cod" => $codentrada
  );

  $dataTablePedidos2 = $banco->ExecuteQuery($sqlPedidos2, $paramPedidos2);
  foreach ($dataTablePedidos2 as $notaslista) {

    $dia = $notaslista['dia'];
    $mes = $notaslista['mes'];
    $ano = $notaslista['ano'];
  }
  $sql = "INSERT INTO `lista_entradas` (cod_entrada, cod_produto, lote, mes_validade, ano_validade, qtd, valor_total, dia, mes, ano) VALUES (:cod_entrada, :cod_produto, :lote, :mes_validade, :ano_validade, :qtd, :valor_total, :dia, :mes, :ano)";
  $param = array(
    ":cod_entrada" => $codentrada,
    ":cod_produto" => $codproduto,
    ":lote" => $lote,
    ":mes_validade" => $mes_validade,
    ":ano_validade" => $ano_validade,
    ":qtd" => $qtditem,
    ":valor_total" => $valortotal,
    ":dia" => $dia,
    ":mes" => $mes,
    ":ano" => $ano
  );
  $banco->ExecuteNonQuery($sql, $param);



  header("location: index.php?pagina=entradas&cod=$codnota&itemcadastradocomsucesso");

}
//FIM DO SUBMIT PARA CADASTRAR ITEM NOTA DE ENTRADA

//SQL PARA CAPTURAR O VALOR TOTAL DO PEDIDO QUANDO O CARRINHO FOR INICIADO

$sqlPedido = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE cod_entrada = " . $codentrada . " ORDER BY cod DESC");
$valor_total_pedido = 0;
$qtdpedidos = 0;
while ($pedidos = mysqli_fetch_object($sqlPedido)) {
  $qtdpedidos = $qtdpedidos + (float) $pedidos->qtd;
  $valor = (float) $pedidos->valor_total;
  $valor_total_pedido = $valor_total_pedido + $valor;
  $valor = 0;
}
$valor_total_pedido = number_format($valor_total_pedido, 2, ',', '.');
//FIM DA CONSULTA

//SQL PARA VALIDAR SE A VENDA JÁ FOI REALIZADO O PAGAMENTO
$sqlTesteEntradas = "SELECT * FROM entradas WHERE cod = :cod ORDER BY cod ASC";
$paramTesteEntradas = array(
  ":cod" => $codentrada
);
$nomecategoria = "Avulso";
$dataTableEntradas = $banco->ExecuteQuery($sqlTesteEntradas, $paramTesteEntradas);



$dinheiropag = 0;
$pixpag = 0;
$debitopag = 0;
$creditopag = 0;
$totalpag = 0;
$subtotalpag = 0;
$descontopag = 0;
$gorjetapag = 0;



foreach ($dataTableEntradas as $infoentrada) {

  $statusentrada = $infoentrada['status'];
  $notafiscal = (float) $infoentrada['n_notafiscal'];
}

//var_dump($dataTableTestePag);

//FIM DO SQL PARA CONSULTA DO PAGAMENTO
if ($statusentrada == 1) {

  ?>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <svg style="fill: #0000ff;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
          class="bi bi-box-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
        </svg>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="width:100%;">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="width:100%; ">
          <li class="nav-item" style="width:17%;">
            <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action=''
              style='margin-top:5px; width:100%;'>
              <input type='hidden' value='<?= $codentrada; ?>' id='txtCod' name='txtCod' />
              <input type='submit' value='Finalizar Entrada' style="width:100%; font-size:11pt; padding:10px; "
                class="btn btn-outline-success" name='btnCadastrarFinalizarEntrada' />

            </form>

          </li>
          <li class="nav-item" style="width:17%;">
            <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action=''
              style='margin-top:5px; width:100%;'>
              <input type='hidden' value='<?= $codentrada; ?>' id='txtCod' name='txtCod' />

              <input type='submit' value='Cancelar' style="width:100%; font-size:11pt; padding:10px; "
                class="btn btn-outline-DANGER" name='btnCadastrarCancelarEntrada' />
            </form>

          </li>
          <li class="nav-item" style="width:17%;">
            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCliente"
              style="width:100%; font-size:11pt; padding:10px; margin-top:5px;" class="btn btn-outline-primary"
              href="#">F3 - CADASTRAR
              PRODUTO</a>
          </li>

          <li class="nav-item" style="width:17%;">
            <a onclick="validacao(73, <?= $codnota; ?>, 0, 73)" type="button" data-bs-toggle="modal"
              data-bs-target="#exampleModalListaItens" style="width:100%;  padding:9px; margin-top:5px;"
              class="btn btn-outline-primary" href="#">F8 -
              LISTA DE ITENS</a>
          </li>
        </ul>

      </div>
    </div>
  </nav>
  <?PHP
} else {
  ?>

  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <svg style="fill: #0000ff;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor"
          class="bi bi-box-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
        </svg>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="width:100%;">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="width:100%;">

          <li class="nav-item" style="width:33%;">
            <a tabindex="5" style="width:100%;" class="btn btn-outline-secondary" href="">EXTRATO</a>
          </li>

          <li class="nav-item" style="width:33%;">
            <a tabindex="6" style="width:100%;" class="btn btn-outline-danger" href="index.php?">SAIR</a>
          </li>
        </ul>

      </div>
    </div>
  </nav>
  <?php
}
?>
<section class="h-100 gradient-custom">
  <div class="container py-5">
    <?php if ($statusentrada == 1) { ?>
      <input tabindex="1" onkeyup="validacao(71, <?= $codentrada; ?>, this.value, 1)" autofocus
        style="width:97%; margin-left:5%; padding:20px; margin-left:2%; margin-bottom:10px; margin-top:-30px;"
        class="form-control" type="text" placeholder="Pesquisar item..." aria-label="Search" name='txtPesquisarProd'
        id='txtPesquisarProd'>
      <div class="row d-flex justify-content-center my-4">

        <div class="col-md-8">
          <div class="card mb-4" id='ResultadoValidacao1'>
            Nenhum item localizado...
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="card-header py-3">
              <h5 class="mb-0">Resumo Entrada</h5>
            </div>
            <div class="card-body">

              <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                <div>
                  <strong>Qtd Total</strong>
                </div>
                <span><strong><?= $qtdpedidos; ?></strong></span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                <div>
                  <strong>Total Final</strong>
                </div>
                <span><strong>R$ <?= $valor_total_pedido; ?></strong></span>
              </li>
              </ul>


            </div>
          </div>
          <div class="card mb-4">
            <div class="card-body">
              <p><strong>Informações da Entrada</strong></p>

              <p class="mb-0">nº Nota: <b><?= $n_notafiscal; ?></b></p>
              <p class="mb-0">Funcionário: <b><?= $nome_func; ?></b></p>
              <p class="mb-0">Data: <b><?= $data; ?></b></p>
              <p class="mb-0">Fornecedor: <b><?= $fornecedor; ?></b></p>
              <p class="mb-0" style="text-align: center;"><a href='index.php?' class='btn btn-danger'
                  style='padding:20px; font-size:9pt; margin:5%;'>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                      d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                    <path fill-rule="evenodd"
                      d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                  </svg>
                  <b>Sair</b></a></p>
            </div>
          </div>

        </div>
      </div>

    <?php } else {
      $cod_funcionario = $_SESSION['codF'];
      $sqlNotaCaixa = "SELECT * FROM fechar_caixa WHERE cod_funcionario = $cod_funcionario AND status = :status ORDER BY cod DESC LIMIT 1";
      $paramNotaCaixa = array(
        ":status" => 1
      );

      $dataTableNotaCaixa = $banco->ExecuteQuery($sqlNotaCaixa, $paramNotaCaixa);
      if ($dataTableNotaCaixa == null) {

        header("Location: index.php?msgget=15");
      } else {
        foreach ($dataTableNotaCaixa as $resultadonotaCaixa) {
          $codcaixa = $resultadonotaCaixa['cod'];

        }
      }
      ?>


      <div class="row d-flex justify-content-center my-4">

        <div class="col-md-8">
          <div class="card mb-4" id='ResultadoValidacao1'>
            <?php
            $total = 0;
            $contador = 0;
            echo "
             <table class='table table-striped table-sm' style='font-size:10pt; width:100%'>";
            echo "
			<tr>
				<td style='width:50%;'><b>Produto</b></td>
				<td><b>Qtd</b></td>
				<td><b>Valor Unt.</b></td>
				<td><b>Valor Total</b></td>
				<td><b>Lote</b></td>
				<td><b>Validade</b></td>
				
			</tr>
			";
            //$sql = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
            $sqlPedidos2 = "SELECT * FROM lista_entradas WHERE cod_entrada = :cod ORDER BY cod DESC";
            $paramPedidos2 = array(
              ":cod" => $codentrada
            );

            $dataTablePedidos2 = $banco->ExecuteQuery($sqlPedidos2, $paramPedidos2);
            foreach ($dataTablePedidos2 as $resultadopedidos2) {

              $codpedido = $resultadopedidos2['cod'];
              $codservico = $resultadopedidos2['cod_produto'];
              $lote = $resultadopedidos2['lote'];
              $mes_validade = $resultadopedidos2['mes_validade'];
              $ano_validade = $resultadopedidos2['ano_validade'];
              $dia = $resultadopedidos2['dia'];
              $mes = $resultadopedidos2['mes'];
              $ano = $resultadopedidos2['ano'];

              $data = $dia . "/" . $mes . "/" . $ano;

              //$sql2 = mysqli_query($conn, "SELECT * FROM servicos WHERE cod=" . $codservico . " LIMIT 1");
              $sqlServicos = "SELECT * FROM servicos WHERE cod= :cod LIMIT 1";
              $paramServicos = array(
                ":cod" => $codservico
              );

              $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
              foreach ($dataTableServicos as $resultadoservicos) {
                $nomeservico = $resultadoservicos['nome'];

              }
              $valor = (float) $resultadopedidos2['valor_total'];
              $qtd = (float) $resultadopedidos2['qtd'];
              $valorunt = $valor / $qtd;
              $total = $total + $valor;

              $valorunt = number_format($valorunt, 2, ',', '.');
              $valor = number_format($valor, 2, ',', '.');



              $contador++;
              echo "
				
					<tr>
						<td>" . $nomeservico . "</td>
						<td>$qtd</td>
						<td>R$ " . $valorunt . "</td>
						<td>R$ " . $valor . "</td>
						<td>$lote</td>
						<td>$mes_validade" . "/" . "$ano_validade</td>

					
							
					</tr>
		   ";
              $contador++;
            }
            $total = number_format($total, 2, ',', '.');
            echo "
			<tr>
				<td colspan='3' style='text-align:right;'>Total:</td>
				<td colspan='4'><b>R$ " . $total . "</b></td>
        
			</tr>
			";
            echo "</table>";
            ?>
          </div>
        </div>
        <div class="col-md-4">
          <?php if ($status_nota == 3) { ?>

            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="mb-0">Resumo Entrada</h5>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush" id='ResultadoValidacaoAddItem'>
                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                    Qtd
                    <span id=''><?= $qtdpedidos; ?></span>
                  </li>

                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                    <div>
                      <strong>Total Final</strong>
                    </div>
                    <span><strong>R$ <?= $valor_total_pedido; ?></strong></span>
                  </li>
                </ul>
              </div>
            </div>
          <?php } else { ?>

          <?php } ?>
          <div class="card mb-4">
            <div class="card-body">
              <p><strong>Informações da Entrada</strong></p>
              <p class="mb-0">nº Nota: <b><?= $n_notafiscal; ?></b></p>
              <p class="mb-0">Funcionário: <b><?= $nome_func; ?></b></p>
              <p class="mb-0">Data: <b><?= $data; ?></b></p>
              <p class="mb-0">Fornecedor: <b><?= $fornecedor; ?></b></p>
              <p class="mb-0" style="text-align: center;">
                <?php if ($statusentrada == 1) { ?>
                  <a href='index.php?' class='btn btn-danger' style='padding:20px; font-size:9pt; margin:5%;'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                      class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                      <path fill-rule="evenodd"
                        d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                      <path fill-rule="evenodd"
                        d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                    </svg>

                    <b>Sair do Carrinho</b></a>
                <?php } ?>


              </p>
            </div>
          </div>

        </div>
      </div>
      <?php
    } ?>
  </div>
</section>

<!-- Modal Pagamento -->
<div class="modal fade" id="exampleModalPagamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Finalizar Nota!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='ResultadoValidacao10'>
        <?PHP
        echo "	<form onsubmit='return ConfirmarIsso();'   name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;'>
									<input  type='hidden' value='$codentrada' id='txtCod' name='txtCod' />
									<input  style='width:100%; box-shadow: 2px 2px 5px #000; color:#fff;'  type='submit' value='Finalizar Entrada' class='btn btn-success badge-pill btn-lg btn-block ' name='btnCadastrarFinalizarEntrada' />";


        echo "   </BR></br><input  style='width:100%; box-shadow: 2px 2px 5px #000; color:#fff;'  type='submit' value='Cancelar' class='btn btn-danger badge-pill badge-pill btn-lg btn-block' name='btnCadastrarCancelarEntrada' />";
        echo "</form> ";
        ?>
      </div>
    </div>

  </div>
</div>

<!-- Modal Cliente -->

<div class="modal fade" id="exampleModalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Novo Produto!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">

<form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastroProd' id='frmCadastroProd' novalidate enctype='multipart/form-data'>

  <div class='col-12 col-md-12 mb-3'>
    <label for='txtNomeProduto' class='form-label' style='color:#337AB7; font-weight: bold;'>Nome do Produto</label>
    <input tabindex='10' type='text' class='form-control form-control-lg' id='txtNomeProduto' name='txtNomeProduto' value='' style='font-size: 14pt;'>
  </div>

  <div class='col-12 col-md-12 d-none'>
    <input tabindex='11' type='hidden' class='form-control' id='txtDescricaoProduto' name='txtDescricaoProduto' value=''>
  </div>

  <div class='col-12 col-md-12 mb-3'>
    <label for='txtValorUntProduto' class='form-label' style='color:#337AB7; font-weight: bold;'>Valor Unit. de Venda</label>
    <input tabindex='12' type='text' class='form-control form-control-lg' id='txtValorUntProduto' name='txtValorUntProduto' value='' style='font-size: 14pt;'>
    <input type='hidden' id='txtTipo' name='txtTipo' value='0'>
  </div>

  <input type="hidden" value="<?= $codentrada;?>" id="codentrada" name="codentrada" />

  <div class='col-12 col-md-12 mb-3'>
    <label for='txtCategoria' class='form-label' style='color:#337AB7; font-weight: bold;'>Categoria</label>
    <select tabindex='13' class='form-control form-control-lg' id='txtCategoria' name='txtCategoria' style='font-size: 14pt;'>
      <?php
        $sqlCategorias = "SELECT * FROM categoriaserfin ORDER BY cod ASC";
        $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias);
        foreach ($dataTableCategorias as $resultadocategorias) {
          $cod_categoria = $resultadocategorias['cod'];
          $nomecategoria = $resultadocategorias['nome'];
          echo " <option value='$cod_categoria'>$nomecategoria</option>";
        }
      ?>
    </select>
  </div>

  <div class='col-12 col-md-12' id='ResultadoValidacao1212'></div>

  <div class='col-12 col-md-12 mb-3'>
    <label for='txtEstMax2' class='form-label' style='color:#337AB7; font-weight: bold;'>Estoque Máximo</label>
    <input tabindex='14' type='text' class='form-control form-control-lg' id='txtEstMax2' name='txtEstMax2' value='' style='font-size: 14pt;'>
  </div>

  <div class='col-12 col-md-12 mb-4'>
    <label for='txtEstMin2' class='form-label' style='color:#337AB7; font-weight: bold;'>Estoque Mínimo</label>
    <input tabindex='15' type='text' class='form-control form-control-lg' id='txtEstMin2' name='txtEstMin2' value='' style='font-size: 14pt;'>
  </div>

  <div class='col-12 col-md-12 text-center'>
    <input tabindex='16' type='submit' name='btnCadastrarNovoProdutoEmEntradas' id='btnCadastrarNovoProdutoEmEntradas' value='Cadastrar' class='btn btn-success btn-lg w-100' style='font-size: 16pt; padding: 12px 0;' />
  </div>

</form>


          </DIV>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Item-->
  <div class="modal fade" id="exampleModalEditarItem" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Editar último item!</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form onsubmit='return ConfirmarIsso();' name='form_cadastrareditaritem' method='post' action=''
            class="row g-3 needs-validation" novalidate>
            <div class="col-md-12">
              <label for="txtContatoCadRes" class="form-label">Qtd</label>
              <input onkeyup="validacao(6, this.value, <?= $codnota; ?>, 6);" type="text"
                class="form-control form-control-lg" id="txtQtdEd" name='txtQtdEd' value="1" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div id='ResultadoValidacao5'>
              <div class="col-md-12">
                <label for="txtNomeCompletoCadRes" class="form-label">Descrição do Item</label>
                <input type="text" class="form-control form-control-lg" id="txtDescricaoEd" name='txtDescricaoEd'
                  value="" required>
                <div class="valid-feedback">
                  Correto!
                </div>
              </div>
              <div class="col-md-6">
                <label for="txtContatoCadRes" class="form-label">Valor Unt.</label>
                <input type="text" class="form-control form-control-lg" id="txtValorUntEd" name='txtValorUntEd' value=""
                  required>
                <div class="valid-feedback">
                  Correto!
                </div>
              </div>
              <div class="col-md-6" id='ResultadoValidacao6'>
                <label for="txtNomeCompletoCadRes" class="form-label">Valor Total</label>
                <input type="hidden" id="txtValorTotalRealEd" id='txtValorTotalRealEd' value="" required>
                <input type="text" class="form-control form-control-lg" id="txtValorTotal" disabled value="" required>
                <div class="valid-feedback">
                  Correto!
                </div>
              </div>
              <div class="col-md-12">
                <label for="txtNomeCompletoCadRes" class="form-label">Observação</label>
                <input type="text" class="form-control form-control-lg" id="txtObsEd" name='txtObsEd' value="" required>
                <div class="valid-feedback">
                  Correto!
                </div>
              </div>
              <div class="col-12">
                <button class="btn btn-outline-success" type="submit" style="width:100%; padding:20px;">Atualizar
                  Item</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Item Avulso -->
<div class="modal fade" id="exampleModalItemAvulso" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cadastrar Item da Nota!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action=''
          style='margin-top:5px; width:100%;'>
          <input type='hidden' value='<?= $codentrada; ?>' id='txtCodnota' name='txtCodnota' />
          <div id='ResultadoValidacao72'>
            <input type='hidden' value='1' id='txtCodproduto' name='txtCodproduto' />
          </div>

          <div class='row'>
            <div class="col-md-4">
              <label for="txtContatoCadRes" class="form-label">Qtd</label>
              <input onkeyup='validacao(7, this.value, txtValoruntAvulso.value, 7)' type="text"
                class="form-control form-control-lg" id="txtQtdAvulso" name='txtQtdAvulso' value="1" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>

            <div class="col-md-4">
              <label for="txtValorUntAvulso" class="form-label">Valor Unt.</label>
              <input onkeyup='' type="text" class="form-control form-control-lg" id="txtValoruntAvulso"
                name='txtValoruntAvulso' value="1,00" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div class="col-md-4" id='ResultadoValidacao7'>
              <label for="txtNomeCompletoCadRes" class="form-label">Valor Total</label>
              <input type="hidden" id="txtValorTotalAvulsoReal" id='txtValorTotalAvulsoReal' value="1,00" required>
              <input type="text" class="form-control form-control-lg" id="txtValorTotalAvulso"
                name='txtValorTotalAvulso' disabled value="1,00" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div class="col-md-6">
              <label for="txtNomeCompletoCadRes" class="form-label">Lote</label>
              <input type="text" class="form-control form-control-lg" id="txtDescricaoAvulso" name='txtDescricaoAvulso'
                value="" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div class="col-md-6">
              <label for="txtNomeCompletoCadRes" class="form-label">Validade(Mês/Ano)</label>
              <input type="month" class="form-control form-control-lg" id="txtValidade" name='txtValidade'
                value="<?= date('Y-m'); ?>" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>



            <div class="col-12">
              <input value='Cadastrar Item' style='width:100%; padding:10px;' id='btnCadastrarItemNota'
                name='btnCadastrarItemNota' type='submit' class='btn btn-outline-success' />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Lista de Itens -->
<div class="modal fade" id="exampleModalListaItens" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Lista de Itens!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='ResultadoValidacao73'>

      </div>


    </div>
  </div>
</div>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <img src="..." class="rounded me-2" alt="...">
      <strong class="me-auto">Bootstrap</strong>
      <small>11 mins ago</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
  </div>
</div>
<style>
  .gradient-custom {
    /* fallback for old browsers */
    background: #6a11cb;

    /* Chrome 10-25, Safari 5.1-6 */
    background: -webkit-linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));

    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1))
  }
</style>
<script>
  const myModal = document.getElementById('exampleModalPagamento')
  const myInput = document.getElementById('txtDinheiro')

  myModal.addEventListener('shown.bs.modal', () => {
    myInput.focus()
  })


  const toastTrigger = document.getElementById('liveToastBtn')
  const toastLiveExample = document.getElementById('liveToast')

  if (toastTrigger) {
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastTrigger.addEventListener('click', () => {
      toastBootstrap.show();
    })
  }


  const myModalCli = document.getElementById('exampleModalCliente')
  const myInputCli = document.getElementById('txtDescricaoProduto')

  myModalCli.addEventListener('shown.bs.modal', () => {
    myInputCli.focus()
  })

  const myModalEd = document.getElementById('exampleModalEditarItem')
  const myInputEd = document.getElementById('txtQtdEd')

  myModalEd.addEventListener('shown.bs.modal', () => {
    myInputEd.focus();
    myInputEd.select();
  })


  const myModalAvulso = document.getElementById('exampleModalItemAvulso')
  const myInputAvulso = document.getElementById('txtQtdAvulso')

  myModalAvulso.addEventListener('shown.bs.modal', () => {
    myInputAvulso.focus();

    myInputAvulso.select();
  })



  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtDinheiro');
    var moneyInputDin = document.getElementById('txtDinheiro');
    var moneyInputPix = document.getElementById('txtPix');
    var moneyInputCarDebito = document.getElementById('txtCardebitoPag');
    var moneyInputCarCredito = document.getElementById('txtCarcreditoPag');
    var moneyInputTotalReal = document.getElementById('txtTotalReal');
    var moneyInputDesconto = document.getElementById('txtDesconto');

    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: 'R$ ',
        zeroCents: false
      });
      moneyInput.value = masked;

      validacaopagamento(3, moneyInputDin.value, moneyInputPix.value, moneyInputCarDebito.value, moneyInputCarCredito.value, moneyInputTotalReal.value, moneyInputDesconto.value, 2);
    });
  });



  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtPix');
    var moneyInputDin = document.getElementById('txtDinheiro');
    var moneyInputPix = document.getElementById('txtPix');
    var moneyInputCarDebito = document.getElementById('txtCardebitoPag');
    var moneyInputCarCredito = document.getElementById('txtCarcreditoPag');
    var moneyInputTotalReal = document.getElementById('txtTotalReal');
    var moneyInputDesconto = document.getElementById('txtDesconto');



    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: 'R$ ',
        zeroCents: false
      });
      moneyInput.value = masked;

      validacaopagamento(3, moneyInputDin.value, moneyInputPix.value, moneyInputCarDebito.value, moneyInputCarCredito.value, moneyInputTotalReal.value, moneyInputDesconto.value, 2);


    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtCardebitoPag');
    var moneyInputDin = document.getElementById('txtDinheiro');
    var moneyInputPix = document.getElementById('txtPix');
    var moneyInputCarDebito = document.getElementById('txtCardebitoPag');
    var moneyInputCarCredito = document.getElementById('txtCarcreditoPag');
    var moneyInputTotalReal = document.getElementById('txtTotalReal');
    var moneyInputDesconto = document.getElementById('txtDesconto');



    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: 'R$ ',
        zeroCents: false
      });
      moneyInput.value = masked;


      validacaopagamento(3, moneyInputDin.value, moneyInputPix.value, moneyInputCarDebito.value, moneyInputCarCredito.value, moneyInputTotalReal.value, moneyInputDesconto.value, 2);

    });
  });
  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtCarcreditoPag');
    var moneyInputDin = document.getElementById('txtDinheiro');
    var moneyInputPix = document.getElementById('txtPix');
    var moneyInputCarDebito = document.getElementById('txtCardebitoPag');
    var moneyInputCarCredito = document.getElementById('txtCarcreditoPag');
    var moneyInputTotalReal = document.getElementById('txtTotalReal');
    var moneyInputDesconto = document.getElementById('txtDesconto');



    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: 'R$',
        zeroCents: false
      });
      moneyInput.value = masked;

      validacaopagamento(3, moneyInputDin.value, moneyInputPix.value, moneyInputCarDebito.value, moneyInputCarCredito.value, moneyInputTotalReal.value, moneyInputDesconto.value, 2);

    });
  });
  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtDesconto');
    var moneyInputDin = document.getElementById('txtDinheiro');
    var moneyInputPix = document.getElementById('txtPix');
    var moneyInputCarDebito = document.getElementById('txtCardebitoPag');
    var moneyInputCarCredito = document.getElementById('txtCarcreditoPag');
    var moneyInputTotalReal = document.getElementById('txtTotalReal');
    var moneyInputDesconto = document.getElementById('txtDesconto');
    var moneyInputValorNovoProduto = document.getElementById('txtDesconto');



    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: 'R$',
        zeroCents: false
      });
      moneyInput.value = masked;

      validacaopagamento(3, moneyInputDin.value, moneyInputPix.value, moneyInputCarDebito.value, moneyInputCarCredito.value, moneyInputTotalReal.value, moneyInputDesconto.value, 2);

    });
  });


  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtValorUntEd');


    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: 'R$',
        zeroCents: false
      });
      moneyInput.value = masked;

    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtValorUntProduto');


    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: ' ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: '',
        zeroCents: false
      });
      moneyInput.value = masked;

    });
  });


  document.addEventListener('DOMContentLoaded', function () {
    var moneyInput = document.getElementById('txtValoruntAvulso');

    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: '', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function () {
      VMasker(moneyInput).unMask();
      var masked = VMasker.toMoney(moneyInput.value, {
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: '',
        zeroCents: false
      });
      moneyInput.value = masked;

      validacao(7, txtQtdAvulso.value, txtValoruntAvulso.value, 7);


    });
  });


  //FUNCOES JAVASCRIPT PARA CHAMAR ATALHO

  document.addEventListener('keydown', function (event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F2') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalPagamento'));
      myModal.show();
      validacao(2, <?= $codnota; ?>, 0, 2);
    }
  });


  document.addEventListener('keydown', function (event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F3') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalCliente'));
      myModal.show();
      validacao(2, <?= $codnota; ?>, 0, 2);
    }
  });

  document.addEventListener('keydown', function (event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F4') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)

      window.open('cupom.php?cod=<?= $codnota ?>');

    }
  });

  document.addEventListener('keydown', function (event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F6') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalEditarItem'));
      myModal.show();
      validacao(5, <?= $codnota; ?>, 0, 5);
    }
  });

  document.addEventListener('keydown', function (event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F7') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalItemAvulso'));
      myModal.show();

    }
  });


  document.addEventListener('keydown', function (event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F8') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalListaItens'));
      myModal.show();

      validacao(73, <?= $codentrada; ?>, 0, 73);

    }
  });
  //FIM DAS FUNCOES PARA CHAAMAR O ATALHO

  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("divpesquisaporcontato").style.display = "none";
    document.getElementById("divpesquisaporcpf").style.display = "none";
  });

  //FUNCAO PARA ALTERNAR DIV NO INPUT
  const radioButtons = document.querySelectorAll('input[name="btnradio"]');

  // Adiciona um manipulador de evento 'change' a cada botão de rádio
  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
      var div1 = document.getElementById("divpesquisapornome");
      var div2 = document.getElementById("divpesquisaporcontato");
      var div3 = document.getElementById("divpesquisaporcpf");

      if (this.checked) {
        // Executa uma ação baseada no botão de rádio selecionado
        switch (this.id) {
          case 'btnradioNomePesc':

            div1.style.display = "block";
            div2.style.display = "none";
            div3.style.display = "none";

            var myInputCli = document.getElementById('txtPesquisarClientePag');
            myInputCli.focus();

            break;
          case 'btnradioContatoPesc':


            div1.style.display = "none";
            div2.style.display = "block";
            div3.style.display = "none";
            var myInputCli = document.getElementById('txtPesquisarContato');
            myInputCli.focus();


            break;
          case 'btnradioCPFPesc':


            div1.style.display = "none";
            div2.style.display = "none";
            div3.style.display = "block";

            var myInputCli = document.getElementById('txtPesquisarCpf');
            myInputCli.focus();
            break;
        }
      }
    });
  });
  //FUNÇÃO PARA CONTROLE DE DIV PARA INPUTS DE PESQUISAS 
</script>