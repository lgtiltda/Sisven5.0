<?php
//INICIO DO FIXAÇÃO DE PAGINAS COMPLEMENTARES DE PHP
session_start();
date_default_timezone_set('America/Manaus');

//INICIO MODELS E CONTROLLERS

require_once("Controller/NotasController.php");
require_once("Model/Notas.php");

require_once("Controller/UsuariosController.php");
require_once("Model/Usuarios.php");

require_once("Controller/LojasController.php");
require_once("Model/Lojas.php");

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

require_once("Controller/MovimentoClientesController.php");
require_once("Model/MovimentoClientes.php");

require_once("Controller/EntradasController.php");
require_once("Model/Entradas.php");

require_once("Controller/ListaEntradasController.php");
require_once("Model/ListaEntradas.php");


require_once("Controller/PedidosController.php");
require_once("Model/Pedidos.php");


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
$lojasController = new LojasController();
$clientesController = new ClientesController();
$categoriaserfinController = new CategoriaSerFinController();
$categoriaFinanceiroController = new CategoriaFinanceiroController();
$servicoController = new ServicoController();
$movimentoEmpresaController = new MovimentoEmpresaController();
$listaentradasControler = new ListaEntradasController();
$entradasController = new EntradasController();
$pedidosController = new PedidosController();
$movimentoClienteController = new MovimentoClientesController();

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


          <div class='card' style='width:100%;'>
          <div class='card-body'>
          <a tabindex='4' onclick='validacao(78, $codcli, 0, 78)' href='javascript: func()' type='button' class='btn btn-outline-success btn-sm' style='width:24%; height:40px; font-size:14pt;'>Editar Dados</a>
          <a tabindex='5' onclick='validacao(79, $codcli, 0, 78)' href='javascript: func()' type='button' class='btn btn-outline-success btn-sm' style='width:24%; height:40px; font-size:14pt;'>Crediário</a>
          <a tabindex='6' onclick='validacao(80, $codcli, 0, 78)' href='javascript: func()' href='javascript: func' type='button' class='btn btn-outline-success btn-sm' style='width:24%; height:40px; font-size:14pt;'>Compras Realizadas</a>
          <a tabindex='7' href='javascript: func' type='button' class='btn btn-outline-success btn-sm' style='width:24%; height:40px; font-size:14pt;'>Extrato de Compras </a>
          <div id='ResultadoValidacao78'>
          
          </div>
          </div></div>
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
                <TD rowspan=''></TD>
                <td colspan=''><b>PAGAMENTO EM DINHEIRO</b></td>
                <td colspan=''><b>PAGAMENTO CARTÃO DÉBITO </b></td>
                <td colspan=''><b>PAGAMENTO PIX </b></td>
                <td colspan=''><b>PAGAMENTO CARTÃO CRÉDITO </b></td>
                
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

    $valorTotalFinal = 0;


    $totalfinalsubtotal = 0;
    $totalfinaldinheiro = 0;
    $totalfinaldebito = 0;
    $totalfinalcredito = 0;
    $totalfinalpix = 0;
    $totalfinaldesconto = 0;


    $totalpagoentrada = 0;
    $totalpagocrediarioloja = 0;
    $totalpagocrediarioavancard = 0;
    $valorpagoparcela = 0;

    $totalpagoemparcelas = 0;
    $totalpagoemparcelasdinheiro = 0;
    $totalpagoemparcelaspix = 0;
    $totalpagoemparcelasdebito = 0;
    $totalpagoemparcelascredito = 0;

    $totalpagoemparcelasdinheiroentrada = 0;
    $totalpagoemparcelaspixentrada = 0;
    $totalpagoemparcelasdebitoentrada = 0;
    $totalpagoemparcelascreditoentrada = 0;


    $sqlNotaPagParcelas = "SELECT * FROM pag_par_pro WHERE cod_caixa = :cod ORDER BY cod DESC";
    $paramNotaPagParcelas = array(
      ":cod" => $cod_caixa
    );

    $dataTableNotaPagParcelas = $banco->ExecuteQuery($sqlNotaPagParcelas, $paramNotaPagParcelas);
    foreach ($dataTableNotaPagParcelas as $resultadonotaPagParcelas) {
      $valorpagoparcela = (float) $resultadonotaPagParcelas['valor'];
      $tipopagparcelas = (float) $resultadonotaPagParcelas['tipopag'];
      $totalpagoemparcelas = $totalpagoemparcelas + $valorpagoparcela;

      if ($tipopagparcelas == 1) {
        $totalpagoemparcelasdinheiro = $totalpagoemparcelasdinheiro + $valorpagoparcela;
      } else if ($tipopagparcelas == 2) {
        $totalpagoemparcelaspix = $totalpagoemparcelaspix + $valorpagoparcela;
      } else if ($tipopagparcelas == 3) {
        $totalpagoemparcelasdebito = $totalpagoemparcelasdebito + $valorpagoparcela;
      } else if ($tipopagparcelas == 4) {
        $totalpagoemparcelascredito = $totalpagoemparcelascredito + $valorpagoparcela;
      }

    }

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

        $total = (float) $resultadonotaPag['total'];
        $subtotal = (float) $resultadonotaPag['subtotal'];
        $gorjeta = (float) $resultadonotaPag['gorjeta'];
        $dinheiro = (float) $resultadonotaPag['dinheiro'];
        $debito = (float) $resultadonotaPag['debito'];
        $credito = (float) $resultadonotaPag['credito'];
        $pix = (float) $resultadonotaPag['pix'];
        $desconto = (float) $resultadonotaPag['desconto'];
        $tipopag = (float) $resultadonotaPag['tipopag'];
        $valorentrada = (float) $resultadonotaPag['entrada'];
        $tipo_crediario = (float) $resultadonotaPag['tipo_crediario'];
        $tipopagentrada = $resultadonotaPag['tipopagentrada'];

        if ($tipopag == 1) {
          $valorTotalFinal = $valorTotalFinal + $total;


          $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
          $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
          $totalfinaldebito = $totalfinaldebito + $debito;
          $totalfinalcredito = $totalfinalcredito + $credito;
          $totalfinalpix = $totalfinalpix + $pix;
          $totalfinaldesconto = $totalfinaldesconto + $desconto;





          $valorTotalFinal2 = $valorTotalFinal2 + $resultadonotaPag['total'];

        } else {

          $totalpagoentrada = $totalpagoentrada + $valorentrada;
          if ($tipo_crediario == 1) {
            $totalpagocrediarioloja = $totalpagocrediarioloja + $total;
          } else {
            $totalpagocrediarioavancard = $totalpagocrediarioavancard + $total;

          }

          if ($tipopagentrada == 1) {
            $totalpagoemparcelasdinheiroentrada = $totalpagoemparcelasdinheiroentrada + $valorentrada;
          } else if ($tipopagentrada == 2) {
            $totalpagoemparcelaspixentrada = $totalpagoemparcelaspixentrada + $valorentrada;
          } else if ($tipopagentrada == 3) {
            $totalpagoemparcelasdebitoentrada = $totalpagoemparcelasdebitoentrada + $valorentrada;
          } else if ($tipopagentrada == 4) {
            $totalpagoemparcelascreditoentrada = $totalpagoemparcelascreditoentrada + $valorentrada;
          }






        }


      }
    }


    $PARTE2 = "
        ";

    $PARTE3 = "
     <tr style='text-align:left; font-size:12pt; '>
         <td><b>COMPRAS À VISTA</b></td>
         
               
          <td><b>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</b></td>
          <td><b>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</b></td>
          <td><b>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
          <td><b>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</b></td>
          
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
         <td colspan='4'><b></b></td>
         <td colspan=''><b>R$ " . number_format($caixa_inicial, 2, ',', '.') . "</b></td>
     </tr>";


    $PARTE11 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>ENTRADA DE CREDIÁRIO</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelasdinheiroentrada, 2, ',', '.') . "</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelaspixentrada, 2, ',', '.') . "</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelasdebitoentrada, 2, ',', '.') . "</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelascreditoentrada, 2, ',', '.') . "</b></td>
     
         <td colspan=''><b>R$ " . number_format($totalpagoentrada, 2, ',', '.') . "</b></td>
     </tr>";

    $PARTE7 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>PARCELAS RECEBIDAS</b></td>
               <td><b>R$ " . number_format($totalpagoemparcelasdinheiro, 2, ',', '.') . "</b></td>
          <td><b>R$ " . number_format($totalpagoemparcelasdebito, 2, ',', '.') . "</b></td>
          <td><b>R$ " . number_format($totalpagoemparcelaspix, 2, ',', '.') . "</td>
          <td><b>R$ " . number_format($totalpagoemparcelascredito, 2, ',', '.') . "</b></td>
          
          <td><b>R$ " . number_format($totalpagoemparcelas, 2, ',', '.') . "</b></td>
     </tr>";





    $PARTE5 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>DESPESAS DO DIA</b></td>
         <td colspan='4'><b></b></td>
         <td colspan=''><b>R$ " . number_format($totalfinaldiacat, 2, ',', '.') . "</b></td>
     </tr>";
    $SALDODIA = 0;
    $SALDODIA = $valorTotalFinal2 + $totalpagoentrada + $totalpagoemparcelas;
    $PARTE6 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>TOTAL DE ENTRADAS DO DIA</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelasdinheiro + $totalpagoemparcelasdinheiroentrada + $totalfinaldinheiro, 2, ',', '.') . "</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelasdebito + $totalpagoemparcelaspixentrada + $totalfinaldebito, 2, ',', '.') . "</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelaspix + $totalpagoemparcelasdebitoentrada + $totalfinalpix, 2, ',', '.') . "</b></td>
         <td colspan=''><b>R$ " . number_format($totalpagoemparcelascredito + $totalpagoemparcelascreditoentrada + $totalfinalcredito, 2, ',', '.') . "</b></td>
         
         <td colspan=''><b>R$ " . number_format($SALDODIA, 2, ',', '.') . "</b></td>
     </tr>
     ";
    $PARTE8 = "</table><table class='table table-hover table-bordered' style='font-size:10pt;'>  <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>CREDIÁRIO DA LOJA</b></td>
        
         <td colspan=''><b>R$ " . number_format($totalpagocrediarioloja, 2, ',', '.') . "</b></td>
     </tr>";
    $PARTE9 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>CREDIÁRIO AVANCARD</b></td>
       
         <td colspan=''><b>R$ " . number_format($totalpagocrediarioavancard, 2, ',', '.') . "</b></td>
     </tr>";
    $PARTE10 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>CREDIÁRIO TOTAL</b></td>
     
         <td colspan=''><b>R$ " . number_format($totalpagocrediarioloja + $totalpagocrediarioavancard, 2, ',', '.') . "</b></td>
     </tr></table>";
    $SALDODIA2 = 0;
    $SALDODIA2 = $valorTotalFinal2 + $caixa_inicial - $totalfinaldiacat + $totalpagoentrada + $totalpagoemparcelas;

    $PARTE13 = " <tr style='text-align:left; font-size:12pt; '>
         <td colspan=''><b>SALDO DO DIA</b></td>
         <td colspan='4'><b></b></td>
         <td colspan=''><b>R$ " . number_format($SALDODIA2, 2, ',', '.') . "</b></td>
     </tr>";
    if ($status_caixa == 1) {
      $resultado = "
      <div class='alert ' role='alert' >
        <div class='d-flex justify-content-end'>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        
          <h4 class='alert-heading'>Caixa Cod. nº $codcaixa! </br>
            <div class='row'>
              <a href='javascript: func()' onclick='validacao(69, $codcaixa, 0, 69)' style='padding:15px; font-size: 9pt; width: 33%;  font-weight: bold'  class='btn btn-success'>Visualizar Vendas</a>
              <a href='javascript: func()' onclick='validacao(70, $codcaixa, 0, 69)' style='padding:15px; font-size: 9pt; width: 33%;  font-weight: bold'  class='btn btn-primary'>Visualizar Saídas de Produtos</a>
              <a href='javascript: func()' onclick='validacao(95, $codcaixa, 0, 69)' style='padding:15px; font-size: 9pt; width: 33%;  font-weight: bold'  class='btn btn-secondary'>Visualizar Crediário e Parcelas</a>
           </div>
              <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate
                enctype='multipart/form-data'>
                <input name='txtCod_caixa' id='txtCod_caixa' value='$codcaixa' type='hidden' />
  
                <input style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-DANGER'
                  type='submit' name='btnSubmitFecharCaixa' id='btnSubmitFecharCaixa' style=''
                  value='Fechar Caixa'>
              </form>
             
          </h4>
        <hr>  
        " . $PARTE1 . $PARTE2 . $PARTE3 . $PARTE7 . $PARTE11 . $PARTE6 . $PARTE4 . $PARTE5 . $PARTE13 . $PARTE8 . $PARTE9 . $PARTE10 . "
        
    <div class='col-md-12' id='ResultadoValidacao69'>
    </div>
    </div>
  ";
    } else {
      $resultado = "
      <div class='alert ' role='alert' >
        <div class='d-flex justify-content-end'>
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
          <h4 class='alert-heading'>Caixa Cod. nº $codcaixa! 
              
          
             
          </h4>
        <hr>  
         " . $PARTE1 . $PARTE2 . $PARTE3 . $PARTE4 . $PARTE7 . $PARTE5 . $PARTE6 . $PARTE8 . "
    
    <div class='col-md-12' id='ResultadoValidacao69'>
    </div>
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
            </div><div id='ResultadoPesquisaProduto'>
       <table class='table table-striped table-sm'>    <thead>
					   <thead>
                                            <tr style='text-align:left; font-size: 12pt;'>
						<td style=''><b>Nome</b></td>
						<td style=''><b>Descrição</b></td>
						<td style=''><b>Categoria</b></td>
						<td style=''><b>Valor Unt.</b></td>
						<td style=''><b>Tipo</b></td>
				
            <td><b>Cod. Barra</b></td>
						<td><b>Cod. Busca</b></td>
						<td><b>Estoque</b></td>
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
        if ($tiposerv == 1) {
          $textotipo = "Produto";
        } else {
          $textotipo = "Serviço";
        }

        $descricaoservico = $resultadoservicos['descricao'];
        $valorunt = number_format($resultadoservicos['valor'], 2, ',', '.');

        $sqlSerTeste = "SELECT * FROM pedidos WHERE servico = $codservico ORDER BY cod ASC LIMIT 1";
        $contadorindex = 4;

        $dataTableSerTeste = $banco->ExecuteQuery($sqlSerTeste);
        if ($dataTableSerTeste == null) {
          $textoteste = "<a onclick='PagPesquisarProdutos(2, txtNomeProdutoP.value, $codservico)' href='javascript: func' style='color:#fff; width:100%;' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span> Apagar</a>";
        } else {
          $textoteste = "";
        }

        $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "
				<tr style='text-align:left; font-size: 12pt;'>
					<td style=''>
          $nomeservico
          </td>
          <td style=''>
          $descricaoservico
          </td>
          <td style=''>
          $nomecategoria
          </td>
          <td style=''>
          $valorunt
                </td>
          <td>$textotipo</td>
              
                                        <td>$codbarra</td>
					<td>$codbusca</td>
					<td>
                                        ";
        if ($tiposerv == 0) {
          $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "
                                               <small style='color:red;'> Sem Estoque
                                                </small>";
        } else {
          $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "
                                        <b>Qtd:</b>$qtdservico</br>
                                      
                                          
                                       ";
        }
        $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "  </td> <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-primary' data-bs-toggle='dropdown' aria-expanded='false'>
                                              <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-gear' viewBox='0 0 16 16'>
                                                <path d='M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0'/>
                                                <path d='M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z'/>
                                              </svg>
                                            </button>
                                            <ul class='dropdown-menu'>
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(27, 1, $codservico)' href='javascript: func'>Informações Produto</a></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(28, 1, $codservico)' href='javascript: func'>Informações Busca</a></li>
                                              
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(97, 1, $codservico)' href='javascript: func'>Gerar Etiqueta</a></li>
                                              ";
        if ($_SESSION['tipo'] != 1) {
          $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . " <li><a class='dropdown-item' onclick='PagPesquisarProdutos(29, 1, $codservico)' href='javascript: func'>Informações Estoque</a></li>
                                              <li><hr class='dropdown-divider'></li>";
        }
        $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . " 
                                              <li><hr class='dropdown-divider'></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(30, 1, $codservico)' href='javascript: func'>Atualizar Imagem do Produto</a></li>
                                              <li><hr class='dropdown-divider'></li>
                                              <li>$textoteste</li>
                                            </ul>
                                          </div>
                                        </td>
				</tr>
				";
        $contadorindex++;
      }


      $RESULTADOPARACADPRODUTO = $RESULTADOPARACADPRODUTO . "</table>
      
    </div>
      </div>";

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
  if ($_GET['msgget'] == 20) {
    $resultado = "
    <div class='alert alert-success' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>Loja cadastrada com sucesso!</h4>
        
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
  if ($_GET['msgget'] == 15) {
    $resultado = "
    <div class='alert alert-danger' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Você precisa abrir Caixa para continuar vendendo!</h4>
        
      <hr>
      </div>
  ";
  }

  if ($_GET['msgget'] == 16) {
    $resultado = "
    <div class='alert alert-danger' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Nota de Entrada Cancelada com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 17) {
    $resultado = "
    <div class='alert alert-successs' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Cliente Cadastrado com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 18) {
    $resultado = "
    <div class='alert alert-danger' role='alert'>
      <div class='d-flex justify-content-end'>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>
        <h4 class='alert-heading'>  Nota de Venda Cancelada com Sucesso!</h4>
        
      <hr>
      </div>
  ";
  }
  if ($_GET['msgget'] == 19) {
    $retorno = "
    <div class='alert alert-danger' role='alert'>
        Ativação Falhou - Verifque o nº de Serial.
      </div>
  ";
  }
  if ($_GET['msgget'] == 20) {
    $retorno = "
    <div class='alert alert-success' role='alert'>
        Ativação Realizada com Sucesso.
      </div>
  ";
  }


}
//FIM FUNCOES PARA PEGAR MENSAGEM DO SUBMIT ANTERIOR

//INICIO DE FUNCOES DE SUBMIT
$user = "";
//INICIO SUBMIT PARA REALIZAR LOGIN
if (filter_input(INPUT_POST, "btnEntrar", FILTER_SANITIZE_STRING)) {

  $usuarioController = new UsuarioController();
  $user = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING);
  $pass = filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING);
  $permissao = 1;

  $resultado = $usuarioController->AutenticarUsuario($user, $pass, $permissao);

  $retorno = "";

  if ($resultado != null) {
    if (filter_input(INPUT_POST, "ckManterLogado", FILTER_SANITIZE_STRING)) {
      $_SESSION["entrarAdminE"] = true;
    }


    $_SESSION["permissaoF"] = $resultado->getPermissao();
    $_SESSION["codF"] = $resultado->getCod();
    $_SESSION["nomeF"] = $resultado->getNome();
    $_SESSION["tipo"] = $resultado->getTipo_ativacao();
    $_SESSION["logadoF"] = true;
    if ($resultado->getPermissao() == 1) {

      header("Location: index.php?");
    } else if ($resultado->getPermissao() == 2) {
      header("Location: index.php?");
    } else if ($resultado->getPermissao() == 3) {

      header("Location: index.php?");
    }
  } else {
    $retorno = "<div class=\"alert alert-danger\" role=\"alert\">Usuário ou senha inválido.</div>";
  }
}
//FIM SUBMIT PARA REALIZAR LOGIN
//INICIO SUBMIT PARA REALIZAR LOGIN
if (filter_input(INPUT_POST, "btnAtivarSistema", FILTER_SANITIZE_STRING)) {


  $serial = filter_input(INPUT_POST, "txtSerialAtivacao", FILTER_SANITIZE_STRING);
  $sqlNotaPag = "SELECT * FROM licencas WHERE serial LIKE :serial AND ativado = 0 ORDER BY id DESC LIMIT 1";
  $paramNotaPag = array(
    ":serial" => $serial
  );
  $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);

  if ($dataTableNotaPag != null) {
    foreach ($dataTableNotaPag as $resultadonotaPag) {
      $id = $resultadonotaPag['id'];
      $serial = $resultadonotaPag['serial'];
      $chave_ativacao = $resultadonotaPag['chave_ativacao'];
      $valor = $resultadonotaPag['valor'];
      $tipo = $resultadonotaPag['tipo'];

      $sqlFunc = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramFunc = array(
        ":cod" => 1
      );

      $dataTableFunc = $banco->ExecuteQuery($sqlFunc, $paramFunc);
      foreach ($dataTableFunc as $resultadofunc) {
        $data_vencimentoanterior = $resultadofunc['data_vencimentoanterior'];
        $data_vencimento2 = $resultadofunc['data_vencimento'];
      }
      $data_vencimento = date("Y-m-d");
      // Adiciona 30 dias
      if ($tipo != 3) {
        $data_vencimento = date('Y-m-d', strtotime($data_vencimento . ' +30 days'));
      } else {
        $data_vencimento = date('Y-m-d', strtotime($data_vencimento . ' +100 years'));

      }

      $query = mysqli_query($conn, "UPDATE licencas SET ativado = 1 WHERE id = $id");
      if ($query) {
        $query2 = mysqli_query($conn, "UPDATE usuarios SET data_vencimento = '$data_vencimento', data_vencimentoanterior = '$data_vencimento2', tipo_ativacao = $tipo WHERE cod = 1");
        if ($query2) {

          header("Location: index.php?&msgget=20");

        }

      }
    }
  } else {

    header("Location: index.php?&msgget=19");
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
  $txtCpfRes1 = filter_input(INPUT_POST, "txtCpfRes1", FILTER_SANITIZE_STRING);
  $nomecompleto = filter_input(INPUT_POST, "txtNomeCompletoCadRes", FILTER_SANITIZE_STRING);

  $data = date("d/m/Y");

  $clientes = new Clientes();
  $clientes->setNome($nomecompleto);
  $clientes->setCelular($contato);
  $clientes->setCpf($txtCpfRes1);
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
  $cpf = filter_input(INPUT_POST, "txtCpfCadCom", FILTER_SANITIZE_STRING);

  $data = date("d/m/Y");

  $clientes = new Clientes();
  $clientes->setNome($nomecompleto);
  $clientes->setCelular($contato);
  $clientes->setEndereco($endereco);
  $clientes->setNumero($numero);
  $clientes->setBairro($bairro);
  $clientes->setComplemento($complemento);
  $clientes->setNascimento($datanascimento);
  $clientes->setCpf($cpf);
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
  echo $datadespesa = (filter_input(INPUT_POST, "txtDataDespesa", FILTER_SANITIZE_STRING));
  $codcaixadespesa = (filter_input(INPUT_POST, "txtCodcaixadespesa", FILTER_SANITIZE_NUMBER_INT));
  $diadespesa = (filter_input(INPUT_POST, "txtDiaDepesa", FILTER_SANITIZE_NUMBER_INT));
  $mesdespesa = (filter_input(INPUT_POST, "txtMesDespesa", FILTER_SANITIZE_NUMBER_INT));
  $anodespesa = (filter_input(INPUT_POST, "txtAnoDespesa", FILTER_SANITIZE_NUMBER_INT));
  $categoriadespesa = (filter_input(INPUT_POST, "txtCategoriaDespesa", FILTER_SANITIZE_NUMBER_INT));
  $status = (filter_input(INPUT_POST, "txtStatusDespesa", FILTER_SANITIZE_NUMBER_INT));

  $pontos = '.';
  $result = str_replace($pontos, "", $valor);
  $result = str_replace(",", ".", $result);
  $valor = $result;


  $partes = explode('-', $datadespesa);

  $diadespesa = $partes['2'];
  $mesdespesa = $partes['1'];
  $anodespesa = $partes['0'];

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
    $movimentoempresa->setStatus($status);


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

if (filter_input(INPUT_POST, "btnCadastrarNovoProdutoEmEntradas", FILTER_SANITIZE_STRING)) {
  $fornecedor = 0;
  $nome = (filter_input(INPUT_POST, "txtNomeProduto", FILTER_SANITIZE_STRING));
  $descricao = (filter_input(INPUT_POST, "txtDescricaoProduto", FILTER_SANITIZE_STRING));
  $valor = (filter_input(INPUT_POST, "txtValorUntProduto", FILTER_SANITIZE_STRING));
  $cat = (filter_input(INPUT_POST, "txtCategoria", FILTER_SANITIZE_NUMBER_INT));
  $tipo = 1;
  $estmax = (filter_input(INPUT_POST, "txtEstMax", FILTER_SANITIZE_NUMBER_INT));
  $estmin = (filter_input(INPUT_POST, "txtEstMin", FILTER_SANITIZE_NUMBER_INT));
  $fornecedor = (filter_input(INPUT_POST, "txtFornecedor", FILTER_SANITIZE_NUMBER_INT));
  $codbarra = (filter_input(INPUT_POST, "txtCodBarra", FILTER_SANITIZE_NUMBER_INT));
  $codentrada = (filter_input(INPUT_POST, "codentrada", FILTER_SANITIZE_NUMBER_INT));

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


      header("Location: index.php?pagina=entradas&cod=$codentrada&codprod=$codultcod");
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

//SUBMIT PARA CADASTRAR NOVA LOJA
if (filter_input(INPUT_POST, "btnSubmitNovaLoja", FILTER_SANITIZE_STRING)) {

  $erros = ValidarNovaLoja();

  if (empty($erros)) {
    $loja = new Lojas();
    $loja->setNome(filter_input(INPUT_POST, "txtNomeLoja", FILTER_SANITIZE_STRING));
    $loja->setTipo((int) filter_input(INPUT_POST, "txtTipoLoja", FILTER_SANITIZE_NUMBER_INT));
    $loja->setCnpj(filter_input(INPUT_POST, "txtCnpjLoja", FILTER_SANITIZE_STRING));
    $loja->setResponsavel(filter_input(INPUT_POST, "txtResponsavelLoja", FILTER_SANITIZE_STRING));
    $loja->setTelefone(filter_input(INPUT_POST, "txtTelefoneLoja", FILTER_SANITIZE_STRING));
    $loja->setEmail(filter_input(INPUT_POST, "txtEmailLoja", FILTER_SANITIZE_EMAIL));
    $loja->setCep(filter_input(INPUT_POST, "txtCepLoja", FILTER_SANITIZE_STRING));
    $loja->setEndereco(filter_input(INPUT_POST, "txtEnderecoLoja", FILTER_SANITIZE_STRING));
    $loja->setNumero(filter_input(INPUT_POST, "txtNumeroLoja", FILTER_SANITIZE_STRING));
    $loja->setBairro(filter_input(INPUT_POST, "txtBairroLoja", FILTER_SANITIZE_STRING));
    $loja->setCidade(filter_input(INPUT_POST, "txtCidadeLoja", FILTER_SANITIZE_STRING));
    $loja->setEstado(filter_input(INPUT_POST, "txtEstadoLoja", FILTER_SANITIZE_STRING));
    $loja->setObservacao(filter_input(INPUT_POST, "txtObservacaoLoja", FILTER_SANITIZE_STRING));
    $loja->setStatus(1);
    $loja->setData_cadastro(date('Y-m-d'));

    if ($lojasController->Cadastrar($loja)) {
      header("Location: index.php?&msgget=20");
    } else {
      $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao cadastrar Loja!</span> </div>";
    }
  }
}

function ValidarNovaLoja()
{
  $listaErros = [];

  if (strlen(filter_input(INPUT_POST, "txtNomeLoja", FILTER_SANITIZE_STRING)) < 3) {
    $listaErros[] = "- Nome da loja invalido.";
  }

  if ((int) filter_input(INPUT_POST, "txtTipoLoja", FILTER_SANITIZE_NUMBER_INT) < 1) {
    $listaErros[] = "- Tipo da loja invalido.";
  }

  if (strlen(filter_input(INPUT_POST, "txtCidadeLoja", FILTER_SANITIZE_STRING)) < 2) {
    $listaErros[] = "- Cidade invalida.";
  }

  if (strlen(filter_input(INPUT_POST, "txtEstadoLoja", FILTER_SANITIZE_STRING)) != 2) {
    $listaErros[] = "- UF invalida.";
  }

  return $listaErros;
}
//FIM DO SUBMIT PARA CADASTRAR NOVA LOJA


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

if (filter_input(INPUT_POST, "btnCadastrarLoja", FILTER_SANITIZE_STRING)) {

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


// FIM SUBMIT PARA EDITAR DADOS DO USUÁRIO

//INICIO SUBMIT PARA CADASTRAR NOTA DE ENTRADA DE PRODUTOS


if (filter_input(INPUT_POST, "btnSubmitNota", FILTER_SANITIZE_STRING)) {
  $n_notafiscal = "";
  $conferidor1 = "";
  $conferidor2 = "";
  $conferidor3 = "";



  $n_notafiscal = filter_input(INPUT_POST, "txtNotaFiscalCad", FILTER_SANITIZE_STRING);
  $dia = filter_input(INPUT_POST, "txtDiaPes", FILTER_SANITIZE_NUMBER_INT);
  $mes = filter_input(INPUT_POST, "txtMesPes", FILTER_SANITIZE_NUMBER_INT);
  $ano = filter_input(INPUT_POST, "txtAnoPes", FILTER_SANITIZE_NUMBER_INT);
  $cod_solic = filter_input(INPUT_POST, "txtSolic", FILTER_SANITIZE_NUMBER_INT);

  $ata_pregao = filter_input(INPUT_POST, "txtAtasControle1", FILTER_SANITIZE_NUMBER_INT);
  $cod_produto = 0;
  $cod_funcionario = $_SESSION['codF'];
  $cod_orgaoF = $_SESSION['cod_orgaoF'];
  $img = "";



  $entradas = new Entradas();
  $entradas->setCod_solicitacao($cod_solic);
  $entradas->setN_notafiscal($n_notafiscal);

  $entradas->setCod_funcionario($cod_funcionario);
  $entradas->setFornecedor(1);
  $entradas->setDia($dia);
  $entradas->setMes($mes);
  $entradas->setAno($ano);
  $entradas->setCod_orgao(1);
  $entradas->setAta_pregao($ata_pregao);
  $entradas->setImg($img);
  $entradas->setConferidor1($conferidor1);
  $entradas->setConferidor2($conferidor2);
  $entradas->setConferidor3($conferidor3);



  if ($entradasController->Cadastrar($entradas)) {

    $n_notafiscal = "";
    $cod_orgaoF = $_SESSION['cod_orgaoF'];
    $status = 1;

    //    $produtosController->AlterStatus($status, $cod_produto);

    echo $cod = $entradasController->RetornarUltimoEntradas(1);

    header("Location: index.php?pagina=entradas&cod=$cod");
  } else {

    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao cadastrar Entrada!</span> </div>";
  }
}

//FIM DO SUBMIT PARA CADASTRAR ITEM EM NOTA DE ENTRADA DE PRODUTOS

//INICIO SUBMIT PARA FINALIZAR ENTRADA
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
      header("Location: index.php?pagina=entradas&cod=" . $cod_entrada . "&msgget=1");
    }
  }
}

//FIM DO SUBMIT PARA FINALIZAR ENTRADA

//INICIO DA FUNCAO PARA EDITAR DADOS DO CLIENTE

if (filter_input(INPUT_POST, "btnSubmitEdCliente", FILTER_SANITIZE_STRING)) {

  $clientes = new Clientes();
  $clientes->setNome(filter_input(INPUT_POST, "txtNome1", FILTER_SANITIZE_STRING));
  $clientes->setNascimento(filter_input(INPUT_POST, "txtNascimento", FILTER_SANITIZE_STRING));
  $clientes->setCpf(filter_input(INPUT_POST, "txtCpf1", FILTER_SANITIZE_STRING));
  $clientes->setEndereco(filter_input(INPUT_POST, "txtEndereco", FILTER_SANITIZE_STRING));
  $clientes->setBairro(filter_input(INPUT_POST, "txtBairro", FILTER_SANITIZE_STRING));
  $clientes->setNumero(filter_input(INPUT_POST, "txtNumero", FILTER_SANITIZE_STRING));
  $clientes->setComplemento(filter_input(INPUT_POST, "txtComplemento", FILTER_SANITIZE_STRING));
  $clientes->setCelular(filter_input(INPUT_POST, "txtCelular1", FILTER_SANITIZE_STRING));
  $clientes->setResidencial(filter_input(INPUT_POST, "txtWhatsapp", FILTER_SANITIZE_STRING));
  $clientes->setStatus(filter_input(INPUT_POST, "txtStatus", FILTER_SANITIZE_STRING));
  $cod_cliente2 = filter_input(INPUT_POST, "txtCodCli", FILTER_SANITIZE_NUMBER_INT);
  //Editar
  $clientes->setId($cod_cliente2);

  if ($clientesController->Alterar($clientes)) {
    header("Location: index.php?&codcli=$cod_cliente2&msgget=1");
    $resultado = " <div class='alert alert-success' role='alert'><span>Dados do Cliente alterado com sucesso!</span>  </div>";
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao alterar dados do paciente!</span> </div>";
  }
}



//FIM DA FUNCAO PARA EDITAR DADOS DO CLIENTE


//INICIO SUBMIT PARA CANCELAR NOTA DE VENDA


if (filter_input(INPUT_POST, "btnCancelarNota", FILTER_SANITIZE_STRING)) {

  $cod_entrada = filter_input(INPUT_POST, "codnota", FILTER_SANITIZE_NUMBER_INT);


  $notasController->Deletar($cod_entrada);
  $pedidosController->DeletarO($cod_entrada);


  header('Location: index.php?msgget=6');

  $notasController->AlterStatusTodos(1, $codnota);
  //header("Location: index.php?pagina=carinhocompras");
}

//FIM SUBMIT PARA CANCELAR NOTA DE VENDA


//INICIO SUBMIT PARA CADASTRAR USUARIO PRINCIPAL

if (filter_input(INPUT_POST, "btnSubmitAdm", FILTER_SANITIZE_STRING)) {

  $erros = ValidarUsu();
  $nome = filter_input(INPUT_POST, "txtNome", FILTER_SANITIZE_STRING);
  $cnpj = filter_input(INPUT_POST, "txtCnpj", FILTER_SANITIZE_STRING);
  $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING);
  $permissao = 1;
  $rua = filter_input(INPUT_POST, "txtRua", FILTER_SANITIZE_STRING);
  $bairro = filter_input(INPUT_POST, "txtBairro", FILTER_SANITIZE_STRING);
  $numero = filter_input(INPUT_POST, "txtNumero", FILTER_SANITIZE_STRING);
  $comissao = 100;
  $usuario2 = filter_input(INPUT_POST, "txtLogin", FILTER_SANITIZE_STRING);
  $celular = filter_input(INPUT_POST, "txtCelular", FILTER_SANITIZE_STRING);
  $senha = filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING);
  $senha = md5($senha);

  if (empty($erros)) {

    $usuario = new Usuarios();

    $data_ativacao = date('Y-m-d');


    $data_vencimento = date('Y-m-d');

    $data = new DateTime($data_vencimento);
    $data->modify('+15 days');

    $data_vencimento_com_mais_15 = $data->format('Y-m-d');
    $data_vencimentoanterior = date('Y-m-d');

    $usuario->setNome($nome);
    $usuario->setCpf($cnpj);
    $usuario->setEmail($email);
    $usuario->setPermissao($permissao);
    $usuario->setRua($rua);
    $usuario->setBairro($bairro);
    $usuario->setNumero($numero);
    $usuario->setCelular($celular);
    $usuario->setUsuario($usuario2);
    $usuario->setPermissao(1);
    $usuario->setSenha($senha);
    $usuario->setData_ativacao($data_ativacao);
    $usuario->setData_vencimento($data_vencimento_com_mais_15);
    $usuario->setData_vencimentoanterior($data_vencimento_com_mais_15);

    if (!filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {

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
        header("location: index.php?pagina=index.php");
        ?>
        <script>
          var mensagem = "<strong>Usuário Cadastrado com sucesso!</strong><br>";
          mostraDialogo(mensagem, "success", 2500);
        </script>
        <?php
        //$resultado = " <div class='alert alert-success' role='alert'><span>Usuario cadastrado com sucesso!</span> </div>";
      } else {
        $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao cadastrar Usuario!</span> </div>";
      }
    } else {
      //Editar
      $usuario->setCod(filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT));

      if ($usuarioController->Alterar($usuario)) {
        $resultado = " <div class='alert alert-success' role='alert'><span>Dados do usuario alterado com sucesso!</span> </div>";
      } else {
        $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao alterar dados do usuario!</span> </div>";
      }
    }
  }
}

function ValidarUsu()
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

//FIM DO SUBMIT PARA CADASTRAR USUARIO PRINCIPAL 


//SUBMIT PARA NEGOCIAÇÃO
if (filter_input(INPUT_POST, "btnSubmitRenegociar22", FILTER_SANITIZE_STRING)) {


  $codcliente = filter_input(INPUT_POST, "txtCodCliente", FILTER_SANITIZE_NUMBER_INT);
  $cod_caixa = filter_input(INPUT_POST, "txtCod_caixa", FILTER_SANITIZE_NUMBER_INT);
  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);

  //$sqlNota = mysqli_query($conn, "SELECT * FROM notas WHERE cod = " . $codnota . " ORDER BY cod ASC LIMIT 1");
  $sqlNotas = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
  $paramNotas = array(
    ":cod" => $codnota
  );

  $dataTableNotas = $banco->ExecuteQuery($sqlNotas, $paramNotas);
  foreach ($dataTableNotas as $resultadonotas) {

    $usuarionota = $resultadonotas['usuario'];


    $dia_compra = $resultadonotas['dia'];
    $mes_compra = $resultadonotas['mes'];
    $ano_compra = $resultadonotas['ano'];


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


  $sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
  $paramTestePag = array(
    ":cod" => $codnota
  );
  $textotipocrediario = "";
  $dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);



  $dinheiropag = 0;
  $pixpag = 0;
  $debitopag = 0;
  $creditopag = 0;
  $totalpag = 0;
  $subtotalpag = 0;
  $descontopag = 0;
  $gorjetapag = 0;



  foreach ($dataTableTestePag as $infopagamento) {
    $codpagamento = $infopagamento['cod'];
    $valorfinalpagamento = $infopagamento['total'];
    $numparcelas = $infopagamento['numparcelas'];
    $tipo_crediario = $infopagamento['tipo_crediario'];

    if ($tipo_crediario == 1) {
      $textotipocrediario = "DA LOJA";
    } else {

      $textotipocrediario = "AVANCARD";
    }

    $valortotalpago = 0;
    $valorPARCELA = 0;
    $contadorparcelaspagas = 0;


    $sqlPedidos22 = "SELECT * FROM pag_par_pro WHERE esp_proc = :cod AND status = 2 ORDER BY cod ASC";
    $paramPedidos22 = array(
      ":cod" => $codnota
    );

    $dataTablePedidos22 = $banco->ExecuteQuery($sqlPedidos22, $paramPedidos22);
    foreach ($dataTablePedidos22 as $resultadopedidos22) {

      $contadorparcelaspagas++;

      $codpedido = $resultadopedidos22['cod'];
      $financeiro_pac = $resultadopedidos22['financeiro_pac'];

      $dia = $resultadopedidos22['dia'];
      $mes = $resultadopedidos22['mes'];
      $ano = $resultadopedidos22['ano'];
      $data_vencimento = $resultadopedidos22['data_vencimento'];
      $status = $resultadopedidos22['status'];

      $valorPARCELA = (float) $resultadopedidos22['valor'];

      $valortotalpago = $valortotalpago + $valorPARCELA;


      // $valorPARCELA = number_format($valorPARCELA, 2, ',', '.');
    }


  }


  $textopagamentotipo = "Pagamento Parcelado em " . $numparcelas . "x no Crediário $textotipocrediario";



  $VALORTOTALARECEBER = 0;
  $VALORTOTALARECEBER = (float) $valorfinalpagamento - $valortotalpago;




  $query2 = mysqli_query($conn, "UPDATE `financeiro_clientes` SET `numparcelas` = $contadorparcelaspagas, `total` = '$valortotalpago' WHERE `financeiro_clientes`.`cod_orcamento` = $codnota;");

  if ($query2) {
    $query = mysqli_query($conn, "DELETE FROM `pag_par_pro` WHERE esp_proc = $codnota AND status =1");
    // Se inserido com scesso
    if ($query) {

      $dia = date('d');
      $mes = date('m');
      $ano = date('Y');
      $hora = date('H:i:s');
      $func = $_SESSION['codF'];

      $sql = "
      INSERT INTO `notas`
      (
          `status`,
          `usuario`,
          `nomeCli`,
          `dia`,
          `mes`,
          `ano`,
          `func`,
          `hora`,
          `tipo_pedido`,
          `cod_caixa`
      )
      VALUES
      (
          '1',
          '$codcliente',
          '',
          '$dia',
          '$mes',
          '$ano',
          '$func',
          '$hora',
          '1',
          '$cod_caixa'
      )";

      $queryNota = mysqli_query($conn, $sql);

      if ($queryNota) {

        $sqlNota = "SELECT * 
        FROM notas 
        WHERE dia = :dia 
        AND mes = :mes 
        AND ano = :ano
        AND func = :func
        ORDER BY cod DESC
        LIMIT 1";

        $paramNota = array(
          ":dia" => $dia,
          ":mes" => $mes,
          ":ano" => $ano,
          ":func" => $_SESSION['codF']
        );

        $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);

        foreach ($dataTableNota as $resultadonota) {

          $codnota = $resultadonota['cod'];

          $sqlPedido = "INSERT INTO pedidos
          (
              dente,
              servico,
              usuario,
              qtd,
              valor,
              status,
              nivel,
              obs,
              tipo,
              dia,
              mes,
              ano,
              categoria
          )
          VALUES
          (
              :dente,
              :servico,
              :usuario,
              :qtd,
              :valor,
              :status,
              :nivel,
              :obs,
              :tipo,
              :dia,
              :mes,
              :ano,
              :categoria
          )";

          $paramPedido = array(

            ":dente" => 0,
            ":servico" => 0,
            ":usuario" => $codnota,
            ":qtd" => 1,
            ":valor" => $VALORTOTALARECEBER,
            ":status" => 1,
            ":nivel" => 1,

            ":obs" => "RENEGOCIAÇÃO REFERENTE A NOTA Nº $codnota: CREDIÁRIO PARCELADO EM " . $numparcelas . "X, NA DATA DE: $dia_compra/$mes_compra/$ano_compra",
            ":tipo" => 1,
            ":dia" => date('d'),
            ":mes" => date('m'),
            ":ano" => date('Y'),
            ":categoria" => 1

          );

          $insertPedido = $banco->ExecuteNonQuery($sqlPedido, $paramPedido);

          header("Location: index.php?pagina=carinhocompras&cod=$codnota ");

        }


      } else {
        echo mysqli_error($conn);
      }

    }

  }

}

//FIM DAS FUNCOES DE SUBMIT

//APAGAR PAGAMENTO EM CARINHO DE COMPRAS
if (filter_input(INPUT_POST, "btnApagarPagamento", FILTER_SANITIZE_STRING)) {
  if (filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT)) {
    $codnota = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
  } else {
    $listaNotas = $notasController->RetornarUltimaNota($_SESSION['codF']);
    if ($listaNotas != null) {
      foreach ($listaNotas as $user477) {
        $codnota = $user477->getCod();
        $codusuario2 = $user477->getUsuario();
        $nomeCli = $user477->getNomeCli();
      }
    } else {
      header('Location: index.php?msgget=6');
    }
  }

  $pedidosController->AlterStatusTodos2(1, $codnota);
  $notasController->AlterStatusTodos(1, $codnota);
  $tipo = 2;
  $listaNotas = $notasController->RetornarNotas($tipo, $codnota);
  if ($listaNotas != null) {
    foreach ($listaNotas as $user477) {
      $codnota = $user477->getCod();
      $codusuario2 = $user477->getUsuario();
      $nomeCli = $user477->getNomeCli();
    }
  }
  $tipo = 1;
  $status2 = $codnota;
  $termo = "";
  $listasaidas3 = $pedidosController->RetornarPedidos($termo, $tipo, $status2);
  // var_dump($listasaidas3);
  if ($listasaidas3 != NULL) {
    foreach ($listasaidas3 as $saidas3) {

      $qtd_saida = $saidas3->getQtd();
      $cod_produto2 = $saidas3->getServico();




      $tipo_servico = $servicoController->RetornarTipo($cod_produto2);
      if ($tipo_servico == 1) {
        $qtd_final = 0;
        $qtd_produto = $servicoController->RetornarNomeValorProdutos($cod_produto2);
        $qtd_final = $qtd_produto + $qtd_saida;
        $servicoController->AlterQtdEstoque($qtd_final, $cod_produto2);
      }
    }
    if ($movimentoClienteController->Deletar($codnota)) {
      $query = mysqli_query($conn, "DELETE FROM `pag_par_pro` WHERE esp_proc = $codnota");
      // Se inserido com scesso
      if ($query) {

        header('Location: index.php?pagina=carinhocompras&cod=' . $codnota);
      }
    }
  }
}


//FIM APAGAR PAGAMENTO EM CARINH DE COMPRAS



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
  <link rel="icon" type="image/png" href="Interface/img/logo.png">

  <title>DopDin</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">

  <!-- Bootstrap core CSS -->
  <link href="./Barras laterais · Bootstrap v5.0_files/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Favicons -->
  <link rel="manifest" href="https://getbootstrap.com/docs/5.0/assets/img/favicons/manifest.json">
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

<body style="margin-top:-25px;">
  <?PHP
  $termo = "";
  $tipo = 5;
  $status = 1;
  $listaUsuariosBusca2 = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

  if ($listaUsuariosBusca2 == null) {
    ?>
    <style>
      .form-title {
        font-size: 1.5rem;
        margin-bottom: 20px;
        color: #2c3e50;
        font-weight: 600;
      }

      .form-container {
        max-width: 960px;
        margin: auto;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
      }

      label {
        font-weight: 500;
        margin-bottom: 5px;
        color: #000;
      }

      .form-control {
        padding: 10px 14px;
        font-size: 1rem;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        width: 100%;
      }

      .form-control:focus {
        border-color: #337ab7;
        outline: none;
        box-shadow: 0 0 0 2px rgba(51, 122, 183, 0.2);
      }

      .btn-block {
        width: 100%;
        padding: 12px;
        font-size: 1.1rem;
      }

      .error-list {
        color: red;
        margin-top: 15px;
        padding-left: 20px;
      }


      .etiqueta {
        width: 48mm;
        height: 28mm;
        border: 1px solid #ccc;
        float: left;
        margin: 2mm;
        text-align: center;
        padding: 2mm;
        box-sizing: border-box;
      }

      .nome {
        font-size: 7px;
        font-weight: bold;
      }

      .preco {
        font-size: 18px;
        font-weight: bold;
      }
    </style>

    <!-- Bootstrap 5 CDN (adicione no <head> do seu HTML) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="container py-5">
      <div class='card'>
        <div class='card card-body'>
          <h3 class="text-secondary mb-4 text-center">Cadastro da Empresa e Usuário Principal</h3>

          <form method="post" name="frmCadastro" id="frmCadastro" enctype="multipart/form-data">

            <div class="row g-3">
              <!-- Nome da Empresa -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="txtNome" name="txtNome" placeholder="Nome da Empresa"
                    autofocus>
                  <label for="txtNome">Nome da Empresa</label>
                </div>
              </div>

              <!-- CNPJ -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="txtCnpj" name="txtCnpj" placeholder="CNPJ">
                  <label for="txtCnpj">CNPJ</label>
                </div>
              </div>

              <!-- Email -->
              <div class="col-md-12">
                <div class="form-floating">
                  <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Email">
                  <label for="txtEmail">Email</label>
                </div>
              </div>

              <!-- Rua -->
              <div class="col-md-12">
                <div class="form-floating">
                  <input type="text" class="form-control" id="txtRua" name="txtRua" placeholder="Rua">
                  <label for="txtRua">Rua</label>
                </div>
              </div>

              <!-- Bairro -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="txtBairro" name="txtBairro" placeholder="Bairro">
                  <label for="txtBairro">Bairro</label>
                </div>
              </div>

              <!-- Número -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="txtNumero" name="txtNumero" placeholder="Número">
                  <label for="txtNumero">Número</label>
                </div>
              </div>

              <!-- Login -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control" id="txtLogin" name="txtLogin" placeholder="Login">
                  <label for="txtLogin">Login</label>
                </div>
              </div>

              <!-- Celular -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="tel" class="form-control" id="txtCelular" name="txtCelular" placeholder="Celular">
                  <label for="txtCelular">Celular</label>
                </div>
              </div>

              <!-- Senha -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="password" class="form-control" id="txtSenha" name="txtSenha" placeholder="Senha">
                  <label for="txtSenha">Senha</label>
                </div>
              </div>

              <!-- Confirmar Senha -->
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="password" class="form-control" id="txtSenha2" name="txtSenha2"
                    placeholder="Confirmar Senha">
                  <label for="txtSenha2">Confirmar Senha</label>
                </div>
              </div>

              <!-- Upload Imagem -->
              <div class="col-md-12">
                <label for="flImagem" class="form-label">Logo da Empresa (opcional)</label>
                <input type="file" class="form-control" id="flImagem" name="flImagem" accept="image/*">
              </div>

              <!-- Botão Submit -->
              <div class="col-md-12">
                <input type="submit" name="btnSubmitAdm" id="btnSubmitAdm" class="btn btn-primary w-100 py-2"
                  value='Cadastrar' />
              </div>

              <!-- Lista de Erros -->
              <div class="col-md-12">
                <ul id="ulErros" class="text-danger ps-3 mt-2">
                  <?php foreach ($erros as $e): ?>
                    <li><?= $e; ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>

        </div>
      </div>
      </form>
    </div>

    <?php
  } else {


    foreach ($listaUsuariosBusca2 as $resultadousu) {
      $data_hoje = date("Y-m-d");
      $data_vencimento = "";
      $data_vencimento = $resultadousu->getData_vencimento();


    }
    if (!isset($_SESSION["permissaoF"])) {

      // Converte para objetos DateTime
      $data1 = new DateTime($data_vencimento);
      $data2 = new DateTime($data_hoje);

      // Comparações
      if ($data1 < $data2) {
        ?>
        <section style="margin-top:-20px; background-color:rgba(38, 12, 48, 1);"
          class="background-radial-gradient overflow-hidden">

          <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
              <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                <h4 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                  A sua mensalidade do DopDin
                  <span style="color: hsla(0, 87%, 46%, 1.00)">está já venceu</span>
                </h4>
                <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%); margin-top: -40px;">
                  Para ativar seu sistema, escolha um dos planos abaixo e realize o pagamento via Pix:</br>

                  🟢 PLANO MENSAL SIMPLES </br>
                  💰 Valor: R$ 99,00</br>

                  🔵 PLANO MENSAL PREMIUM </br>
                  💰 Valor: R$ 299,00</br>

                  📌 Chave Pix para pagamento:
                  <a href='#'>lgti_ltda@icloud.com</a></br>

                  Após o pagamento, envie o comprovante para ativação via WhatsApp:</br>
                  👉 <a
                    href="https://api.whatsapp.com/send/?phone=5597984320699&text=Ol%C3%A1%2C+realizei+o+pagamento+do+plano+mensal+e+gostaria+de+ativar+o+sistema.&type=phone_number&app_absent=0">Clique
                    aqui para enviar no WhatsApp</a></br>

                  A ativação será feita rapidamente após a confirmação.</br>
                  Obrigado por escolher nosso sistema! ✅</br>
                </p>
              </div>

              <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                <div class="card bg-glass" style="background-color:rgba(38, 12, 48, 1); box-shadow: 5px 5px 15px #fff;">
                  <div class="card-body px-4 py-5 px-md-5">


                    <select onchange="validacao(89, this.value, 0, 89)" name="txtCategoriaPesquisa" id="txtCategoriaPesquisa"
                      style="padding:20px; margin-top:5px;" class="form-select" aria-label="Selecione uma Categoria">
                      <option value="0" selected>Selecione um Plano Para Continuar</option>
                      <option value="1">Plano Simples Mensal - R$ 100,00</option>
                      <option value="2">Plano Premiun Mensal - R$ 299,00</option>
                      <option value="3">Plano Vitálicio - R$ 1200,00</option>

                    </select>

                    <div id='ResultadoValidacao89'>

                    </div>
                    <form method="post" name="frmCadastro" id="frmCadastro" class="form-signin" novalidate
                      enctype="multipart/form-data">

                      <!-- 2 column grid layout with text inputs for the first and last names -->
                      <div class="row">
                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                          </BR> <label style="color:#fff;" class="form-label" for="txtSerialAtivacao">Informe Serial de
                            Ativação,
                            Fornecido pelo administrador do Sistema</label>

                          <input value="" autofocus type="text" id="txtSerialAtivacao" name="txtSerialAtivacao"
                            class="form-control" />
                        </div>

                        <!-- Submit button -->
                        <input value="ATIVAR" id='btnAtivarSistema' name='btnAtivarSistema' type="submit"
                          style='padding:20px;' data-mdb-button-init data-mdb-ripple-init
                          class="btn btn-outline-primary btn-block mb-4" />
                        <?= $retorno; ?>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php
      } elseif ($data1 > $data2) {
        ?>

        <section style="margin-top:-20px; background-color:rgba(38, 12, 48, 1);"
          class="background-radial-gradient overflow-hidden">

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

                <div class="card bg-glass" style="background-color:rgba(38, 12, 48, 1); box-shadow: 5px 5px 15px #fff;">
                  <div class="card-body px-4 py-5 px-md-5">
                    <form method="post" name="frmCadastro" id="frmCadastro" class="form-signin" novalidate
                      enctype="multipart/form-data">
                      <!-- 2 column grid layout with text inputs for the first and last names -->
                      <div class="row">



                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input value="<?= $user; ?>" autofocus type="text" id="txtUsuario" name="txtUsuario"
                            class="form-control" />
                          <label style="color:#ffff;" class="form-label" for="form3Example3">Usuário</label>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input type="password" id="txtSenha" name="txtSenha" class="form-control" />
                          <label style="color:#ffff;" class="form-label" for="form3Example4">Senha</label>
                        </div>



                        <!-- Submit button -->
                        <input value="Login" id='btnEntrar' name='btnEntrar' type="submit" style='padding:20px;'
                          data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary btn-block mb-4" />
                        <?= $retorno; ?>
                        <a class='btn btn-outline-info' onclick='' href='javascript: func' type='button'
                          data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedidoAtivacao'>

                          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                            class="bi bi-box-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                              d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
                          </svg>
                          Ativação do Sistema
                        </a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php

      } else {
        ?>
        <section style="margin-top:-20px; background-color:rgba(38, 12, 48, 1);"
          class="background-radial-gradient overflow-hidden">

          <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
              <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                <h4 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                  A sua mensalidade do DopDin
                  <span style="color: hsla(0, 87%, 46%, 1.00)">está vencendo hoje</span>
                </h4>
                <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%); margin-top: -40px;">
                  Para ativar seu sistema, escolha um dos planos abaixo e realize o pagamento via Pix:</br>

                  🟢 PLANO MENSAL SIMPLES </br>
                  💰 Valor: R$ 99,00</br>

                  🔵 PLANO MENSAL PREMIUM </br>
                  💰 Valor: R$ 299,00</br>

                  📌 Chave Pix para pagamento:
                  <a href='#'>lgti_ltda@icloud.com</a></br>

                  Após o pagamento, envie o comprovante para ativação via WhatsApp:</br>
                  👉 <a
                    href="https://api.whatsapp.com/send/?phone=5597984320699&text=Ol%C3%A1%2C+realizei+o+pagamento+do+plano+mensal+e+gostaria+de+ativar+o+sistema.&type=phone_number&app_absent=0">Clique
                    aqui para enviar no WhatsApp</a></br>

                  A ativação será feita rapidamente após a confirmação.</br>
                  Obrigado por escolher nosso sistema! ✅</br>
                </p>
              </div>

              <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                <div class="card bg-glass" style="background-color:rgba(38, 12, 48, 1); box-shadow: 5px 5px 15px #fff;">
                  <div class="card-body px-4 py-5 px-md-5">
                    <form method="post" name="frmCadastro" id="frmCadastro" class="form-signin" novalidate
                      enctype="multipart/form-data">
                      <!-- 2 column grid layout with text inputs for the first and last names -->
                      <div class="row">



                        <!-- Email input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input value="<?= $user; ?>" autofocus type="text" id="txtUsuario" name="txtUsuario"
                            class="form-control" />
                          <label style="color:#ffff;" class="form-label" for="form3Example3">Usuário</label>
                        </div>

                        <!-- Password input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                          <input type="password" id="txtSenha" name="txtSenha" class="form-control" />
                          <label style="color:#ffff;" class="form-label" for="form3Example4">Senha</label>
                        </div>



                        <!-- Submit button -->
                        <input value="Login" id='btnEntrar' name='btnEntrar' type="submit" style='padding:20px;'
                          data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary btn-block mb-4" />
                        <?= $retorno; ?>

                        <a class='btn btn-outline-info' onclick='' href='javascript: func' type='button'
                          data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedidoAtivacao'>

                          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                            class="bi bi-box-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                              d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
                          </svg>
                          Ativação do Sistema
                        </a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php
      }
      ?>

      <!-- Section: Design Block -->

    <?php } else {
      $cod_funcionario = (int) $_SESSION['codF'];
      $permissao_funcionario = (int) $_SESSION['permissaoF'];
      require_once("Util/ResquestPagePrincipal.php");
    }
  }
  ?>

  <!-- Modal Detalhes do Pedido -->
  <div class="modal fade" id="exampleModalDetalhesPedidoAtivacao" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Ativação do Sistema </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <select onchange="validacao(89, this.value, 0, 89)" name="txtCategoriaPesquisa" id="txtCategoriaPesquisa"
            style="padding:20px; margin-top:5px;" class="form-select" aria-label="Selecione uma Categoria">
            <option value="0" selected>Selecione um Plano Para Continuar</option>
            <option value="1">Plano Simples Mensal - R$ 100,00</option>
            <option value="2">Plano Premiun Mensal - R$ 299,00</option>
            <option value="3">Plano Vitálicio - R$ 1200,00</option>

          </select>

          <div id='ResultadoValidacao89'>

          </div>
          <form method="post" name="frmCadastro" id="frmCadastro" class="form-signin" novalidate
            enctype="multipart/form-data">

            <!-- 2 column grid layout with text inputs for the first and last names -->
            <div class="row">
              <!-- Email input -->
              <div data-mdb-input-init class="form-outline mb-4">
                </BR> <label style="color:#000;" class="form-label" for="txtSerialAtivacao">Informe Serial de Ativação,
                  Fornecido pelo administrador do Sistema</label>

                <input value="" autofocus type="text" id="txtSerialAtivacao" name="txtSerialAtivacao"
                  class="form-control" />
              </div>

              <!-- Submit button -->
              <input value="ATIVAR" id='btnAtivarSistema' name='btnAtivarSistema' type="submit" style='padding:20px;'
                data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-primary btn-block mb-4" />
              <?= $retorno; ?>

          </form>
        </div>

      </div>
    </div>
  </div>

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
    var div25 = document.getElementById("DivCadastroLojas");
    var div14 = document.getElementById("ResultadoPesquisaUsuario");
    var div24 = document.getElementById("ResultadoPesquisaLoja");


    var div15 = document.getElementById("ResultadoPesquisaDespesa");
    var div16 = document.getElementById("DivCadastroDespesa");
    var div17 = document.getElementById("DivCadastroCategoriaDespesa");



    var div18 = document.getElementById("BtnMostrarFoto");
    var div19 = document.getElementById("BtnMostrarPerfil");
    var div20 = document.getElementById("DivParaAtualizarDadosUSu");
    var div21 = document.getElementById("DivParaTrocarImagemUsu");
    var div22 = document.getElementById("DivParaGerarRelatorioDespesa");

    var div23 = document.getElementById("DivLojas");




    var div1b = document.getElementById("BotaoHome");
    var div2b = document.getElementById("BotaoProdutos");
    var div3b = document.getElementById("BotaoRelatorios");
    var div4b = document.getElementById("BotaoUsuarios");
    var div5b = document.getElementById("BotaoContas");
    var div6b = document.getElementById("BotaoDespesas");
    var div7b = document.getElementById("BotaoConfiguracoes");
    var div8b = document.getElementById("BotaoMinhaConta");
    var div9b = document.getElementById("BotaoLojas");



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
      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";
      


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
      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";

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

      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";

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
      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";

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
      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";



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
      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";


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

      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";
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

      
      div9b.className = "nav-link  py-3 border-bottom";
      div23.style.display = "none";


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

    if(teste==21){
      div23.style.display = "block";
      div1.style.display = "none";
      div2.style.display = "none";
      div3.style.display = "none";
      div4.style.display = "none";
      div5.style.display = "none";
      div6.style.display = "none";
      div7.style.display = "none";
      div8.style.display = "none";
      div8.style.display = "none";

      
      div9b.className = "nav-link active py-3 border-bottom";
      div1b.className = "nav-link  py-3 border-bottom";
      div2b.className = "nav-link  py-3 border-bottom";
      div3b.className = "nav-link  py-3 border-bottom";
      div4b.className = "nav-link  py-3 border-bottom";
      div5b.className = "nav-link  py-3 border-bottom";
      div6b.className = "nav-link  py-3 border-bottom";
      div7b.className = "nav-link  py-3 border-bottom";
      div8b.className = "nav-link  py-3 border-bottom";
    }

    if(teste==24){
      div25.style.display = "none";
      div24.style.display = "block";
    }
    if(teste==25){
      div25.style.display = "block";
      div24.style.display = "none";
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
    document.getElementById("DivLojas").style.display = "none";

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

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInput = document.getElementById('txtCnpj');
    VMasker(phoneInput).maskPattern('99.999.999/9999-99');
  });



  //FUNCOES PARA FORMATAR O INPUT DO PESQUISAR POR CONTATO E CPF 

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInputCPF = document.getElementById('txtPesquisarCpf');
    VMasker(phoneInputCPF).maskPattern('999.999.999-99');
  });

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInputCPF = document.getElementById('txtCpfRes2');
    VMasker(phoneInputCPF).maskPattern('999.999.999-99');
  });

  document.addEventListener('DOMContentLoaded', function () {
    var phoneInputCPF = document.getElementById('txtCpfRes1');
    VMasker(phoneInputCPF).maskPattern('999.999.999-99');
  });



  document.addEventListener('DOMContentLoaded', function () {
    var phoneInputCPF = document.getElementById('txtSerialAtivacao');
    VMasker(phoneInputCPF).maskPattern('AAAA-AAAA');
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
    document.getElementById("DivCadastroLojas").style.display = "none";
    document.getElementById("DivParaTrocarImagemUsu").style.display = "none";
    document.getElementById("BtnMostrarPerfil").style.display = "none";
    document.getElementById("DivLoja").style.display = "none";
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
<script src="Interface/js/scriptpdf.js"></script>



<script>
  document.getElementById('txtNascimento').addEventListener('input', function (e) {
    let input = e.target;
    input.value = input.value
      .replace(/\D/g, '') // remove não dígitos
      .replace(/(\d{2})(\d)/, '$1/$2')
      .replace(/(\d{2})(\d)/, '$1/$2')
      .replace(/(\d{4})\d+?$/, '$1'); // limita a 4 dígitos no ano
  });
</script>

<style>
  .btn-flutuante-imprimir {
    position: fixed;
    bottom: 20px;
    right: 20px;
    border: 2px solid red;
    background: #f9f9f9;
    /* tom neutro claro */
    color: red;
    font-size: 20px;
    padding: 8px 10px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
  }

  .btn-flutuante-imprimir:hover {
    background: #ffe5e5;
    /* tom suave relacionado ao vermelho */
    color: red;
  }
</style>
<!--- INICIO DA FUNÇÃO PARA GERAR PDF  --->
<style>
  /* Esconde na tela */
  #ORDEMSERVICO {
    display: none;
  }

  /* Mostra somente na impressão (PDF) */
  @media print {
    #ORDEMSERVICO {
      display: block !important;
    }
  }
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
  integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
  integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script src="Interface/js/scriptpdf.js" defer></script>
<style>
  /* Tenta evitar quebras dentro de elementos */
  table {
    width: 100%;
    border-collapse: collapse;
    page-break-inside: auto;
  }

  tr {
    page-break-inside: avoid !important;
    page-break-after: auto;
  }

  td,
  th {
    word-wrap: break-word;
    white-space: normal;
  }

  @media print {
    body {
      -webkit-print-color-adjust: exact;
    }
  }
</style>

</html>
