<?php
//INICIO DO FIXAÇÃO DE PAGINAS COMPLEMENTARES DE PHP
session_start();
date_default_timezone_set('America/Manaus');

//INICIO MODELS E CONTROLLERS

require_once("Controller/NotasController.php");
require_once("Model/Notas.php");

require_once("Controller/UsuariosController.php");
require_once("Model/Usuarios.php");

require_once("Controller/ClientesController.php");
require_once("Model/Clientes.php");

require_once("Controller/CategoriaSerFinController.php");
require_once("Model/CategoriaSerFin.php");


require_once("Controller/ServicoController.php");
require_once("Model/Servicos.php");


require_once("Controller/CategoriaFinanceiroController.php");
require_once("Model/CategoriaFinanceiro.php");



require_once("Controller/MovimentoEmpresaController.php");
require_once("Model/MovimentoEmpresa.php");


//FIM MODELS E CONTROLLERS


//FUNCOES ALTERNATIVAS
require_once("Util/UploadFile.php");
require_once("Util/functions.php");
include("Action/conn.php");
require_once("Util/UploadFile.php");
$retorno = "&nbsp;";
$erros = [];



$banco = new Banco();
$notasController = new NotasController();
$usuarioController = new UsuarioController();
$clientesController = new ClientesController();
$categoriaserfinController = new CategoriaSerFinController();
$categoriaFinanceiroController = new CategoriaFinanceiroController();
$servicoController = new ServicoController();
$movimentoEmpresaController = new MovimentoEmpresaController();



//FIM DO FIXAÇÃO DE PAGINAS COMPLEMENTARES DE PHP

//INICIO FUNCOES PARA PEGAR MENSAGEM DO SUBMIT ANTERIOR
$resultado = "";
if (isset($_GET['msgget'])) {

  if ($_GET['msgget'] == 1) {

    $codcli = $_GET['codcli'];
    $nomecliente = $clientesController->RetornarNomeClientes($codcli);

    $cod_funcionario = $_SESSION['codF'];
    $sqlNota = "SELECT * FROM fechar_caixa WHERE cod_funcionario = $cod_funcionario AND status = :status ORDER BY cod DESC LIMIT 1";
    $paramNota = array(
      ":status" => 1
    );

    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    if ($dataTableNota == null) {
      echo "<div class='alert alert-danger'>ABRA O CAIXA PARA INICIAR AS VENDAS!</div>";
      ?>

      <button onclick="FuncaoChamarBotao8()" style="width:100%;" class="btn btn-outline-primary" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" aria-controls="offcanvasRight">

        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-box-fill"
          viewBox="0 0 16 16">
          <path fill-rule="evenodd"
            d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
        </svg>
        <span style="font-size:18pt;">Iniciar Novo Caixa</span></button>
      <?php

    } else {
      foreach ($dataTableNota as $resultadonota) {
        $codcaixa = $resultadonota['cod'];
      }

      $resultado = "
    <div class='alert' role='alert'>
        <div class='d-flex justify-content-end'>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
          <h4 class='alert-heading'>CLIENTE SELECIONADO: $nomecliente!</h4>
          
        <hr>
        <div class='row' id='ocultarinfo'>
          <div class='card' style='width:33%;'>
          <div class='card-body'>
            <h5 id='offcanvasTopLabel' class=''>Venda Expressa</h5>
            <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
              <input name='txtCodCliente' id='txtCodCliente' value='$codcli' type='hidden' />
              <input name='txtCod_caixa' id='txtCod_caixa' value='$codcaixa' type='hidden' />
              <input name='txtTipoEntrega' id='txtTipoEntrega' value='1' type='hidden' />

              <input tabindex='1' autofocus style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-success' type='submit' name='btnSubmitPedirVendaExpressa' id='btnSubmitPedirVendaExpressa' style='' value='Inciar Venda Expressa'>
            </form>

          </div>
        </div>
        <div class='card' style='width:33%;'>
          <div class='card-body'>
            <h5 id='offcanvasTopLabel' class=''>Venda Retirada no Balcão</h5>
            <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
              <input name='txtCodCliente' id='txtCodCliente' value='$codcli' type='hidden' />
              <input name='txtCod_caixa' id='txtCod_caixa' value='$codcaixa' type='hidden' />
              <input name='txtTipoEntrega' id='txtTipoEntrega' value='2' type='hidden' />

              <input tabindex='2' style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-danger' type='submit' name='btnSubmitPedirVendaExpressa' id='btnSubmitPedirVendaExpressa' style='' value='Inciar Venda Retirada'>
            </form>
          </div>
        </div>
        <div class='card' style='width:33%;'>
          <div class='card-body'>
            <h5 id='offcanvasTopLabel' class=''>Venda Online para Entrega</h5>
            <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
              <input name='txtCodCliente' id='txtCodCliente' value='$codcli' type='hidden' />
              <input name='txtCod_caixa' id='txtCod_caixa' value='$codcaixa' type='hidden' />
              <input name='txtTipoEntrega' id='txtTipoEntrega' value='3' type='hidden' />
              <input tabindex='3' style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-primary' type='submit' name='btnSubmitPedirVendaExpressa' id='btnSubmitPedirVendaExpressa' style='' value='Inciar Venda Entrega'>
            </form>
          </div>
        </div>
        </div>
    </div>
    ";
    }
  }

  if ($_GET['msgget'] == 2) {
    $resultado = "
    <div class='alert alert-warning' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>Erro a carregar venda!</h4>
        
        <p class='mb-0'>Para continuar vendendo voce necessita iniciar uma nova venda em 'ÁREA DE VENDAS'  ou retornar a uma venda já aberta.</p>
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 3) {
    $codcaixa = $_GET['codcaixa'];
    $cod_caixa = $codcaixa;

    $cod_funcionario = $_SESSION['codF'];
    $sqlNota = "SELECT * FROM fechar_caixa WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramNota = array(
      ":cod" => $cod_caixa
    );

    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $status_caixa = $resultadonota['status'];

      $caixa_inicial = (float) $resultadonota['caixa_inicial'];
    }
    $PARTE1 = "
          <table class='table table-hover table-bordered' style='font-size:10pt;'> 
            <tr style='text-align:left; '>
                <TD rowspan='2'></TD>
                <td colspan=''><b>PAGAMENTO EM DINHEIRO</b></td>
                <td colspan=''><b>PAGAMENTO CARTÃO DÉBITO </b></td>
                <td colspan=''><b>PAGAMENTO PIX </b></td>
                <td colspan=''><b>PAGAMENTO CARTÃO CRÉDITO </b></td>
                <td colspan=''><b>PAGAMENTO CREDIÁRIO </b></td>
                <td colspan=''><b>TOTAL</b></td>
            </tr>
            ";

    //    $sqlNota2 = "SELECT * FROM usuarios ORDER BY cod ASC";

    $contadorindex = 0;
    $valorTotalFinal2 = 0;
    $valorTotalFinalDinheiro2 = 0;
    $valorTotalFinalCartao2 = 0;
    $valorTotalFinalCartaoDeb2 = 0;
    $valorTotalFinalCartaoCred2 = 0;
    $valorTotalFinalPix2 = 0;
    $valorTotalFinalCrediario2 = 0;

    //  $dataTableNota2 = $banco->ExecuteQuery($sqlNota2);
    // foreach ($dataTableNota2 as $resultadonota2) {
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 3 AND dia = $dia AND mes = $mes AND ano = $ano ORDER BY cod ASC");
    $sqlNota = "SELECT * FROM notas WHERE cod_caixa = :cod_caixa ORDER BY cod DESC";
    $paramNota = array(
      ":cod_caixa" => $cod_caixa,
    );
    $valorTotalFinal = 0;
    $valorTotalFinalDinheiro = 0;
    $valorTotalFinalCartao = 0;
    $valorTotalFinalCartaoDeb = 0;
    $valorTotalFinalCartaoCred = 0;
    $valorTotalFinalPix = 0;
    $valorTotalFinalCrediario = 0;

    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $qtdTotalFinal = 0;
      $nomecli = "";
      $codnota = $resultadonota['cod'];

      $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod DESC";
      $paramNotaPag = array(
        ":cod" => $codnota
      );

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        if ($resultadonotaPag['tipo'] == 1 && $resultadonotaPag['tipopag'] == 1) {
          $valorTotalFinalDinheiro = $valorTotalFinalDinheiro + $resultadonotaPag['total'];
          $valorTotalFinalDinheiro2 = $valorTotalFinalDinheiro2 + $resultadonotaPag['total'];
        }
        if ($resultadonotaPag['tipo'] == 1 && $resultadonotaPag['tipopag'] == 2) {
          $valorTotalFinalCartaoDeb = $valorTotalFinalCartaoDeb + $resultadonotaPag['total'];
          $valorTotalFinalCartaoDeb2 = $valorTotalFinalCartaoDeb2 + $resultadonotaPag['total'];
        }
        if ($resultadonotaPag['tipo'] == 1 && $resultadonotaPag['tipopag'] == 3) {
          $valorTotalFinalPix = $valorTotalFinalPix + $resultadonotaPag['total'];
          $valorTotalFinalPix2 = $valorTotalFinalPix2 + $resultadonotaPag['total'];
        }
        if ($resultadonotaPag['tipo'] == 2 && $resultadonotaPag['tipopag'] == 1) {
          $valorTotalFinalCartaoCred = $valorTotalFinalCartaoCred + $resultadonotaPag['total'];
          $valorTotalFinalCartaoCred2 = $valorTotalFinalCartaoCred2 + $resultadonotaPag['total'];
        }
        if ($resultadonotaPag['tipo'] == 2 && $resultadonotaPag['tipopag'] == 2) {
          $valorTotalFinalCrediario = $valorTotalFinalCrediario + $resultadonotaPag['total'];
          $valorTotalFinalCrediario2 = $valorTotalFinalCrediario2 + $resultadonotaPag['total'];
        }


        $valorTotalFinal = $valorTotalFinal + $resultadonotaPag['total'];
        $valorTotalFinal2 = $valorTotalFinal2 + $resultadonotaPag['total'];
      }
    }


    $PARTE2 = "
            <tr style='text-align:left;  '>
                
                <td>R$ " . number_format($valorTotalFinalDinheiro, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalCartaoDeb, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalPix, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalCartaoCred, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinalCrediario, 2, ',', '.') . "</td>
                <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                
            </tr>
        ";

    $PARTE3 = "
     <tr style='text-align:left; font-size:12pt; '>
         <td><b>FATURAMENTO</b></td>
         
         <td><b>R$ " . number_format($valorTotalFinalDinheiro2, 2, ',', '.') . "</b></td>
         <td><b>R$ " . number_format($valorTotalFinalCartaoDeb2, 2, ',', '.') . "</b></td>
         <td><b>R$ " . number_format($valorTotalFinalPix2, 2, ',', '.') . "</td>
         <td><b>R$ " . number_format($valorTotalFinalCartaoCred2, 2, ',', '.') . "</b></td>
         <td><b>R$ " . number_format($valorTotalFinalCrediario2, 2, ',', '.') . "</b></td>
         <td><b>R$ " . number_format($valorTotalFinal2, 2, ',', '.') . "</b></td>     
     </tr>
 ";

    $sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE cod_caixa = $cod_caixa ORDER BY id ASC");
    // Exibe todos os valores encontrados
    $totalfinaldiacat = 0;
    while ($dividadia = mysqli_fetch_object($sqlDividasDia)) {
      $cod = $dividadia->id;
      $pontos = ',';
      $result = str_replace($pontos, "", $dividadia->valor);
      $valor_total = (float) $result;
      $total = $valor_total;

      $totalfinaldiacat = $totalfinaldiacat + $total;
    }


    $PARTE4 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>CAIXA INICIAL</b></td>
         <td colspan='5'><b></b></td>
         <td colspan=''><b>R$ " . number_format($caixa_inicial, 2, ',', '.') . "</b></td>
     </tr>";

    $PARTE5 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>DESPESAS DO DIA</b></td>
         <td colspan='5'><b></b></td>
         <td colspan=''><b>R$ " . number_format($totalfinaldiacat, 2, ',', '.') . "</b></td>
     </tr>";
    $SALDODIA = 0;
    $SALDODIA = $valorTotalFinal2 - $totalfinaldiacat + $caixa_inicial;
    $PARTE6 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>SALDO DO DIA</b></td>
         <td colspan='5'><b></b></td>
         <td colspan=''><b>R$ " . number_format($SALDODIA, 2, ',', '.') . "</b></td>
     </tr>
     </table>";



     if($status_caixa==1){
      $resultado = "
      <div class='alert ' role='alert' >
        <div class='d-flex justify-content-end'>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
          <h4 class='alert-heading'>Caixa Cod. nº $codcaixa! 
              
              <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate
                enctype='multipart/form-data'>
                <input name='txtCod_caixa' id='txtCod_caixa' value='$codcaixa' type='hidden' />
  
                <input style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-DANGER'
                  type='submit' name='btnSubmitFecharCaixa' id='btnSubmitFecharCaixa' style=''
                  value='Fechar Caixa'>
              </form>
             
          </h4>
        <hr>  
        " . $PARTE1 . $PARTE2 . $PARTE3 . $PARTE4 . $PARTE5 . $PARTE6 . "
    </div>
  ";
     }else{
      $resultado = "
      <div class='alert ' role='alert' >
        <div class='d-flex justify-content-end'>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
          <h4 class='alert-heading'>Caixa Cod. nº $codcaixa! 
              
          
             
          </h4>
        <hr>  
        " . $PARTE1 . $PARTE2 . $PARTE3 . $PARTE4 . $PARTE5 . $PARTE6 . "
    </div>
  ";

     }
 

  }

  if ($_GET['msgget'] == 4) {

    $codprod = $_GET['codprod'];

    $sqlServicos2 = "SELECT * FROM servicos WHERE  cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos2 = array(
      ":cod" => $codprod
    );

    $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);

    if ($dataTableServicos2 != null) {
      $RESULTADOPARACADPRODUTO = "
        <div class='alert ' role='alert'>
            <div class='d-flex justify-content-end'>
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
       <table class='table table-striped table-sm'>    <thead>
        <thead>
                                    <tr style='text-align:left; font-size: 16pt;'>
    <td style='width:30%;'><b>Informações do Produto</b></td>
    <td><b>Cod. Barra</b></td>
    <td><b>Cod. Busca</b></td>
    <td><b>Informações de Estoque</b></td>
    <td></td>
  </tr>    </thead>";
      foreach ($dataTableServicos2 as $resultadoservicos) {
        $codservico = $resultadoservicos['cod'];
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
        $codbusca = $resultadoservicos['codbusca'];
        $codbarra = $resultadoservicos['codbarra'];


        $qtdservico = $resultadoservicos['qtd'];
        $estmax = $resultadoservicos['est_max'];
        $estmim = $resultadoservicos['est_mim'];
        $fornecedor = $resultadoservicos['fornecedor'];
        $tiposerv = $resultadoservicos['tipo'];

        $descricaoservico = $resultadoservicos['descricao'];
        $valorunt = number_format($resultadoservicos['valor'], 2, ',', '.');

        $sqlSerTeste = "SELECT * FROM pedidos WHERE servico = $codservico ORDER BY cod ASC LIMIT 1";
        $contadorindex = 4;

        $dataTableSerTeste = $banco->ExecuteQuery($sqlSerTeste);
        if ($dataTableSerTeste == null) {
          $textoteste = "<a onclick='PagPesquisarProdutos(2, txtNomeProdutoP.value, $codservico)' href='javascript: func' style='color:#fff; width:100%;  margin-top:5px;' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span> Apagar</a>";
        } else {
          $textoteste = "";
        }

        $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "
<tr style='text-align:left; font-size: 14pt;'>
  <td style='width:30%;'>
                                    <b>Nome:</b>$nomeservico</br>
                                    <b>Descrição:</b>$descricaoservico</br>
                                    <b>Categoria:</b>$nomecategoria</br>
                                    <b>Valor Unt.:</b>R$ $valorunt</br>
                                    </td>
                                <td>$codbarra</td>
  <td>$codbusca</td>
  <td>
                                ";
        if ($tiposerv == 0) {
          $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "
                                       <small style='color:red;'> Este produto não possui controle de estoque
                                        </small>";
        } else {
          $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "
                                <b>Qtd:</b>$qtdservico</br>
                                    <b>Estoque Max.:</b>$estmax</br>
                                    <b>Estoque Mín.:</b>$estmim</br>
                                    <b>Fornecedor:</b> $fornecedor
                                  
                               ";
        }
        $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "  </td> <td>
                                <a tabindex='$contadorindex' onclick='PagPesquisarProdutos(3, 1, $codservico)' href='javascript: func' style='margin-top:5px; color:#fff; font-size: 11pt; width:100%;' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-retweet'></span>  Inf. Produto</a></br>
  <a tabindex='$contadorindex' onclick='PagPesquisarProdutos(8, 1, $codservico)' href='javascript: func' style='color:#fff; margin-top:5px;font-size: 11pt; width:100%;' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-retweet'></span> Inf. de Busca</a></br>
  <a tabindex='$contadorindex' onclick='PagPesquisarProdutos(9, 1, $codservico)' href='javascript: func' style='color:#fff; margin-top:5px; font-size: 11pt;width:100%;' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-retweet'></span>  Inf. de Estoque</a>
                                <a tabindex='$contadorindex' onclick='PagPesquisarProdutos(10, 1, $codservico)' href='javascript: func' style='color:#fff; margin-top:5px; font-size: 11pt;width:100%;' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-retweet'></span>  Imagem</a>
                                $textoteste</td>
</tr>
";
        $contadorindex++;
      }


      $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "</table></div>";

      $resultado = $RESULTADOPARACADPRODUTO;
    }

  }

  if ($_GET['msgget'] == 5) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>Categoria Cadastrada com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }

  if ($_GET['msgget'] == 6) {
    $resultado = "
    <div class='alert alert-danger' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>Categoria Deletada com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }

  if ($_GET['msgget'] == 7) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>Imagem atualizada com sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 8) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>Usuário Cadastrado com sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 9) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Imagem atualizada com sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 10) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Despesa Cadastrada Com sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 11) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Categoria de Despesa Cadastrada Com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 12) {
    $resultado = "
    <div class='alert alert-danger' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Categoria de Despesa Deletada Com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }

  if ($_GET['msgget'] == 13) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Dados do Usuário Atualizado com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }

  
  if ($_GET['msgget'] == 14) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Configurações do Usuário Atualizada com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }



}
//FIM FUNCOES PARA PEGAR MENSAGEM DO SUBMIT ANTERIOR

//INICIO DE FUNCOES DE SUBMIT

//INICIO SUBMIT PARA REALIZAR LOGIN
if (filter_input(INPUT_POST, "btnEntrar", FILTER_SANITIZE_STRING)) {

  $usuarioController = new UsuarioController();
  $user = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING);
  $pass = filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING);
  $permissao = 1;

  $resultado = $usuarioController->AutenticarUsuario($user, $pass, $permissao);

  if ($resultado != null) {
    if (filter_input(INPUT_POST, "ckManterLogado", FILTER_SANITIZE_STRING)) {
      $_SESSION["entrarAdminE"] = true;
    }


    $_SESSION["permissaoF"] = $resultado->getPermissao();
    $_SESSION["codF"] = $resultado->getCod();
    $_SESSION["nomeF"] = $resultado->getNome();
    $_SESSION["cod_orgaoF"] = $resultado->getCod_orgao();
    $_SESSION["funcaoF"] = $resultado->getFuncao();
    $_SESSION["logadoF"] = true;
    if ($resultado->getPermissao() == 1) {

      header("Location: index.php?");
    } else if ($resultado->getPermissao() == 2) {

      header("Location: index.php?pagina=homefunc");
    } else if ($resultado->getPermissao() == 3) {

      header("Location: index.php?pagina=hometelao");
    }
  } else {
    $retorno = "<div class=\"alert alert-danger\" role=\"alert\">Usuário ou senha inválido.</div>";
  }
}
//FIM SUBMIT PARA REALIZAR LOGIN

//SUBMIT PARA INICIAR UMA VENDA EXPRESSA, RETIRADA OU ENTREGA
if (filter_input(INPUT_POST, "btnSubmitPedirVendaExpressa", FILTER_SANITIZE_STRING)) {
  $status = 1;
  $usuario1 = filter_input(INPUT_POST, "txtCodCliente", FILTER_SANITIZE_NUMBER_INT);
  $tipoentrega = filter_input(INPUT_POST, "txtTipoEntrega", FILTER_SANITIZE_NUMBER_INT);
  $cod_caixa = filter_input(INPUT_POST, "txtCod_caixa", FILTER_SANITIZE_NUMBER_INT);
  $func = $_SESSION["codF"];

  $data_hoje2 = date('d/m/Y');

  $t = explode("/", $data_hoje2);
  $dia = $t[0];
  $mes = $t[1];
  $ano = $t[2];


  if ($tipoentrega == 1) {
    $sqlNotas = "SELECT * FROM notas WHERE tipo_pedido = 1 ORDER BY cod DESC LIMIT 1";
    $ordem = 0;

    $dataTableNotas = $banco->ExecuteQuery($sqlNotas);
    foreach ($dataTableNotas as $resultadonotas) {
      $ordem = $resultadonotas['ordem'];
      if ($ordem < 999) {
        $ordem = $ordem + 1;
      } elseif ($ordem == 999) {
        $ordem = 1;
      }
    }
  } else {
    $ordem = 0;
  }


  $notas = new Notas();
  $notas->setStatus($status);
  $notas->setUsuario($usuario1);
  $notas->setDia($dia);
  $notas->setMes($mes);
  $notas->setAno($ano);
  $notas->setFunc($func);
  $notas->setOrdem($ordem);
  $notas->setTipo_entrega($tipoentrega);
  $notas->setCod_caixa($cod_caixa);


  $cod_nota_ult = 0;
  if ($notasController->Cadastrar($notas)) {
    $cod_nota_ult = $notasController->RetornarUltimaNotaFunc($_SESSION['codF']);

    header("location: index.php?pagina=carinhocompras&cod=$cod_nota_ult");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM SUBMIT PARA PEDIDOS

//SUBMIT PARA FECHAR O CAIXA
if (filter_input(INPUT_POST, "btnSubmitFecharCaixa", FILTER_SANITIZE_STRING)) {
  $status = 2;
  $cod_caixa = filter_input(INPUT_POST, "txtCod_caixa", FILTER_SANITIZE_NUMBER_INT);
  $func = $_SESSION["codF"];

  $hora = date('d/m/Y H:i');

  $sql = "UPDATE fechar_caixa SET status = :status, hora_fechamento = :hora WHERE cod = :cod";
  $param = array(
    ":status" => $status,
    ":hora" => $hora,
    ":cod" => $cod_caixa
  );

  if ($banco->ExecuteNonQuery($sql, $param)) {
    header("location: index.php?");
    header("Location: index.php?&codcaixa=$cod_caixa&msgget=3&finalizadocomsucesso");
  } else {
    header("Location: index.php?&codcaixa=$cod_caixa&msgget=3&deuerrado");
  }
}
//FIM SUBMIT PARA PEDIDOS


//SUBMIT PARA ATUALIZAR CLIENTE EM NOTAS
if (filter_input(INPUT_POST, "btnSubmitClienteNovaVenda", FILTER_SANITIZE_STRING)) {


  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $codcliente = filter_input(INPUT_POST, "txtCodCliente", FILTER_SANITIZE_NUMBER_INT);

  $sql = "UPDATE notas SET usuario = :usuario WHERE cod = :cod";
  $param = array(
    ":usuario" => $codcliente,
    ":cod" => $codnota
  );

  if ($banco->ExecuteNonQuery($sql, $param)) {
    header("Location: index.php?pagina=carinhocompras&cod=$codnota&finalizadocomsucesso");
  } else {
    header("Location: index.php?pagina=carinhocompras&cod=$codnota&deuerrado");
  }
}
//FIM DO SUBMIT PARA ATUALIZAR CLIENTE EM VENDAS

//SUBMIT PARA CADASTRO RAPIDO DE CLIENTE
if (filter_input(INPUT_POST, "btnSubmitClienteCadastroRapido", FILTER_SANITIZE_STRING)) {


  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $contato = filter_input(INPUT_POST, "txtContatoCadRes", FILTER_SANITIZE_STRING);
  $nomecompleto = filter_input(INPUT_POST, "txtNomeCompletoCadRes", FILTER_SANITIZE_STRING);

  $data = date("d/m/Y");

  $clientes = new Clientes();
  $clientes->setNome($nomecompleto);
  $clientes->setCelular($contato);
  $clientes->setData($data);


  if ($clientesController->Cadastrar($clientes)) {
    $codcliente = $clientesController->RetornarUltimoClientes();
    header("Location: index.php?&codcli=$codcliente&msgget=1");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM DO SUBMIT PARA CADASTRO RAPIDO DE CLIENTE

//SUBMIT PARA CADASTRO COMLETO DE CLIENTE
if (filter_input(INPUT_POST, "btnSubmitClienteCadastroCompleto", FILTER_SANITIZE_STRING)) {


  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $nomecompleto = filter_input(INPUT_POST, "txtNomeCadCom", FILTER_SANITIZE_STRING);
  $contato = filter_input(INPUT_POST, "txtContatoCadCom", FILTER_SANITIZE_STRING);
  $endereco = filter_input(INPUT_POST, "txtEnderecoCadCom", FILTER_SANITIZE_STRING);
  $numero = filter_input(INPUT_POST, "txtNCadCom", FILTER_SANITIZE_STRING);
  $bairro = filter_input(INPUT_POST, "txtBairroCadCom", FILTER_SANITIZE_STRING);
  $complemento = filter_input(INPUT_POST, "txtComplementoCadCOm", FILTER_SANITIZE_STRING);
  $datanascimento = filter_input(INPUT_POST, "txtDataNascimentoCadCom", FILTER_SANITIZE_STRING);

  $data = date("d/m/Y");

  $clientes = new Clientes();
  $clientes->setNome($nomecompleto);
  $clientes->setCelular($contato);
  $clientes->setEndereco($endereco);
  $clientes->setNumero($numero);
  $clientes->setBairro($bairro);
  $clientes->setComplemento($complemento);
  $clientes->setNascimento($datanascimento);
  $clientes->setData($data);


  if ($clientesController->Cadastrar($clientes)) {
    $codcliente = $clientesController->RetornarUltimoClientes();
    header("Location: index.php?&codcli=$codcliente&msgget=1");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM DO SUBMIT PARA CADASTRO COMPLETO DE CLIENTE


//INICIO FUNCAO DE ABERTURA DE MOVIMENTOS DE CAIXA
if (filter_input(INPUT_POST, "btnSubmitIniciarCaixa", FILTER_SANITIZE_STRING)) {
  $status = 1;
  $cod_funcionario = filter_input(INPUT_POST, "txtCodFuncionario", FILTER_SANITIZE_NUMBER_INT);
  $caixainicial = filter_input(INPUT_POST, "txtCaixainicial", FILTER_SANITIZE_STRING);

  $pontos = '.';
  $result = str_replace($pontos, "", $caixainicial);
  $result = str_replace(",", ".", $result);
  $caixainicial = (float) $result;

  $data_hoje2 = date('d/m/Y');
  
  

  $t = explode("/", $data_hoje2);
  $dia = $t[0];
  $mes = $t[1];
  $ano = $t[2];

  $hora_inicio = date('d/m/Y H:i');

  $query = mysqli_query($conn, "INSERT INTO `fechar_caixa` (cod_funcionario,status, dia, mes, ano, hora_inicio, caixa_inicial) VALUES ($cod_funcionario, $status, $dia, $mes, $ano, '$hora_inicio', '$caixainicial')");
  // Se i// Se inserido com scesso
  if ($query) {
    $sqlPedidos23 = "SELECT * FROM fechar_caixa WHERE cod_funcionario = :cod AND status = 1 ORDER BY cod DESC LIMIT 1";
    $paramPedidos23 = array(
      ":cod" => $_SESSION['codF']
    );

    $dataTablePedidos23 = $banco->ExecuteQuery($sqlPedidos23, $paramPedidos23);
    foreach ($dataTablePedidos23 as $resultadocaixa) {
      $cod = $resultadocaixa['cod'];
    }
    header("location: index.php?&codcaixa=$cod&msgget=3");
  }
}
//FUNCAO DE ABERTURA DE MOVIMENTOS DE CAIXA

//INICIO DA FUNCAO PARA CADASTRAR NOVA DESPESA

if (filter_input(INPUT_POST, "btnCadastrarNovaDespesa", FILTER_SANITIZE_STRING)) {
  $fornecedor = 0;
  $descricaodespesa = (filter_input(INPUT_POST, "txtDescricaoDespesa", FILTER_SANITIZE_STRING));
  $valor = (filter_input(INPUT_POST, "txtValorDespesa", FILTER_SANITIZE_STRING));
  $codcaixadespesa = (filter_input(INPUT_POST, "txtCodcaixadespesa", FILTER_SANITIZE_NUMBER_INT));
  $diadespesa = (filter_input(INPUT_POST, "txtDiaDepesa", FILTER_SANITIZE_NUMBER_INT));
  $mesdespesa = (filter_input(INPUT_POST, "txtMesDespesa", FILTER_SANITIZE_NUMBER_INT));
  $anodespesa = (filter_input(INPUT_POST, "txtAnoDespesa", FILTER_SANITIZE_NUMBER_INT));
  $categoriadespesa = (filter_input(INPUT_POST, "txtCategoriaDespesa", FILTER_SANITIZE_NUMBER_INT));

  $pontos = '.';
  $result = str_replace($pontos, "", $valor);
  $result = str_replace(",", ".", $result);
  $valor = $result;



  $erros = ValidarDespesa();


  if (empty($erros)) {
    $movimentoempresa = new MovimentoEmpresa();
    $movimentoempresa->setDescricao($descricaodespesa);
    $movimentoempresa->setValor($valor);
    $movimentoempresa->setDia($diadespesa);
    $movimentoempresa->setMes($mesdespesa);
    $movimentoempresa->setAno($anodespesa);
    $movimentoempresa->setCod_usu(1);
    $movimentoempresa->setTipo(1);
    $movimentoempresa->setCat($categoriadespesa);
    $movimentoempresa->setCod_caixa($codcaixadespesa);


    if ($movimentoEmpresaController->Cadastrar($movimentoempresa)) {

      $codultcod = $movimentoEmpresaController->RetornarUltCad(1);


      header("Location: index.php?&msgget=10&coddesp=$codultcod");
      $resultado = " <div class='alert alert-success' role='alert'><span>Produto cadastrado com sucesso! </span> </div>";
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Serviço!</span> </div>";
    }
  }
}
function ValidarDespesa()
{
  $listaErros = [];

  $movimentoEmpresaController = new MovimentoEmpresaController();

  if (strlen(filter_input(INPUT_POST, "txtDescricaoDespesa", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Descrição da Despesa inválida.";
  }
  if (strlen(filter_input(INPUT_POST, "txtValorDespesa", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Valor inválido.";
  }

  return $listaErros;
}
// FIM DA FUNCAO PARA CADASTRAR NOVA DESPESA

//INICIO DA FUNCAO PARA CADASTRAR NOVO PRODUTOO

if (filter_input(INPUT_POST, "btnCadastrarNovoProduto", FILTER_SANITIZE_STRING)) {
  $fornecedor = 0;
  $nome = (filter_input(INPUT_POST, "txtNomeProduto", FILTER_SANITIZE_STRING));
  $descricao = (filter_input(INPUT_POST, "txtDescricaoProduto", FILTER_SANITIZE_STRING));
  $valor = (filter_input(INPUT_POST, "txtValorUntProduto", FILTER_SANITIZE_STRING));
  $cat = (filter_input(INPUT_POST, "txtCategoria", FILTER_SANITIZE_NUMBER_INT));
  $tipo = (filter_input(INPUT_POST, "txtTipo", FILTER_SANITIZE_NUMBER_INT));
  $estmax = (filter_input(INPUT_POST, "txtEstMax", FILTER_SANITIZE_NUMBER_INT));
  $estmin = (filter_input(INPUT_POST, "txtEstMin", FILTER_SANITIZE_NUMBER_INT));
  $fornecedor = (filter_input(INPUT_POST, "txtFornecedor", FILTER_SANITIZE_NUMBER_INT));
  $codbarra = (filter_input(INPUT_POST, "txtCodBarra", FILTER_SANITIZE_NUMBER_INT));

  $pontos = '.';
  $result = str_replace($pontos, "", $valor);
  $result = str_replace(",", ".", $result);
  $valor = $result;



  $erros = Validar();


  if (empty($erros)) {
    $servicos = new Servicos();
    $servicos->setNome($nome);
    $servicos->setDescricao($descricao);
    $servicos->setValor($valor);
    $servicos->setCategoria($cat);
    $servicos->setTipo($tipo);
    $servicos->setEst_max($estmax);
    $servicos->setEst_mim($estmin);
    $servicos->setFornecedor($fornecedor);
    $servicos->setCodbarra($codbarra);
    $servicos->setImg(null);


    if ($servicoController->Cadastrar($servicos)) {
      $cod = 0;
      $nome = "";
      $descricao = "";
      $valor = "";
      $categoria = 0;
      $codbarra = "";
      $codultcod = $servicoController->RetornarUltCad(1);


      header("Location: index.php?&msgget=4&codprod=$codultcod");
      $resultado = " <div class='alert alert-success' role='alert'><span>Produto cadastrado com sucesso! </span> </div>";
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Serviço!</span> </div>";
    }
  }
}
function Validar()
{
  $listaErros = [];

  $servicosController = new ServicoController();

  if (strlen(filter_input(INPUT_POST, "txtNomeProduto", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Nome Produto inválido.";
  }
  if (strlen(filter_input(INPUT_POST, "txtValorUntProduto", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Valor inválido.";
  }

  return $listaErros;
}
// FIM DA FUNCAO PARA CADASTRAR NOVO PRODUTO

//INICIO SUBMIT PARA CADASTRAR NOVA CATEGORIA E APAGAR CATEGORIA
if (filter_input(INPUT_POST, "btnCadastrarCategoria", FILTER_SANITIZE_STRING)) {

  $nome = (filter_input(INPUT_POST, "txtNomeCategoria", FILTER_SANITIZE_STRING));
  $erros = ValidarCat();


  if (empty($erros)) {

    $catserfin = new CategoriaSerFin();
    $catserfin->setNome($nome);


    //Cadastrar
    if ($categoriaserfinController->Cadastrar($catserfin)) {
      $cod = 0;
      $nome = "";

      header("Location: index.php?&msgget=5");
      $resultado = " <div class='alert alert-success' role='alert'><span>Categoria cadastrada com sucesso! </span> </div>";
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar nova categoria!</span> </div>";
    }
  }
}
if (filter_input(INPUT_POST, "btnApagarCategoria", FILTER_SANITIZE_STRING)) {

  if ($categoriaserfinController->Deletar(filter_input(INPUT_POST, "codcategoria", FILTER_SANITIZE_NUMBER_INT))) {
    $cod = 0;
    $nome = "";
    $descricao = "";
    $valor = "";
    $resultado = " <div class='alert alert-success' role='alert'><span>Serviço excluído com sucesso! </span> </div>";

    header("Location: index.php?&msgget=6");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao excluir o Serviço!</span> </div>";
  }
}

function ValidarCat()
{
  $listaErros = [];

  $categoriaserfinController = new CategoriaSerFinController();
  if (strlen(filter_input(INPUT_POST, "txtNomeCategoria", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Nome Categoria inválido.";
  }

  return $listaErros;
}
//FIM SUBMIT PARA CADASTRAR NOVA CATEGORIA E APAGAR CATEGORIA

//INICIO SUBMIT PARA CADASTRAR NOVA CATEGORIA DESPESA E APAGAR CATEGORIA DESPESA
if (filter_input(INPUT_POST, "btnCadastrarCategoriaDespesa", FILTER_SANITIZE_STRING)) {

  $nome = (filter_input(INPUT_POST, "txtNomeCategoriaDespesa", FILTER_SANITIZE_STRING));
  $erros = ValidarCatDespesa();


  if (empty($erros)) {

    $categoriafinanceiro = new CategoriaFinanceiro();
    $categoriafinanceiro->setNome($nome);
    $categoriafinanceiro->setCod_usu(1);


    //Cadastrar
    if ($categoriaFinanceiroController->Cadastrar($categoriafinanceiro)) {
      $cod = 0;
      $nome = "";

      header("Location: index.php?&msgget=11");
      $resultado = " <div class='alert alert-success' role='alert'><span>Categoria cadastrada com sucesso! </span> </div>";
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar nova categoria!</span> </div>";
    }
  }
}
if (filter_input(INPUT_POST, "btnApagarCategoriaDespesa", FILTER_SANITIZE_STRING)) {

  if ($categoriaFinanceiroController->Deletar2(filter_input(INPUT_POST, "codcategoriadesp", FILTER_SANITIZE_NUMBER_INT))) {
    $cod = 0;
    $nome = "";
    $descricao = "";
    $valor = "";
    $resultado = " <div class='alert alert-success' role='alert'><span>Serviço excluído com sucesso! </span> </div>";

    header("Location: index.php?&msgget=12");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao excluir o Serviço!</span> </div>";
  }
}

function ValidarCatDespesa()
{
  $listaErros = [];

  $categoriaserfinController = new CategoriaSerFinController();
  if (strlen(filter_input(INPUT_POST, "txtNomeCategoriaDespesa", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Nome Categoria inválido.";
  }

  return $listaErros;
}
//FIM SUBMIT PARA CADASTRAR NOVA CATEGORIA E APAGAR CATEGORIA

//SUBMIT PARA ATUALIZAR IMAGEM DO PRODUTO
if (filter_input(INPUT_POST, "btnAlterarImagem", FILTER_SANITIZE_STRING)) {
  $cod = (filter_input(INPUT_POST, "txtCodser", FILTER_SANITIZE_NUMBER_INT));

  $upload = new Upload();
  $thumb = filter_input(INPUT_POST, "txtImagemAtual", FILTER_SANITIZE_STRING);
  $nomeImagem = $upload->LoadFile("Interface/img/Servicos/", "img", $_FILES["flImagemLivre"]);
  if ($nomeImagem != "" && $nomeImagem != null) {
    if ($servicoController->AlterarImagem($nomeImagem, $cod)) {
      unlink("$thumb");
      //header("Location: index.php?pagina=produtos");
      $codultcod = $cod;

      header("Location: index.php?&msgget=7&codprod=$codultcod");

      $resultado = "<div class=\"alert alert-success\" role=\"alert\">Imagem alterada com sucesso</div>";
    } else {
      $resultado = "<div class=\"alert alert-danger\" role=\"alert\">APERTE F5 para tentar novamente. Caso o erro persista tente iniciar o processo novamente!</div>";
      unlink("Interface/img/Servicos/{$nomeImagem}");
    }
  } else if ($nomeImagem == null) {
    $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Formato de imagem inválido.</div>";
  } else {
    $resultado = "<div class=\"alert alert-danger\" role=\"alert\">APERTE F5 para tentar novamente. Caso o erro persista tente iniciar o processo novamente!</div>";
  }
}
//FIM DO SUBMIT PARA ATUALIZAR IMAGEM DO PRODUTO


//SUBMIT PARA ATUALIZAR IMAGEM DO USUARIO
if (filter_input(INPUT_POST, "btnAlterarImagemUsu", FILTER_SANITIZE_STRING)) {
  $cod = (filter_input(INPUT_POST, "txtCodser", FILTER_SANITIZE_NUMBER_INT));

  $upload = new Upload();
  $thumb = filter_input(INPUT_POST, "txtImagemAtual", FILTER_SANITIZE_STRING);
  $nomeImagem = $upload->LoadFile("Interface/img/Usuarios/", "img", $_FILES["flImagemLivre"]);
  if ($nomeImagem != "" && $nomeImagem != null) {
    if ($usuarioController->AlterarImagemUsu($nomeImagem, $cod)) {
      unlink("$thumb");
      //header("Location: index.php?pagina=produtos");
      $codultcod = $cod;

      header("Location: index.php?&msgget=9&codusu=$codultcod");

      $resultado = "<div class=\"alert alert-success\" role=\"alert\">Imagem alterada com sucesso</div>";
    } else {
      $resultado = "<div class=\"alert alert-danger\" role=\"alert\">APERTE F5 para tentar novamente. Caso o erro persista tente iniciar o processo novamente!</div>";
      unlink("Interface/img/Usuarios/{$nomeImagem}");
    }
  } else if ($nomeImagem == null) {
    $resultado = "<div class=\"alert alert-danger\" role=\"alert\">Formato de imagem inválido.</div>";
  } else {
    $resultado = "<div class=\"alert alert-danger\" role=\"alert\">APERTE F5 para tentar novamente. Caso o erro persista tente iniciar o processo novamente!</div>";
  }
}
//FIM DO SUBMIT PARA ATUALIZAR IMAGEM DO USUARIO


//SUBMIT PARA CADASTRAR NOVO Usuário

if (filter_input(INPUT_POST, "btnSubmitNovoUsuario", FILTER_SANITIZE_STRING)) {

  $erros = ValidarNovoUsu();

  $nome = filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING);
  $usuario2 = filter_input(INPUT_POST, "txtLogin", FILTER_SANITIZE_STRING);
  $celular = filter_input(INPUT_POST, "txtCelular", FILTER_SANITIZE_STRING);
  $senha = filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING);
  $permissao = filter_input(INPUT_POST, "txtPermissaoCad", FILTER_SANITIZE_NUMBER_INT);
  $senha = md5($senha);

  if (empty($erros)) {

    $usuario = new Usuarios();



    $usuario->setNome($nome);
    $usuario->setUsuario($usuario2);
    $usuario->setPermissao(2);
    $usuario->setCelular($celular);
    $usuario->setSenha($senha);
    $usuario->setPermissao($permissao);

    //Cadastrar
    $upload = new Upload();
    $nomeImagem = $upload->LoadFile("Interface/img/Usuarios/", "img", $_FILES["flImagem"]);
    $usuario->setFoto($nomeImagem);
    if ($usuarioController->Cadastrar($usuario)) {

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

      $comissao = 0;
      $listaUsuariosBusca = $usuarioController->RetornarUsuarios("", 1, 1);

      header("Location: index.php?&msgget=8");

      //$resultado = " <div class='alert alert-success' role='alert'><span>Usuario cadastrado com sucesso!</span> </div>";
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao cadastrar Usuario!</span> </div>";
    }

  }
}


function ValidarNovoUsu()
{
  $listaErros = [];

  $usuarioController = new UsuarioController();


  if (strlen(filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Nome inválido.";
  }

  if (strlen(filter_input(INPUT_POST, "txtLogin", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Usuario inválido.";
  }



  return $listaErros;
}

//FIM SUBMIT PARA CADASTRAR NOVO USUÁRIO


if (filter_input(INPUT_POST, "btnEditarDadosUsu", FILTER_SANITIZE_STRING)) {

  $erros = ValidarEditarUsu();

  $cod = filter_input(INPUT_POST, "codusu", FILTER_SANITIZE_NUMBER_INT);
  $nome = filter_input(INPUT_POST, "txtEditarNomeUsu", FILTER_SANITIZE_STRING);
  $usuario2 = filter_input(INPUT_POST, "txtEditarUsuarioUsu", FILTER_SANITIZE_STRING);
  $cpf = filter_input(INPUT_POST, "txtEditarCpfUsu", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "txtEditarEmailUsu", FILTER_SANITIZE_STRING);
  $contato = filter_input(INPUT_POST, "txtEditarContatoUsu", FILTER_SANITIZE_STRING);
  $rua = filter_input(INPUT_POST, "txtEditarRuaUsu", FILTER_SANITIZE_STRING);
  $numero = filter_input(INPUT_POST, "txtEditarNumeroUsu", FILTER_SANITIZE_STRING);
  $bairro = filter_input(INPUT_POST, "txtEditarBairroUsu", FILTER_SANITIZE_STRING);


  if (empty($erros)) {

    $usuario = new Usuarios();



    $usuario->setCod($cod);
    $usuario->setNome($nome);
    $usuario->setUsuario($usuario2);
    $usuario->setCpf($cpf);
    $usuario->setEmail($email);
    $usuario->setCelular($contato);
    $usuario->setRua($rua);
    $usuario->setNumero($numero);
    $usuario->setBairro($bairro);

    var_dump($usuario);



    if ($usuarioController->Alterar($usuario)) {


      header("Location: index.php?&msgget=13");
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao alterar dados do usuario!</span> </div>";
    }
  }
}

function ValidarEditarUsu()
{
  $listaErros = [];

  $usuarioController = new UsuarioController();


  if (strlen(filter_input(INPUT_POST, "txtEditarNomeUsu", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Nome inválido.";
  }

  if (strlen(filter_input(INPUT_POST, "txtEditarUsuarioUsu", FILTER_SANITIZE_STRING)) < 1) {
    $listaErros[] = "- Usuario inválido.";
  }



  return $listaErros;
}
//SUBMIT PARA EDITAR CONFIGURACOES DO USUÁRIO

if (filter_input(INPUT_POST, "btnEditarConfiguracoes", FILTER_SANITIZE_STRING)) {


  $cod = filter_input(INPUT_POST, "codusu", FILTER_SANITIZE_NUMBER_INT);
  $tipoempresa = filter_input(INPUT_POST, "txtTipoEmpresa", FILTER_SANITIZE_STRING);
  $tipoentrega = filter_input(INPUT_POST, "txtTipoEntrega", FILTER_SANITIZE_STRING);
  $opcaopreco = filter_input(INPUT_POST, "txtOpcaoPreco", FILTER_SANITIZE_STRING);
  $etapa = filter_input(INPUT_POST, "txtEtapa", FILTER_SANITIZE_STRING);
 
  $sql = "UPDATE usuarios SET tipoempresa = :tipoempresa, entrega = :tipoentrega, opcaopreco = :opcaopreco, etapas = :etapas WHERE cod = :cod";
  $param = array(
    ":cod" => $cod,
    ":tipoempresa" => $tipoempresa,
    ":tipoentrega" => $tipoentrega,
    ":opcaopreco" => $opcaopreco,
    ":etapas" => $etapa,
  );
  

  if ($banco->ExecuteNonQuery($sql, $param)) {
      
  header("Location: index.php?&msgget=14");

  } else {
       
  $resultado = "ERRO AO ATUALIZAR DADOS";

  }
  
}


//SUBMIT PARA EDITAR DADOS DO USUÁRIO

//FIM DAS FUNCOES DE SUBMIT




?>


<!DOCTYPE html>
<!-- saved from url=(0052)https://getbootstrap.com/docs/5.0/examples/sidebars/ -->
<html lang="pt" class="">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=0.5">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="generator" content="">
  <title>DopDin</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">

  <!-- Bootstrap core CSS -->
  <link href="./Barras laterais · Bootstrap v5.0_files/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="manifest" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/manifest.json">
  <link rel="icon" href="Interface/img/logo.ico">
  <meta name="theme-color" content="#fff">


  <link rel="manifest" href="https://coreui.io/demos/bootstrap/5.0/free/assets/favicon/manifest.json">
<link rel="stylesheet" href="./modelo_files/simplebar.css">
<link rel="stylesheet" href="./modelo_files/simplebar(1).css">
<!-- We use those styles to show code examples, you should remove them in your application.-->
<!-- We use those styles to style Carbon ads and CoreUI PRO banner, you should remove them in your application.-->
<script src="./modelo_files/config.js.download"></script>
<script src="./modelo_files/color-modes.js.download"></script>

  <!-- Custom styles for this template -->
  <link href="./Barras laterais · Bootstrap v5.0_files/sidebars.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" charset="UTF-8" href="./Barras laterais · Bootstrap v5.0_files/m=el_main_css">
</head>

<body >
  <?PHP
  if (!isset($_SESSION["permissaoF"])) {
    ?>
    <!-- Section: Design Block -->
    <section style="margin-top:-20px;" class="background-radial-gradient overflow-hidden">


      <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
        <div class="row gx-lg-5 align-items-center mb-5">
          <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
            <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
              DopDin, O melhor sistema
              <span style="color: hsl(218, 81%, 75%)">de controle para seu négocio</span>
            </h1>
            <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
              O DopDin é uma plataforma web desenvolvida para otimizar a gestão de vendas, faturamento, despesas e estoque
              em pequenas e médias empresas. Com uma interface intuitiva e ferramentas avançadas, o sistema automatiza
              processos cruciais, oferecendo controle total e informações estratégicas para tomada de decisões. Ideal para
              quem busca eficiência e crescimento sustentável, o DopDin transforma a administração financeira em um
              processo simples e eficaz.
            </p>
          </div>

          <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
            <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
            <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

            <div class="card bg-glass">
              <div class="card-body px-4 py-5 px-md-5">
                <form method="post" name="frmCadastro" id="frmCadastro" class="form-signin" novalidate
                  enctype="multipart/form-data">
                  <!-- 2 column grid layout with text inputs for the first and last names -->
                  <div class="row">



                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                      <input autofocus type="text" id="txtUsuario" name="txtUsuario" class="form-control" />
                      <label class="form-label" for="form3Example3">Usuário</label>
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" id="txtSenha" name="txtSenha" class="form-control" />
                      <label class="form-label" for="form3Example4">Senha</label>
                    </div>



                    <!-- Submit button -->
                    <input value="Login" id='btnEntrar' name='btnEntrar' type="submit" style='padding:20px;'
                      data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary btn-block mb-4" />

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Section: Design Block -->

  <?php } else {
    $cod_funcionario = (int) $_SESSION['codF'];
    $permissao_funcionario = (int) $_SESSION['permissaoF'];
    require_once("Util/ResquestPagePrincipal.php");
    ?>

    <?php
  } ?>


  <script src="./Barras laterais · Bootstrap v5.0_files/bootstrap.bundle.min.js.download"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

  <script src="./Barras laterais · Bootstrap v5.0_files/sidebars.js.download"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</body>
<script>
  function alternarDivs(tipo) {


    var div1 = document.getElementById("DivHome");
    var div2 = document.getElementById("DivProdutos");
    var div3 = document.getElementById("DivRelatorios");
    var div4 = document.getElementById("DivUsuarios");
    var div5 = document.getElementById("DivContas");
    var div6 = document.getElementById("DivDespesas");
    var div7 = document.getElementById("DivConfiguracoes");
    var div8 = document.getElementById("DivMinhaConta");

    var div9 = document.getElementById("ResultadoPesquisaProduto");
    var div10 = document.getElementById("DivCadastroProduto");
    var div11 = document.getElementById("DivCadastroCategoria");
    var div12 = document.getElementById("DivCadastroEntrada");

    var div13 = document.getElementById("DivCadastroUsuario");
    var div14 = document.getElementById("ResultadoPesquisaUsuario");


    var div15 = document.getElementById("ResultadoPesquisaDespesa");
    var div16 = document.getElementById("DivCadastroDespesa");
    var div17 = document.getElementById("DivCadastroCategoriaDespesa");


    var div18 = document.getElementById("BtnMostrarFoto");
    var div19 = document.getElementById("BtnMostrarPerfil");
    var div20 = document.getElementById("DivParaAtualizarDadosUSu");
    var div21 = document.getElementById("DivParaTrocarImagemUsu");
    var div22 = document.getElementById("DivParaGerarRelatorioDespesa");



    var div1b = document.getElementById("BotaoHome");
    var div2b = document.getElementById("BotaoProdutos");
    var div3b = document.getElementById("BotaoRelatorios");
    var div4b = document.getElementById("BotaoUsuarios");
    var div5b = document.getElementById("BotaoContas");
    var div6b = document.getElementById("BotaoDespesas");
    var div7b = document.getElementById("BotaoConfiguracoes");
    var div8b = document.getElementById("BotaoMinhaConta");



    // Ocultar div1 e desocultar div2

    var teste = tipo;
    if (teste == 1) {

      div1.style.display = "block";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "none";


      div1b.className = "nav-link active py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";


    }
    if (teste == 2) {

      div1.style.display = "none";
      div2.style.display = "block";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "none";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link active py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";

      document.getElementById('txtPequisarProduto2').focus();

    }
    if (teste == 3) {
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "block";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "none";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  active py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";

    }
    if (teste == 4) {
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "block";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "none";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link active py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";

      document.getElementById('txtPequisarUsuario2').focus();

    }
    if (teste == 5) {
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "block";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "none";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link active py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";


    }
    if (teste == 6) {
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "block";
      div7.style.display = "none";
      div8.style.display = "none";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link active  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";


    }
    if (teste == 7) {
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "block";
      div8.style.display = "none";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link active  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";
    }
    if (teste == 8) {
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "block";

      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link active  py-3 border-bottom";


      document.getElementById('txtEditarNomeUsu').focus();

    }

    if (teste == 9) {
      div9.style.display = "none";
      div10.style.display = "block";
      div11.style.display = "none";
      div12.style.display = "none";


      document.getElementById('txtNomeProduto').focus();


    }

    if (teste == 10) {
      div9.style.display = "none";
      div10.style.display = "none";
      div11.style.display = "block";
      div12.style.display = "none";


      document.getElementById('txtNomeCategoria').focus();

    }

    if (teste == 11) {
      div9.style.display = "none";
      div10.style.display = "none";
      div11.style.display = "none";
      div12.style.display = "block";


      document.getElementById('txtNotaFiscalCad').focus();

    }
    if (teste == 12) {
      div9.style.display = "block";
      div10.style.display = "none";
      div11.style.display = "none";
      div12.style.display = "none";

    }
    if (teste == 13) {
      div13.style.display = "block";
      div14.style.display = "none";

    }
    if (teste == 14) {
      div13.style.display = "none";
      div14.style.display = "block";

    }
    if (teste == 15) {
      div15.style.display = "block";
      div16.style.display = "none";
      div17.style.display = "none";
    }
    if (teste == 16) {
      div15.style.display = "none";
      div16.style.display = "block";
      div17.style.display = "none";
      div22.style.display = "none";



      document.getElementById('txtDescricaoDespesa').focus();
    }
    if (teste == 17) {
      div15.style.display = "none";
      div16.style.display = "none";
      div17.style.display = "block";
      div22.style.display = "none";


      document.getElementById('txtNomeCategoriaDespesa').focus();

    }
    if (teste == 18) {
      div18.style.display = "none";
      div19.style.display = "block";
      div20.style.display = "none";
      div21.style.display = "block";




    }

    if (teste == 19) {
      div18.style.display = "block";
      div19.style.display = "none";
      div20.style.display = "block";
      div21.style.display = "none";

      //   document.getElementById('txtNomeCategoriaDespesa').focus();

    }
    if (teste == 20) {
      div15.style.display = "none";
      div16.style.display = "none";
      div17.style.display = "none";
      div22.style.display = "block";


      //document.getElementById('txtNomeCategoriaDespesa').focus();

    }

  }
</script>

<script>
  function FuncaoChamarBotao1() {

    var offcanvasElement = document.getElementById('offcanvasTop');

    offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
      document.getElementById('btnSubmitPedirVendaExpressa').focus();
    });
    return true;
  }

  function FuncaoChamarBotao2() {

    var offcanvasElement = document.getElementById('offcanvasRight');

    offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
      document.getElementById('txtPesquisarCliente').focus();
    });
    return true;
  }

  function FuncaoChamarBotao3() {
    document.getElementById('txtNomeCompletoCadRes').focus();
  }

  function FuncaoChamarBotao4() {
    document.getElementById('txtPesquisarCliente').focus();
  }

  function FuncaoChamarBotao5() {
    document.getElementById('txtNomeCadCom').focus();

  }

  function FuncaoChamarBotao6() {

    var offcanvasElement = document.getElementById('offcanvasLeft');
    //<input name='txtPesquisarPorData' id='txtPesquisarPorData' value="" onkeyup="validacao(16, this.value ,0, 13)" type="text" />

    offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
      document.getElementById('txtDataPequisaCaixa').focus();

      validacao(13, 0, 0, 13);
    });
    return true;
  }

  function FuncaoChamarBotao8() {

    var offcanvasElement = document.getElementById('offcanvasLeft');
    //<input name='txtPesquisarPorData' id='txtPesquisarPorData' value="" onkeyup="validacao(16, this.value ,0, 13)" type="text" />

    offcanvasElement.addEventListener('shown.bs.offcanvas', function () {
      FuncaoChamarBotao7(1);
    });
    return true;
  }

  function FuncaoChamarBotao7(tipo) {



    var div1 = document.getElementById("ResultadoValidacao13");
    var div2 = document.getElementById("secundariocaixa");
    var div3 = document.getElementById("FormularioParaPesquisa");



    // Ocultar div1 e desocultar div2

    var teste = tipo;
    if (teste == 1) {

      div1.style.display = "none";
      div2.style.display = "block";
      div3.style.display = "none";

      document.getElementById('txtCaixainicial').focus();
    }
    if (teste == 2) {

      div1.style.display = "block";
      div2.style.display = "none";
      div3.style.display = "block";

      document.getElementById('txtDataPequisaCaixa').focus();
    }



  }


  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("DivProdutos").style.display = "none";
    document.getElementById("DivRelatorios").style.display = "none";
    document.getElementById("DivUsuarios").style.display = "none";
    document.getElementById("DivContas").style.display = "none";
    document.getElementById("DivDespesas").style.display = "none";
    document.getElementById("DivProdutos").style.display = "none";
    document.getElementById("DivConfiguracoes").style.display = "none";
    document.getElementById("DivMinhaConta").style.display = "none";

    document.getElementById("DivCadastroCategoriaDespesa").style.display = "none";
    document.getElementById("DivCadastroDespesa").style.display = "none";

  });

  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
  })()

  function ConfirmarIsso() {
    if (!confirm("APERTE ENTER PARA CONTINUAR."))
      return false;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInput = document.getElementById('txtContatoCadRes');
    VMasker(phoneInput).maskPattern('(99) 99999-9999');
  });
  document.addEventListener('DOMContentLoaded', function () {
    var phoneInput = document.getElementById('txtContatoCadCom');
    VMasker(phoneInput).maskPattern('(99) 99999-9999');
  });
  document.addEventListener('DOMContentLoaded', function () {
    var phoneInput = document.getElementById('txtDataNascimentoCadCom');
    VMasker(phoneInput).maskPattern('99/99/9999');
  });
  document.addEventListener('DOMContentLoaded', function () {
    var phoneInput = document.getElementById('txtCpfCadCom');
    VMasker(phoneInput).maskPattern('999.999.999-99');
  });

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInput = document.getElementById('txtCelular');
    VMasker(phoneInput).maskPattern('(99) 99999-9999');
  });

  //FUNCOES PARA FORMATAR O INPUT DO PESQUISAR POR CONTATO E CPF 

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInputCPF = document.getElementById('txtPesquisarCpf');
    VMasker(phoneInputCPF).maskPattern('999.999.999-99');
  });


  document.addEventListener('DOMContentLoaded', function () {
    var phoneInputContato = document.getElementById('txtPesquisarContato');
    VMasker(phoneInputContato).maskPattern('(99) 99999-9999');

    //FUNCOES PARA PESQUISAR O QUE ESTÁ SENDO DIGITADO NO INPUT DE PESQUISAR CLIENTES EM PAGAMENTO

    // Evento para quando o modal é aberto
    $('#exampleModalCliente').on('shown.bs.modal', function () {
      // Seleciona o input dentro do modal
      var inputPesquisarContato = document.getElementById("txtPesquisarContato");
      

      // Adiciona um listener de evento de input
      inputPesquisarContato.addEventListener("input", function (event) {
        // Chama a função de validação com os parâmetros desejados
        validacao(9, inputPesquisarContato.value, 1, 10);
        validacao(12, '22222', 2, 47);
      });
    });


    //FIM DAS FUNCOES DE PESQUISAR....
  });

  document.addEventListener('DOMContentLoaded', function () {
    window.scrollTo(0, 0); // Rola a página para a posição (0, 0), ou seja, topo
  });


  window.onload = function () {
    // Seleciona a div pelo id e oculta
    document.getElementById("DivCadastroProduto").style.display = "none";
    document.getElementById("DivCadastroCategoria").style.display = "none";
    document.getElementById("DivCadastroEntrada").style.display = "none";
    document.getElementById("DivCadastroUsuario").style.display = "none";
    document.getElementById("DivParaTrocarImagemUsu").style.display = "none";
    document.getElementById("BtnMostrarPerfil").style.display = "none";
  };


  //FIM DAS FUNCOES DE FORMATAR O INPUT DO PESQUISAR POR CONTATO E CPF
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-masker/1.0.9/vanilla-masker.min.js"></script>
<script src="Interface/js/funcs.js"></script>
<script src="Interface/js/snippets.js"></script>


<!-- CoreUI and necessary plugins-->
<script src="./modelo_files/coreui.bundle.min.js.download"></script>
<script src="./modelo_files/simplebar.min.js.download"></script>
<script>
  const header = document.querySelector('header.header');

  document.addEventListener('scroll', () => {
    if (header) {
      header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
    }
  });
</script>
<!-- Plugins and scripts required by this view-->
<script src="./modelo_files/chart.umd.js.download"></script>
<script src="./modelo_files/coreui-chartjs.js.download"></script>
<script src="./modelo_files/index.js.download"></script>
<script src="./modelo_files/main.js.download"></script>

<script>

</script>

</html>