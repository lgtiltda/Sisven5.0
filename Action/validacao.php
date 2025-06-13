<?php
include("conn.php");
include("Banco.php");
include("functions.php");
session_start();
//Error_reporting(0);
$banco = new Banco();


$tipo = $_GET['tipo'];
switch ($tipo) {

  //VALIDACAO PARA PESQUISAR PRODUTO E SERVIÇOS EM CARRINHO DE COMPRAS
  case 1:


    $param = $_GET['param'];
    $codnota = $_GET['param'];
    $valor = $_GET['valor'];

    $sqlServicoscont = "SELECT count(*) as total from servicos WHERE nome LIKE :nome";
    $paramServicoscount = array(
      ":nome" => "%{$valor}%"
    );

    $dataTableServicos222 = $banco->ExecuteQuery($sqlServicoscont, $paramServicoscount);
    foreach ($dataTableServicos222 as $resultado22) {
      $totalderegistros = $resultado22['total'];
    }


    //PRIMEIRA PESQUISA PARA VERIFICAR SE HÁ PRODUTO COM MESMO CODIGO DE BARRA OU CODIGO DE BUSCA

    $sqlServicosCodBarra = "SELECT * FROM servicos WHERE  codbarra = :codbarra AND codbarra != '' || codbusca = :codbusca AND codbusca != 0 ORDER BY cod ASC LIMIT 1";
    $paramServicosCodBarra = array(
      ":codbarra" => $valor,
      ":codbusca" => $valor,
    );

    $dataTableServicos = $banco->ExecuteQuery($sqlServicosCodBarra, $paramServicosCodBarra);



    //SE NÃO FOR LOCALIZADO NENHUM ITEM COM O CODIGO DE BARRA OU COD BUSCA - O SISTEMA IRA PESQUISA NOS ITENS POR NOME
    if ($dataTableServicos == null) {


      $sqlServicos = "SELECT * FROM servicos WHERE nome LIKE :nome ORDER BY cod ASC LIMIT 10";
      $paramServicos = array(
        ":nome" => "%{$valor}%"
      );

      $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);

    }

    $codindex = 2;

    foreach ($dataTableServicos as $resultadoservicos) {



      $codservico = $resultadoservicos['cod'];
      $descricaoservico = $resultadoservicos['descricao'];
      $nomeservico = $resultadoservicos['nome'];
      $categoriaservico = $resultadoservicos['categoria'];
      $img = $resultadoservicos['img'];
      $imgservico = $resultadoservicos['img'];
      $tiposervico = 1;
      $qtdservico = $resultadoservicos['qtd'];
      $valorservico = $resultadoservicos['valor'];
      $tiposervico = $resultadoservicos['tipo'];


      if ($imgservico == null) {
        $imgservico = "logo.ico";
      }

      $valorformatado = number_format($resultadoservicos['valor'], 2, ',', '.');
      //FUNCAO PARA CONTAR ITEM QUE JÁ ESTÃO EM PROCESSO DE VENDA
      $qtdreservada = 0;
      $sqlPedidos23 = "SELECT * FROM pedidos WHERE servico = :cod AND status != 3 ORDER BY cod ASC";
      $paramPedidos23 = array(
        ":cod" => $codservico
      );

      $dataTablePedidos23 = $banco->ExecuteQuery($sqlPedidos23, $paramPedidos23);
      foreach ($dataTablePedidos23 as $resultadopedidos23) {
        $qtdreservada = $qtdreservada + $resultadopedidos23['qtd'];
      }
      //FIM DA FUNCAO PARA CONTAGEM

      //FUNCAO PARA CHAMAR NOME DA CATEGORIA DO ITEM
      $categoriaservico = (float) $resultadoservicos['categoria'];
      $sqlCategorias = "SELECT * FROM categoriaserfin WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCategorias = array(
        ":cod" => $categoriaservico
      );
      $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
      foreach ($dataTableCategorias as $resultadocategorias) {
        $nomecategoria = $resultadocategorias['nome'];
      }


      //EQUAÇÃO PARA ESTOQUE DISPONIVEL
      $qtdservico = $qtdservico - $qtdreservada;


      if ($tiposervico == 0) {
        $textotipo = "SERVIÇO";
      } else {
        $textotipo = "PRODUTO";
      }


      if ($tiposervico == 0) {

        echo "<div class='card-header py-3'>
      <h5 class='mb-0'>$nomeservico</h5>
    </div>
    <div class='card-body'>
      <div class='row'>
        <div class='col-lg-3 col-md-12 mb-4 mb-lg-0'>
          <div class='bg-image hover-overlay hover-zoom ripple rounded' data-mdb-ripple-color='light'>
            <img style='  ' src='Interface/img/Servicos/logo.ico' class='w-100' />
            <a href='#!'>
              <div class='mask' style='background-color: rgba(251, 251, 251, 0.2)'></div>
            </a>
          </div>
        </div>
        <div class='col-lg-5 col-md-6 mb-4 mb-lg-0'>
          <p><strong>Categoria: $nomecategoria</strong></p>
          <p>Tipo: $textotipo</p>
          ";
        if ($tiposervico == 0) {
          echo "";
        } else {
          if ($qtdservico > 0) {
            echo "
          <p>Qtd: $qtdservico</p>
          <p>Qtd reservada: $qtdreservada</p>
         ";
          } else {
            echo "<div class='alert alert-warning'>Ñ Disponível</div>";
          }
        }

        echo " </button>
          <!-- Data -->
        </div>
        
        <div class='col-lg-4 col-md-6 mb-4 mb-lg-0'>
          <p class='text-start text-md-center' style='font-size:14pt;'>Valor Unt.
            <strong>R$ $valorformatado</strong></br>
            ";
        if ($tiposervico == 1) {
          if ($qtdservico > 0) {
            echo " 
                <a href='javascript:func()' onclick='CadastrarPedido(1, $codservico, $codnota, $valorservico, $categoriaservico, 1)' type='button' id='liveToastBtn' tabindex='$codindex' data-mdb-button-init data-mdb-ripple-init class='btn btn-outline-success px-2 me-1' onclick='this.parentNode.querySelector('input[type=number]').stepUp()'>
                 <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='currentColor' class='bi bi-cart-plus' viewBox='0 0 16 16'>
                    <path d='M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z'/>
                    <path d='M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0'/>
                  </svg>
                  </a>";
          } else {
            echo "<div class='alert alert-danger' style='text-align:justify'>Por favor, para realizar venda deste produto, o usuário deverá atualizar o valor de estoque. </div>";
          }
        } else {
          echo " 
              <a href='javascript:func()' onclick='CadastrarPedido(1, $codservico, $codnota, $valorservico, $categoriaservico, 1)' type='button' id='liveToastBtn' tabindex='$codindex' data-mdb-button-init data-mdb-ripple-init class='btn btn-outline-success px-2 me-1' onclick='this.parentNode.querySelector('input[type=number]').stepUp()'>
               <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='currentColor' class='bi bi-cart-plus' viewBox='0 0 16 16'>
                  <path d='M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z'/>
                  <path d='M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0'/>
                </svg>
                </a>";
        }

        echo " 
            </p>
        </div>
      </div>
    </div>";
        $codindex++;
      } else if ($tiposervico == 1) {
        if ($qtdservico > 0) {

          echo "<div class='card-header py-3'>
            <h5 class='mb-0'>$nomeservico</h5>
          </div>
          <div class='card-body'>
            <div class='row'>
              <div class='col-lg-3 col-md-12 mb-4 mb-lg-0'>
                <div class='bg-image hover-overlay hover-zoom ripple rounded' data-mdb-ripple-color='light'>
                  <img style='  ' src='Interface/img/Servicos/logo.ico' class='w-100' />
                  <a href='#!'>
                    <div class='mask' style='background-color: rgba(251, 251, 251, 0.2)'></div>
                  </a>
                </div>
              </div>
              <div class='col-lg-5 col-md-6 mb-4 mb-lg-0'>
                <p><strong>Categoria: $nomecategoria</strong></p>
                <p>Tipo: $textotipo</p>
                ";
          if ($tiposervico == 0) {
            echo "";
          } else {
            if ($qtdservico > 0) {
              echo "
                <p>Qtd: $qtdservico</p>
                <p>Qtd reservada: $qtdreservada</p>
               ";
            } else {
              echo "<div class='alert alert-warning'>Ñ Disponível</div>";
            }
          }

          echo " </button>
                <!-- Data -->
              </div>
              
              <div class='col-lg-4 col-md-6 mb-4 mb-lg-0'>
                <p class='text-start text-md-center' style='font-size:14pt;'>Valor Unt.
                  <strong>R$ $valorformatado</strong></br>
                  ";
          if ($tiposervico == 1) {
            if ($qtdservico > 0) {
              echo " 
                      <a href='javascript:func()' onclick='CadastrarPedido(1, $codservico, $codnota, $valorservico, $categoriaservico, 1)' type='button' id='liveToastBtn' tabindex='$codindex' data-mdb-button-init data-mdb-ripple-init class='btn btn-outline-success px-2 me-1' onclick='this.parentNode.querySelector('input[type=number]').stepUp()'>
                       <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='currentColor' class='bi bi-cart-plus' viewBox='0 0 16 16'>
                          <path d='M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z'/>
                          <path d='M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0'/>
                        </svg>
                        </a>";
            } else {
              echo "<div class='alert alert-danger' style='text-align:justify'>Por favor, para realizar venda deste produto, o usuário deverá atualizar o valor de estoque. </div>";
            }
          } else {
            echo " 
                    <a href='javascript:func()' onclick='CadastrarPedido(1, $codservico, $codnota, $valorservico, $categoriaservico, 1)' type='button' id='liveToastBtn' tabindex='$codindex' data-mdb-button-init data-mdb-ripple-init class='btn btn-outline-success px-2 me-1' onclick='this.parentNode.querySelector('input[type=number]').stepUp()'>
                     <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='currentColor' class='bi bi-cart-plus' viewBox='0 0 16 16'>
                        <path d='M9 5.5a.5.5 0 0 0-1 0V7H6.5a.5.5 0 0 0 0 1H8v1.5a.5.5 0 0 0 1 0V8h1.5a.5.5 0 0 0 0-1H9z'/>
                        <path d='M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1zm3.915 10L3.102 4h10.796l-1.313 7zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0m7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0'/>
                      </svg>
                      </a>";
          }

          echo " 
                  </p>
              </div>
            </div>
          </div>";
          $codindex++;
        }
      }
    }


    break;


  //FUNCAO PARA ATUALIZAR VALOR TOTAL EM FINALIZAR PAGAMENTO
  case 2:
    $codnota = $_GET['param'];
    $valor = $_GET['valor'];
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

    $totalfinal = number_format($totalfinal, 2, ',', '.');
    echo "
      <div class='input-group mb-3'>
            <span class='input-group-text' id='basic-addon1'>
              <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-coin' viewBox='0 0 16 16'>
                <path d='M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z' />
                <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16' />
                <path d='M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12' />
              </svg>
            </span>
            <input name='txtTotalReal' id='txtTotalReal' type='hidden' value='$codnota'>
            <input name='txtTotal' id='txtTotal' disabled type='text' value='R$ $totalfinal' class='form-control' placeholder='Valor Total' aria-label='Username' aria-describedby='basic-addon1'>
          </div>
          <div class='input-group mb-3'>
            <span class='input-group-text' id='basic-addon1'>
              <svg style='fill:#FF0000;' xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-arrow-left-right' viewBox='0 0 16 16'>
                <path fill-rule='evenodd' d='M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5' />
              </svg>

            </span>
            <input name='txtTrocoReal' id='txtTrocoReal' type='hidden'>
            <input disabled name='txtTroco' id='txtTroco' type='text' class='form-control' placeholder='Troco' aria-label='Username' aria-describedby='basic-addon1'>
          </div>
          
     
    ";
    break;

  //FUNCAO PARA VALIDAR VALORES INFORMADOS COMO PAGAMENTO, REALIZAR LOGICA PARA DESCONTO E CONSISTENCIA DAS INFORMAÇÕES.
  case 3:

    $param1 = $_GET['param1']; //DINHEIRO 
    $param2 = $_GET['param2']; //PIX
    $param3 = $_GET['param3']; //DEBITO
    $param4 = $_GET['param4']; //CREDITO
    $codnota = $_GET['param5']; //codnota
    $param6 = $_GET['param6']; //DESCONTO
    $msgteste = null;

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


    $param5 = $totalfinal;
    $paramnovo5 = (float) $param5;

    $paramnovo1 = str_replace("R$", "", $param1);
    $paramnovo2 = str_replace("R$", "", $param2);
    $paramnovo3 = str_replace("R$", "", $param3);
    $paramnovo4 = str_replace("R$", "", $param4);
    $paramnovo6 = str_replace("R$", "", $param6);


    //FORMATAR O VALOR UNT PARA O MODELO AMERICANO ACEITO PELO BANCO DE DADOS
    $pontos = '.';
    $result = str_replace($pontos, "", $paramnovo1);
    $result = str_replace(",", ".", $result);
    $paramnovo1 = $result;

    $result = str_replace($pontos, "", $paramnovo2);
    $result = str_replace(",", ".", $result);
    $paramnovo2 = $result;


    $result = str_replace($pontos, "", $paramnovo3);
    $result = str_replace(",", ".", $result);
    $paramnovo3 = $result;


    $result = str_replace($pontos, "", $paramnovo4);
    $result = str_replace(",", ".", $result);
    $paramnovo4 = $result;

    $result = str_replace($pontos, "", $paramnovo6);
    $result = str_replace(",", ".", $result);
    $paramnovo6 = $result;

    $paramnovo1 = (float) $paramnovo1;
    $paramnovo2 = (float) $paramnovo2;
    $paramnovo3 = (float) $paramnovo3;
    $paramnovo4 = (float) $paramnovo4;
    $paramnovo5 = (float) $paramnovo5;
    $paramnovo6 = (float) $paramnovo6;



    $soma_pamento_alternativo = $paramnovo2 + $paramnovo3 + $paramnovo4;


    $pagamento_total = $paramnovo1 + $soma_pamento_alternativo;
    $operacao_desconto = $paramnovo5 - $paramnovo6;

    $troco = 0;
    $totalfinal = $operacao_desconto;
    $troco = $pagamento_total - $operacao_desconto;


    if ($soma_pamento_alternativo > $operacao_desconto) {
      $msgteste = "<div class='alert alert-danger' style='font-size:7pt;'>O valores inseridos nos pagamentos alternativos ao dinheiro ultrapassam o valor total da compra.</div>";
    }

    if ($troco < 0) {
      $troco = 0;
      $msgteste = "<div class='alert alert-danger' style='font-size:7pt;'>Valor Pagamento Inváldio</div>";
    }

    $troco = number_format($troco, 2, ',', '.');
    $totalfinal = number_format($totalfinal, 2, ',', '.');

    echo "
      <div class='input-group mb-3'>
            <span class='input-group-text' id='basic-addon1'>
              <svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-coin' viewBox='0 0 16 16'>
                <path d='M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z' />
                <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16' />
                <path d='M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12' />
              </svg>
            </span>
            <input name='txtTotalReal' id='txtTotalReal' type='hidden' value='$codnota'>
            <input name='txtTotal' id='txtTotal' disabled type='text' value='R$ $totalfinal' class='form-control' placeholder='Valor Total' aria-label='Username' aria-describedby='basic-addon1'>
          </div>
          <div class='input-group mb-3'>
            <span class='input-group-text' id='basic-addon1'>
              <svg style='fill:#FF0000;' xmlns='http://www.w3.org/2000/svg' width='30' height='30' fill='currentColor' class='bi bi-arrow-left-right' viewBox='0 0 16 16'>
                <path fill-rule='evenodd' d='M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5' />
              </svg>

            </span>
            <input name='txtTrocoReal' id='txtTrocoReal' type='hidden' value='$troco'>
            <input disabled value='R$ $troco' name='txtTroco' id='txtTroco' type='text' class='form-control' placeholder='Troco' aria-label='Username' aria-describedby='basic-addon1'>
          </div>
          ";
    if ($msgteste == null) {
      echo "       
            <input style='width:100%; padding:10px;' id='btnFinalizarPagamento' name='btnFinalizarPagamento' type='submit' class='btn btn-outline-success' value='Finalizar Pagamento' />
         
            ";
    } else {
      echo $msgteste;
    }
    echo "
          
    ";
    break;
  //FUNCAO PARA CHAMAR DE LISTA DE ITENS
  case 4:
    $codnota = $_GET['param'];
    $total = 0;
    $contador = 0;
    $cont = 0;
    echo "
             <table class='table table-striped table-sm' style='font-size:10pt; width:100%'>";
    echo "
			<tr>
				<td style='width:50%;'><b>Produto</b></td>
				<td><b>Qtd</b></td>
				<td><b>Valor Unt.</b></td>
				<td><b>Valor Total</b></td>
				<td style=''><b>Obs</b></td>
				<td><b></b></td>
			</tr>
			";
    //$sql = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
    $sqlPedidos2 = "SELECT * FROM pedidos WHERE usuario = :cod ORDER BY cod DESC";
    $paramPedidos2 = array(
      ":cod" => $codnota
    );

    $dataTablePedidos2 = $banco->ExecuteQuery($sqlPedidos2, $paramPedidos2);
    foreach ($dataTablePedidos2 as $resultadopedidos2) {

      $codpedido = $resultadopedidos2['cod'];
      $codservico = $resultadopedidos2['servico'];

      if ($codservico == 0) {
        $nomeservico = $resultadopedidos2['obs'] . "<b><small>(Avulso)</small>" . "<b>";
        $obs = "";
      } else {
        $categoria = $resultadopedidos2['categoria'];
        //$sql3 = mysqli_query($conn, "SELECT * FROM categoriaserfin WHERE cod=$categoria LIMIT 1");
        $sqlCategorias = "SELECT * FROM categoriaserfin WHERE cod=  :cod LIMIT 1";
        $paramCategorias = array(
          ":cod" => $categoria
        );
        $nomecategoria = "Avulso";
        $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
        foreach ($dataTableCategorias as $resultadocategorias) {
          $nomecategoria = $resultadocategorias['nome'];
        }
        //$sql2 = mysqli_query($conn, "SELECT * FROM servicos WHERE cod=" . $codservico . " LIMIT 1");
        $sqlServicos = "SELECT * FROM servicos WHERE cod= :cod LIMIT 1";
        $paramServicos = array(
          ":cod" => $codservico
        );

        $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
        foreach ($dataTableServicos as $resultadoservicos) {
          $nomeservico = $resultadoservicos['nome'] . "<b><small>(" . $nomecategoria . ")</small>" . "<b>";
          $obs = $resultadopedidos2['obs'];
        }
      }
      $valor = (float) $resultadopedidos2['valor'];
      $qtd = (float) $resultadopedidos2['qtd'];
      $valorunt = $valor / $qtd;
      $total = $total + $valor;
      $codpedido = $resultadopedidos2['cod'];
      $nomecategoria = "";
      $valorunt = number_format($valorunt, 2, ',', '.');
      $valor = number_format($valor, 2, ',', '.');
      $contador++;
      echo "
				
					<tr>
						<td>" . $nomeservico . "</td>
						<td>$qtd</td>
						<td>R$ " . $valorunt . "</td>
						<td>R$ " . $valor . "</td>
						<td>$obs</td>
						<td>
						  <a style='font-size:10pt;' tabindex='$contador' id='primeirobotao' href='javascript: func()' onclick='validacao(8, $codnota, $codpedido, 3)'    class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                  <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                </svg></a>   
						</td>
							
					</tr>
		   ";
      $contador++;
    }
    $total = number_format($total, 2, ',', '.');
    echo "
			<tr>
				<td colspan='3' style='text-align:right;'>Total:</td>
				<td colspan='3'><b>R$ " . $total . "</b></td>
        
			</tr>
			";
    echo "</table>";

    break;

  //FUNCAO PARA MOSTRAR INFORMAÇÕES PARA EDITAR ULTIMO ITEM.
  case 5:

    $codnota = $_GET['param'];
    $codpedido = $_GET['valor'];
    $total = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM pedidos WHERE cod = " . $codpedido . " ORDER BY cod ASC");
    $sqlPedidos = "SELECT * FROM pedidos WHERE usuario = :cod ORDER BY cod DESC LIMIT 1";
    $paramPedidos = array(
      ":cod" => $codnota
    );
    $nomecategoria = "Avulso";
    $dataTablePedidos = $banco->ExecuteQuery($sqlPedidos, $paramPedidos);
    foreach ($dataTablePedidos as $resultadopedidos) {

      $codservico = $resultadopedidos['servico'];
      $codpedidoCard = $resultadopedidos['cod'];

      if ($codservico == 0) {
        $nomeservico = $resultadopedidos['obs'];
        $obs = $nomeservico;
        $valor = (float) $resultadopedidos['valor'];
        $qtd = (float) $resultadopedidos['qtd'];
        $valorunt = $valor / $qtd;
      } else {
        $categoria = $resultadopedidos['categoria'];
        //$sql3 = mysqli_query($conn, "SELECT * FROM categoriaserfin WHERE cod=$categoria LIMIT 1");
        $sqlCategorias = "SELECT * FROM categoriaserfin WHERE cod= :cod LIMIT 1";
        $paramCategorias = array(
          ":cod" => $categoria
        );
        $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
        foreach ($dataTableCategorias as $resultadocategorias) {
          $nomecategoria = $resultadocategorias['nome'];
        }
        //$sql2 = mysqli_query($conn, "SELECT * FROM servicos WHERE cod=" . $codservico . " LIMIT 1");
        $sqlServicos = "SELECT * FROM servicos WHERE cod= :cod LIMIT 1";
        $paramServicos = array(
          ":cod" => $codservico
        );
        $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
        foreach ($dataTableServicos as $resultadoservicos) {
          $nomeservico = $resultadoservicos['nome'] . "(" . $nomecategoria . ")";
          $valorunt = $resultadoservicos['valor'];
          $obs = $resultadopedidos['obs'];
        }
      }

      $valor = (float) $resultadopedidos['valor'];
      $codpedido = $resultadopedidos['cod'];
      $valorunt = number_format($valorunt, 2, ',', '.');
      $valor = number_format($valor, 2, ',', '.');
    }

    echo "
          <div class='row'>
          <div class='col-md-12'>
            <input type='hidden' value='$codpedido' id='txtCodpedido' name='txtCodpedido' />
            <input type='hidden' value='$codnota' id='txtCodnota' name='txtCodnota' />
            <label for='txtNomeCompletoCadRes' class='form-label'>Descrição do Item</label>
            <input value='$nomeservico' disabled type='text' class='form-control form-control-lg' id='txtDescricaoEd' name='txtDescricaoEd' value='' required>
            <div class='valid-feedback'>
              Correto!
            </div>
          </div>
          <div class='col-md-6'>
            <label for='txtContatoCadRes' class='form-label'>Valor Unt.</label>
            <input disabled value='$valorunt' type='text' class='form-control form-control-lg' id='txtValorUntEd' name='txtValorUntEd' value='' required>
            <div class='valid-feedback'>
              Correto!
            </div>
          </div>
           <div class='col-md-6' id='ResultadoValidacao6'>
              <label for='txtNomeCompletoCadRes' class='form-label'>Valor Total</label>
              <input type='hidden' id='txtValorTotalRealEd' id='txtValorTotalRealEd' value='$valor' required>
              <input type='text' class='form-control form-control-lg' id='txtValorTotal' disabled value='$valor' required>
              <div class='valid-feedback'>
                Correto!
              </div>
            </div>

          <div class='col-md-12' style='margin-bottom:10px;'>
            <label for='txtObsEd' class='form-label'>Observação</label>
            <input type='text' class='form-control form-control-lg' id='txtObsEd' name='txtObsEd' value='$obs' >
            <div class='valid-feedback'>
              Correto!
            </div>
          </div>


          <div class='col-12'>
          <input value='Atualizar Item' style='width:100%; padding:10px;' id='btnEditarQtdValor' name='btnEditarQtdValor' type='submit' class='btn btn-outline-success' />

          
          </div>
       
        </div>
      
        ";

    break;

  //VALIDACAO PARA GERAR VALOR TOTAL EM EDITAR ULT. ITEM
  case 6:
    $qtditem = (float) $_GET['param'];
    $codnota = $_GET['valor'];

    $sqlPedidos = "SELECT * FROM pedidos WHERE usuario = :cod ORDER BY cod DESC LIMIT 1";
    $paramPedidos = array(
      ":cod" => $codnota
    );
    $dataTablePedidos = $banco->ExecuteQuery($sqlPedidos, $paramPedidos);
    foreach ($dataTablePedidos as $resultadopedidos) {

      $codservico = $resultadopedidos['servico'];

      if ($codservico != 0) {
        $sqlServicos = "SELECT * FROM servicos WHERE cod= :cod LIMIT 1";
        $paramServicos = array(
          ":cod" => $codservico
        );
        $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
        foreach ($dataTableServicos as $resultadoservicos) {
          $obs = $resultadopedidos['obs'];
          $valorunt = $resultadoservicos['valor'];
        }
      } else {
        $valorunt = 0;

        $qtd = $resultadopedidos['qtd'];
        $totalpedido = $resultadopedidos['valor'];

        if ($totalpedido != 0) {
          $qtd = $resultadopedidos['qtd'];
          $valorunt = $totalpedido / $qtd;
        }
      }

      $valortotal = 0;
      $valortotal = $qtditem * $valorunt;

      $codpedido = $resultadopedidos['cod'];
      $valorunt = number_format($valorunt, 2, ',', '.');
      $valortotal = number_format($valortotal, 2, ',', '.');
    }

    echo "  <label for='txtNomeCompletoCadRes' class='form-label'>Valor Total</label>
              <input type='hidden' id='txtValorTotalRealEd' id='txtValorTotalRealEd' value='$valortotal' required>
              <input type='text' class='form-control form-control-lg' id='txtValorTotal' disabled value='$valortotal' required>
              <div class='valid-feedback'>
                Correto!
              </div>";

    break;

  //VALIDACAO PARA GERAR VALOR TOTAL EM CADASTRAR ITEM AVULSO
  case 7:
    $qtditem = (float) $_GET['param']; //qtd
    $valorunt = $_GET['valor']; //valor unt

    //FORMATAR O VALOR UNT PARA O MODELO AMERICANO ACEITO PELO BANCO DE DADOS
    $pontos = '.';
    $result = str_replace($pontos, "", $valorunt);
    $result = str_replace(",", ".", $result);
    $valorunt = (float) $result;

    $valortotal = 0;
    $valorreal = 0;
    $valortotal = $qtditem * $valorunt;
    $valorreal = $qtditem * $valorunt;


    $valortotal = number_format($valortotal, 2, ',', '.');


    echo "  <label for='txtNomeCompletoCadRes' class='form-label'>Valor Total</label>
              <input type='hidden' id='txtValorTotalAvulsoReal' id='txtValorTotalAvulsoReal' value='$valorreal' required>
              <input type='text' class='form-control form-control-lg' id='txtValorTotalAvulso' name='txtValorTotalAvulso' disabled value='$valortotal' required>
              <div class='valid-feedback'>
                Correto!
              </div>";
    break;
  //GERAR PÁGINA PARA APAGAR PEDIDO
  case 8:
    $contador = 0;
    $codnota = $_GET['param'];
    $codpedido = $_GET['valor'];
    $total = 0;
    $query = mysqli_query($conn, "DELETE FROM `pedidos` WHERE cod = $codpedido");
    // Se inserido com scesso
    if ($query) {
      echo "
             <table class='table table-striped table-sm' style='font-size:10pt; width:100%'>";
      echo "
			<tr>
				<td style='width:50%;'><b>Produto</b></td>
				<td><b>Qtd</b></td>
				<td><b>Valor Unt.</b></td>
				<td><b>Valor Total</b></td>
				<td style=''><b>Obs</b></td>
				<td><b></b></td>
			</tr>
			";
      //$sql = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
      $sqlPedidos2 = "SELECT * FROM pedidos WHERE usuario = :cod ORDER BY cod DESC";
      $paramPedidos2 = array(
        ":cod" => $codnota
      );

      $dataTablePedidos2 = $banco->ExecuteQuery($sqlPedidos2, $paramPedidos2);
      foreach ($dataTablePedidos2 as $resultadopedidos2) {

        $codpedido = $resultadopedidos2['cod'];
        $codservico = $resultadopedidos2['servico'];

        if ($codservico == 0) {
          $nomeservico = $resultadopedidos2['obs'] . "<b><small>(Avulso)</small>" . "<b>";
          $obs = "";
        } else {
          $categoria = $resultadopedidos2['categoria'];
          //$sql3 = mysqli_query($conn, "SELECT * FROM categoriaserfin WHERE cod=$categoria LIMIT 1");
          $sqlCategorias = "SELECT * FROM categoriaserfin WHERE cod=  :cod LIMIT 1";
          $paramCategorias = array(
            ":cod" => $categoria
          );
          $nomecategoria = "Avulso";
          $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
          foreach ($dataTableCategorias as $resultadocategorias) {
            $nomecategoria = $resultadocategorias['nome'];
          }
          //$sql2 = mysqli_query($conn, "SELECT * FROM servicos WHERE cod=" . $codservico . " LIMIT 1");
          $sqlServicos = "SELECT * FROM servicos WHERE cod= :cod LIMIT 1";
          $paramServicos = array(
            ":cod" => $codservico
          );

          $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
          foreach ($dataTableServicos as $resultadoservicos) {
            $nomeservico = $resultadoservicos['nome'] . "<b><small>(" . $nomecategoria . ")</small>" . "<b>";
            $obs = $resultadopedidos2['obs'];
          }
        }
        $valor = (float) $resultadopedidos2['valor'];
        $qtd = (float) $resultadopedidos2['qtd'];
        $valorunt = $valor / $qtd;
        $total = $total + $valor;
        $codpedido = $resultadopedidos2['cod'];
        $nomecategoria = "";
        $valorunt = number_format($valorunt, 2, ',', '.');
        $valor = number_format($valor, 2, ',', '.');

        echo "
				
					<tr>
						<td>" . $nomeservico . "</td>
						<td>$qtd</td>
						<td>R$ " . $valorunt . "</td>
						<td>R$ " . $valor . "</td>
						<td>$obs</td>
						<td>
						  <a style='font-size:10pt;' tabindex='$contador' id='primeirobotao' href='javascript: func()' onclick='validacao(8, $codnota, $codpedido, 3)'    class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span> <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                  <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>
                </svg></a>   
						</td>
							
					</tr>
		   ";
        $contador++;
      }
      $total = number_format($total, 2, ',', '.');
      echo "
			<tr>
				<td colspan='3' style='text-align:right;'>Total:</td>
				<td colspan='3'><b>R$ " . $total . "</b></td>
        
			</tr>
			";
      echo "</table>";
    }
    break;

  //FUNCAO DE PESQUISAR CLIENTES EM PAGAMENTO DE VENDA

  case 9:
    $codnota = $_GET['valor']; //CODIGO DA NOTA
    $valor = $_GET['param']; //VALOR DIGITADO NO INPUT
    echo "
      <div class='table-responsive'>
        <table class='table table-striped table-sm'>
          <thead>
            <tr>
              <th>Nome</th>
              <th>#</th>
            </tr>
          </thead>
          <tbody>
          ";
    $mes_hoje = date('m');

    //$sql = mysqli_query($conn, "SELECT * FROM clientes WHERE nome LIKE '%" . $valor . "%' ORDER BY id DESC");
    // Exibe todos os valores encontrados
    $sqlClientes = "SELECT * FROM clientes WHERE nome LIKE :nome ORDER BY id ASC";
    $paramClientes = array(
      ":nome" => "%{$valor}%"
    );

    $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
    foreach ($dataTableClientes as $resultadoclientes) {

      $codcliente = $resultadoclientes['id'];
      $nomecliente = $resultadoclientes['nome'];
      $cpf = $resultadoclientes['cpf'];
      $celular = $resultadoclientes['celular'];




      echo " 
            <tr>
              <td>
               <b>Cod.:</b> $codcliente</br>
                <b>Nome.: </b>$nomecliente 
           </br><b>Contato: </b>$celular
           </br><b>CPF.: </b>$cpf</br>
              </td>
              <td>
              <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
							<input name='txtCodCliente' id='txtCodCliente' value='$codcliente' type='hidden' />
							<input name='txtCodnota' id='txtCodnota' value='$codnota' type='hidden' />
							<input style='width:100%; height:120px; font-size:18pt;' class='btn btn-success btn-sm' type='submit' name='btnSubmitClienteAtualizarNota' id='btnSubmitClienteAtualizarNota' style='' value='SELECIONAR'>
						</form>
					      </td>
              
            </tr>
            

            ";
    }
    echo "
          </tbody>
        </table>
      </div>
            
            ";

    $textobonus = "";
    break;

  //validacao para gerar crediário
  case 10:

    $codnota = $_GET['param'];


    $dinheirooudebito = 0;

    // Procura titulos no banco relacionados ao valor
    $sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");

    $totalfinal = 0;
    $valor = 0;

    while ($pedidos = mysqli_fetch_object($sqlPedido)) {
      $valor = (float) $pedidos->valor;
      $totalfinal = $totalfinal + $valor;
    }

    $totalfinal = number_format($totalfinal, 2, ',', '.');

    echo "  
                <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;' class='needs-validation' novalidate>
             <div class='row' style=''>
                  
                  <input name='txtCodnota' id='txtCodnota' value='$codnota' type='hidden' />
                  <label for='valortotal'><h4>Parcelamento e Juros</h4></label>
                  <div class='input-group' style='width:95%; margin-bottom:10px;'>
                          <select onchange='Parcelamento(11, numparcelas.value, $codnota, this.value, juros2.value)' style='font-size:18pt; padding:15px; height:60px;' class='form-control' id='juros' name='juros'>
                          <option value='1'>Sem Juros</option>
                          <option value='2'>Juros Simples</option>
                          <option value='3'>Juros Composto</option>
                          </select>
                          </div>
                          
                      <div class='input-group' style='width:50%;'>
                          <select onchange='Parcelamento(11, this.value, $codnota, juros.value, juros2.value)' style='font-size:18pt; padding:15px; height:60px;' class='form-control' id='numparcelas' name='numparcelas'>
                          <option value='1'>1x</option>
                          <option value='2'>2x</option>
                          <option value='3'>3x</option>
                          <option value='4'>4x</option>
                          <option value='5'>5x</option>
                    			<option value='6'>6x</option>
                          <option value='7'>7x</option>
                          <option value='8'>8x</option>
                          <option value='9'>9x</option>
                          <option value='10'>10x</option>
                          <option value='11'>11x</option>
                          <option value='12'>12x</option>
                          </select>
                          </div>
                          </br>
                          <div class='input-group' style='width:45%;'>
                          <select onchange='Parcelamento(11, numparcelas.value, $codnota, juros.value, this.value)' style='font-size:18pt; padding:15px; height:60px;' class='form-control' id='juros2' name='juros2'>
                          <option value='1'>1%</option>
                          <option value='2'>2%</option>
                          <option value='3'>3%</option>
                          <option value='4'>4%</option>
                          <option value='5'>5%</option>
                          <option value='6'>6%</option>
                          <option value='7'>7%</option>
                          <option value='8'>8%</option>
                          <option value='9'>9%</option>
                          <option value='10' selected='selected'>10%</option>
                          <option value='20'>20%</option>
                          <option value='30'>30%</option>
                          <option value='40'>40%</option>
                          <option value='50'>50%</option>
                          <option value='60'>60%</option>
                          <option value='70'>70%</option>
                          <option value='80'>80%</option>
                          <option value='90'>90%</option>
                          <option value='100'>100%</option>
                          </select>
                          </div>
                        <div id='resultadodoparcelamento' class='row' style='margin-bottom:10px;'>

                          <label for='valortotal'><h4>Valor da Parcela</h4></label>
                          <div class='input-group'>
                          <input style='font-size:14pt; padding:25px; ' disabled type='text' class='form-control' id='valorparcela' placeholder='' value='$totalfinal'>
                          </div><label for='valortotal'><h4>Valor Total</h4></label>
                          <div class='input-group'>
                          <input style='font-size:14pt; padding:25px;' disabled type='text' class='form-control' id='valortotal' placeholder='' value='$totalfinal'>
                        
                        </div>
                        
                      </div>
                           
                  </div>
                  <input style='width:95%; padding:20px; font-size:18pt;' class='btn btn-outline-success' type='submit' name='btnCadastrarFinalizarPagamentoCrediario' id='btnCadastrarFinalizarPagamentoCrediario' value='Finalizar Pagamento'>
  
             
              </form>
              
            ";

    break;

  //PAGINA FINAL DE PAGAMENTO - FORMULATARIO DE PAGAMENTO PARCELADO NO CARTÃO DE CREDITO
  case 11:
    $codnota = $_GET['codnota'];
    $numparcelas = (float) $_GET['valor'];
    $juros = $_GET['juros'];
    $juros2 = (float) $_GET['juros2'];
    // Procura titulos no banco relacionados ao valor
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

    if ($juros == 1) {
      $valorparcela = $totalfinal / $numparcelas;
      $totalfinal2 = $totalfinal;
      $totalfinal = number_format($totalfinal, 2, ',', '.');
      $valorparcela = number_format($valorparcela, 2, ',', '.');
    } else if ($juros == 2) {
      $totalfinal = $totalfinal + ($totalfinal * ($juros2 / 100));
      $valorparcela = $totalfinal / $numparcelas;
      $totalfinal2 = $totalfinal;
      $totalfinal = number_format($totalfinal, 2, ',', '.');
      $valorparcela = number_format($valorparcela, 2, ',', '.');
    } else if ($juros == 3) {
      $totalfinalporc = ($totalfinal * ($juros2 / 100));
      $valorparcela = ($totalfinal / $numparcelas) + $totalfinalporc;
      $totalfinal = 0;
      for ($i = 1; $i <= $numparcelas; $i++) {
        $totalfinal = $totalfinal + $valorparcela;
      }
      $totalfinal2 = $totalfinal;
      $totalfinal = number_format($totalfinal, 2, ',', '.');
      $valorparcela = number_format($valorparcela, 2, ',', '.');
    }


    echo "
       <label for='valortotal'><h4>Valor da Parcela</h4></label>
                <div class='input-group'>
                
                <input style='font-size:14pt; padding:25px;' disabled type='text' class='form-control' id='valorparcela' placeholder='' value='$valorparcela'>
                </div>
              
                <label for='valortotal'><h4>Valor Total</h4></label>
                <div class='input-group'>
                
                <input style='font-size:14pt; padding:25px;' disabled type='text' class='form-control' id='valortotal' placeholder='' value='$totalfinal'>
                </div>
               
       ";
    break;


  //FUNCAO DE PESQUISAR CLIENTES EM TELA INICIAL

  case 12:
    $codnota = $_GET['valor']; //CODIGO DA NOTA
    echo $valor = $_GET['param']; //VALOR DIGITADO NO INPUT
    echo "
        <div class='table-responsive'>
          <table class='table table-striped table-sm'>
            <thead>
              <tr>
                <th>Nome</th>
              </tr>
            </thead>
            <tbody>
            ";
    $mes_hoje = date('m');

    //$sql = mysqli_query($conn, "SELECT * FROM clientes WHERE nome LIKE '%" . $valor . "%' ORDER BY id DESC");
    // Exibe todos os valores encontrados
    $sqlClientes = "SELECT * FROM clientes WHERE nome LIKE :nome ORDER BY id ASC";
    $paramClientes = array(
      ":nome" => "%{$valor}%"
    );

    $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
    foreach ($dataTableClientes as $resultadoclientes) {

      $codcliente = $resultadoclientes['id'];
      $nomecliente = $resultadoclientes['nome'];
      $cpf = $resultadoclientes['cpf'];
      $celular = $resultadoclientes['celular'];




      echo " 
              <tr>
                <td>
                 <b>Cod.:</b> $codcliente</br>
                  <b>Nome.: </b>$nomecliente 
             </br><b>Contato: </b>$celular
             </br><b>CPF.: </b>$cpf</br>
                </td>
                </tr>
              <tr>
                  <td>  
                            <a href='index.php?&codcli=$codcliente&msgget=1' type='button' class='btn btn-outline-success btn-sm' style='width:100%; height:40px; font-size:14pt;'>Novo Pedido</a>
                  </td>
              </tr>
  
              ";
    }
    echo "
            </tbody>
          </table>
        </div>
              
              ";

    $textobonus = "";
    break;

  //FUNCAO PARA TRAZER OS CAIXA ABETO DO DIA
  case 13:

    echo "<h5>Lista de Caixa em Aberto</h5>
            <table class='table table-hover'>
        <tr>
          <td><b>Funcionário</b></td>
          <td><b>Data</b></td>
          
          <td></td>
        </tr>
    ";
    //SQL PARA VALIDAR SE A VENDA JÁ FOI REALIZADO O PAGAMENTO
    $sqlTestePag = "SELECT * FROM fechar_caixa WHERE status = :cod ORDER BY cod ASC";
    $paramTestePag = array(
      ":cod" => 1
    );
    $dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);
    foreach ($dataTableTestePag as $resultadocaixa) {
      $codcaixa = $resultadocaixa['cod'];
      $codfuncionario = $resultadocaixa['cod_funcionario'];
      $dia = $resultadocaixa['dia'];
      $mes = $resultadocaixa['mes'];
      $ano = $resultadocaixa['ano'];
      $hora_inicio = $resultadocaixa['hora_inicio'];
      $caixa_inicial = (float) $resultadocaixa['caixa_inicial'];


      $caixa_inicial = number_format($caixa_inicial, 2, ',', '.');

      $data_completa = $dia . '/' . $mes . '/' . $ano . ' ' . $hora_inicio;

      $sqlFunc = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramFunc = array(
        ":cod" => $codfuncionario
      );

      $dataTableFunc = $banco->ExecuteQuery($sqlFunc, $paramFunc);
      foreach ($dataTableFunc as $resultadofunc) {
        $nomefunc = $resultadofunc['nome'];
      }


      echo " <tr>
            <td>$nomefunc</td>
            <td>$data_completa</td>
            <td>
                        <a href='index.php?&codcaixa=$codcaixa&msgget=3' type='button' class='btn btn-outline-success btn-sm' style='width:100%; font-size:9pt;'>Ver Tudo</a>
                  
            </td>
          </tr>
         ";
    }

    echo "         </table>";
    break;


  //FUNCAO PARA TRAZER OS CAIXA ABERTO DO DIA
  case 14:
    $data = $_GET['param'];
    $codfunc = $_GET['valor'];

    $t = explode("-", $data);
    $ano = $t[0];
    $mes = $t[1];
    $dia = $t[2];

    echo "<h5>Resultado da Pesquisa - <a onclick='validacao(13, 0, 0, 13)' href='javascript: func' class='btn btn-outline-primary'>
            <svg style='fill: #000;' xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-left-square-fill' viewBox='0 0 16 16'>
              <path d='M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1' />
            </svg>
            </a></h5>
            <table class='table table-hover'>
        <tr>
          <td><b>Funcionário</b></td>
          <td><b>Data</b></td>
          
          <td></td>
        </tr>
    ";
    //SQL PARA VALIDAR SE A VENDA JÁ FOI REALIZADO O PAGAMENTO
    if ($codfunc != 0) {
      $sqlTestePag = "SELECT * FROM fechar_caixa WHERE dia = :dia AND mes = :mes AND ano = :ano AND cod_funcionario = :codfunc ORDER BY cod ASC";
      $paramTestePag = array(
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
        ":codfunc" => $codfunc
      );
    } else {
      $sqlTestePag = "SELECT * FROM fechar_caixa WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod ASC";
      $paramTestePag = array(
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano
      );
    }
    $dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);
    foreach ($dataTableTestePag as $resultadocaixa) {
      $codcaixa = $resultadocaixa['cod'];
      $codfuncionario = $resultadocaixa['cod_funcionario'];
      $dia = $resultadocaixa['dia'];
      $mes = $resultadocaixa['mes'];
      $ano = $resultadocaixa['ano'];
      $hora_inicio = $resultadocaixa['hora_inicio'];
      $caixa_inicial = (float) $resultadocaixa['caixa_inicial'];


      $caixa_inicial = number_format($caixa_inicial, 2, ',', '.');

      $data_completa = $dia . '/' . $mes . '/' . $ano . ' ' . $hora_inicio;

      $sqlFunc = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramFunc = array(
        ":cod" => $codfuncionario
      );

      $dataTableFunc = $banco->ExecuteQuery($sqlFunc, $paramFunc);
      foreach ($dataTableFunc as $resultadofunc) {
        $nomefunc = $resultadofunc['nome'];
      }


      echo " <tr>
            <td>$nomefunc</td>
            <td>$data_completa</td>
            <td>
                        <a href='index.php?&codcaixa=$codcaixa&msgget=3' type='button' class='btn btn-outline-success btn-sm' style='width:100%; font-size:9pt;'>Ver Tudo</a>
                  
            </td>
          </tr>
         ";
    }

    echo "         </table>";
    break;



  //FUNCAO PARA GERAR PAGINA DE RESUMO DE INFORMAÕES
  case 15:

    //PESQUISA FATURAMENTO DO DIA 

    $dia = date("d");
    $mes = date("m");
    $ano = date("Y");

    $valorTotalFinal = 0;
    $totalfinaldiacat = 0;
    $valorTotalFinalmes = 0;
    $totalfinaldiacatmes = 0;

    //SQL PARA MONTAR FATURAMENTO, DESPESA E SALDO DIÁRIO
    $sqlNota = "SELECT * FROM notas WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod DESC";
    $paramNota = array(
      ":dia" => $dia,
      ":mes" => $mes,
      ":ano" => $ano,
    );


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod DESC LIMIT 1";
      $paramNotaPag = array(
        ":cod" => $codnota
      );

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $valorpag = (float) $resultadonotaPag['total'];
        $valorTotalFinal = $valorTotalFinal + $valorpag;
      }
    }

    //FIM DO SQL PARA FATURAMENTO DO DIA

    //SQL PARA GERAR DESPESA DO DIA
    $sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE dia = $dia AND mes = $mes AND ano = $ano ORDER BY id ASC");
    // Exibe todos os valores encontrados
    while ($dividadia = mysqli_fetch_object($sqlDividasDia)) {

      $total = (float) $dividadia->valor;

      $totalfinaldiacat = $totalfinaldiacat + $total;
    }
    $saldo = 0;
    $saldomes = 0;
    $saldo = $valorTotalFinal - $totalfinaldiacat;
    $valorTotalFinal = number_format($valorTotalFinal, 2, ',', '.');
    $totalfinaldiacat = number_format($totalfinaldiacat, 2, ',', '.');
    $saldo = number_format($saldo, 2, ',', '.');
    //FIM DO SQL




    echo "<h5>Resumo do Dia</h5>

      <table class='table table-hover'>
        <tr>
          <td><b>Faturamento</b></td>
          <td><b>Depesas</b></td>
          <td><b>Saldo</b></td>
        </tr>
        <tr>
          <td>R$ $valorTotalFinal</td>
          <td>R$ $totalfinaldiacat</td>
          <td>R$ $saldo</td>
        </tr>
      </table>
      ";

    $valorTotalFinal = 0;
    $totalfinaldiacat = 0;
    $valorTotalFinalmes = 0;
    $totalfinaldiacatmes = 0;

    //SQL PARA MONTAR FATURAMENTO, DESPESA E SALDO DIÁRIO
    $sqlNota = "SELECT * FROM notas WHERE mes = :mes AND ano = :ano  ORDER BY cod DESC";
    $paramNota = array(
      ":mes" => $mes,
      ":ano" => $ano,
    );


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod DESC LIMIT 1";
      $paramNotaPag = array(
        ":cod" => $codnota
      );

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $valorpag = (float) $resultadonotaPag['total'];
        $valorTotalFinal = $valorTotalFinal + $valorpag;
      }
    }

    //FIM DO SQL PARA FATURAMENTO DO DIA

    //SQL PARA GERAR DESPESA DO DIA
    $sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE mes = $mes AND ano = $ano ORDER BY id ASC");
    // Exibe todos os valores encontrados
    while ($dividadia = mysqli_fetch_object($sqlDividasDia)) {

      $total = (float) $dividadia->valor;

      $totalfinaldiacat = $totalfinaldiacat + $total;
    }
    $saldo = 0;
    $saldomes = 0;
    $saldo = $valorTotalFinal - $totalfinaldiacat;
    $valorTotalFinal = number_format($valorTotalFinal, 2, ',', '.');
    $totalfinaldiacat = number_format($totalfinaldiacat, 2, ',', '.');
    $saldo = number_format($saldo, 2, ',', '.');
    //FIM DO SQL
    echo " 

      <h5>Balanço Mensal </h5>

      <table class='table table-hover'>
        <tr>
          <td><b>Faturamento</b></td>
          <td><b>Depesas</b></td>
          <td><b>Saldo</b></td>
        </tr>
        <tr>
            <td>R$ $valorTotalFinal</td>
          <td>R$ $totalfinaldiacat</td>
          <td>R$ $saldo</td>
        </tr>
      </table>
      ";
    //SQL PARA MONTAR LISTA DE ENTRADAS DO DIA


    $sqlNota = "SELECT * FROM entradas WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod DESC";
    $paramNota = array(
      ":dia" => $dia,
      ":mes" => $mes,
      ":ano" => $ano,
    );

    $textostatus = "";
    $valorTotalFinal = 0;
    $nomefunc = "";
    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $valorTotalFinal = 0;


      $codnota = $resultadonota['cod'];
      $cod_funcionario = $resultadonota['cod_funcionario'];
      $status = $resultadonota['status'];

      if ($status = 1) {
        $textostatus = "Aberto";
      } else {
        $textostatus = "Finalizado";
      }


      $sqlFunc = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramFunc = array(
        ":cod" => $cod_funcionario
      );

      $dataTableFunc = $banco->ExecuteQuery($sqlFunc, $paramFunc);
      foreach ($dataTableFunc as $resultadofunc) {
        $nomefunc = $resultadofunc['nome'];
      }
      $sqlNotaPag = "SELECT * FROM lista_entradas WHERE cod_entrada = :cod ORDER BY cod DESC";
      $paramNotaPag = array(
        ":cod" => $codnota
      );

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $valorpag = (float) $resultadonotaPag['valor_total'];
        $valorTotalFinal = $valorTotalFinal + $valorpag;
      }
    }
    //FIM SQL
    $valorTotalFinal = number_format($valorTotalFinal, 2, ',', '.');



    echo " 
      <h5>Resumo de Entradas do Dia</h5>

      <table class='table table-hover'>
        <tr>
          <td><b>Funcionário</b></td>
          <td><b>Valor</b></td>
          <td><b>Status</b></td>
        </tr>
        <tr>
          <td>$nomefunc</td>
          <td>R$ $valorTotalFinal</td>
          <td>$textostatus</td>
        </tr>
      </table>

      <h5>Estoque Crítico</h5>
      ";
    $contador1 = 0;
    echo "
            <table class='table' style='font-size:12pt;'>
            <tr style='text-align:center;'>
                <td><b>Produto</b></td>
                <td><b>Est. Máx</b></td>
                <td><b>Est. min.</b></td>
                <td><b>Qtd</b></td>
                
            </tr>
            ";
    //$sql = mysqli_query($conn, "SELECT * FROM produtos ORDER BY cod ASC");

    $param1 = 0;
    if ($param1 == 0) {
      //   $sqlProdutos = mysqli_query($conn, "SELECT * FROM produtos WHERE descricao LIKE '%" . $descricao . "%' ORDER BY cod ASC");
      $sqlProdutos = "SELECT * FROM servicos WHERE tipo = 1 ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    } else {
      $sqlProdutos = "SELECT * FROM servicos WHERE categoria = $param1 AND tipo = 1 ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    }



    $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    foreach ($dataTableProdutos as $resultadoprodutos) {

      $nomeproduto = $resultadoprodutos['nome'];
      $qtd = $resultadoprodutos['qtd'];
      if ($qtd == null) {
        $qtd = 0;
      }
      if ($qtd < 0) {
        $qtd = 0;
      }
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

      if ($contador1 < 5) {
        $contador1++;
        if ($qtd < $est_min) {
          echo " <tr style='text-align:center;'>
                <td>$nomeproduto($nomecategoria)</td>
                <td>$est_max</td>
                <td>$est_min</td>
                <td>$qtd</td>
                
            </tr>
            ";
        }
      }
    }
    echo "</table>
        
        ";


    $mes = date('m');
    $ano = date('Y');


    $categoria = 0;

    echo "
            <h5>Produtos Mais Vendidos do Mês</h5>
            
            ";


    if ($categoria == 0) {
      $sqlServicos = "SELECT * FROM servicos ORDER BY nome ASC";
      $dataTableServicos = $banco->ExecuteQuery($sqlServicos);
    } else {
      $sqlServicos = "SELECT * FROM servicos WHERE categoria = :cod ORDER BY nome ASC";
      $paramServicos = array(
        ":cod" => $categoria
      );
      $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    }
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $nomeservico = $resultadoservicos['nome'];
      $valorservico = $resultadoservicos['valor'];
      $valorservico = number_format($valorservico, 2, ',', '.');
      $qtd_total = 0;
      $valor_total = 0;
      $sqlListaSaidas = "SELECT * FROM pedidos WHERE servico = :cod_produto AND mes = $mes AND ano = $ano AND status = 3 ORDER BY cod ASC";
      $paramListaSaidas = array(
        ":cod_produto" => $codservico
      );
      $dataTableListaSaidas = $banco->ExecuteQuery($sqlListaSaidas, $paramListaSaidas);
      foreach ($dataTableListaSaidas as $resultadolistasaidas) {
        $qtd = $resultadolistasaidas['qtd'];
        $valor = $resultadolistasaidas['valor'];
        $qtd_total = $qtd_total + $qtd;
        $valor_total = $valor_total + $valor;
      }
      $jogo[$codservico] = [
        ["$nomeservico", "$valorservico", $qtd_total, "$valor_total"],
      ];
      // print_r($jogo);
      // O array
      $arraynovo[$codservico] = array(
        array($valor_total, $codservico, $nomeservico, $valorservico, $qtd_total),
      );
    }

    arsort($jogo[3]);
    // var_dump($jogo);
    //  var_dump($arraynovo);

    echo "
        <table class='table'>
          <thead>
                    <tr>
                        <td><b>Nome Serviço/Produto</b></td>
                        <td><b>Valor Unt.</b></td>
                        <td><b>Qtd Total</b></td>
                        <td><b>Valor Total</b></td>
                    </tr>
            </thead>          ";
    arsort($arraynovo);
    $contador2 = 0;
    foreach ($arraynovo as $chave => $valor_total) {

      foreach ($arraynovo[$chave] as $resultadoservicos) {
        $valorservico = 0;
        $qtd_total = 0;
        $valor_total = 0;

        $valor_total = $resultadoservicos[0];
        $codservico = $resultadoservicos[1];
        $nomeservico = $resultadoservicos[2];
        $valorservico = (float) $resultadoservicos[3];
        $qtd_total = $resultadoservicos[4];
        if ($contador2 < 5) {
          $contador2++;
          echo "
                        <tr>
                        <td>$nomeservico</td>
                        <td>R$ " . number_format($valorservico, 2, ',', '.') . "</td>
                        <td>$qtd_total</td>
                        <td>R$" . number_format($valor_total, 2, ',', '.') . "</td>
                    </tr>
            
                        ";
        }
      }
    }


    echo "</table>";

    break;


  //VALIDACAO PARA PEDIDOS EM ABERTO
  case 16:
    $param = $_GET['param'];
    $valor = $_GET['valor'];


    echo "<table class='table table-hover' style='font-size:14pt; width:100%;'>
            <tr style='text-align:left;'>
                <td><b>Cod. Nota</b></td>
                <td><b>Cliente</b></td>
                <td><b>Valor Total</b></td>
                <td><b>Funcionário</b></td>
                <td><b>Data e Hora</b></td>
                <td><b>Tipo</b></td>
                <td></td>
            </tr>
            ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
// Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {

      $sqlNota = "SELECT * FROM notas WHERE status = :status ORDER BY cod DESC";
      $paramNota = array(
        ":status" => 1
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND func = $codfunc ORDER BY cod DESC";
      $paramNota = array(
        ":status" => 1
      );
    }

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
          <tr style='text-align:left;'>
                <td>$codnota</td>
                <td>$nomeCli</td>
                <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                <td>$nomefunc</td>
                <td>$datahora</td>
                <td>$textotipo</td>
                <td>
                        <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                              <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                              <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                              <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                            </svg>
                        </a>
                        <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                            <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                              <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                            </svg>
                        </a>
                
                </td>

                </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table>";
    break;

  //VALIDACAO PARA CARREGAR DETALHES DO PEDIDO 
  case 17:
    $codnota = $_GET['param'];

    $dinheiropag = 0;
    $pixpag = 0;
    $debitopag = 0;
    $creditopag = 0;
    $totalpag = 0;
    $subtotalpag = 0;
    $descontopag = 0;
    $gorjetapag = 0;
    $tipopagamento = null;



    //SQL PARA VALIDAR SE A VENDA JÁ FOI REALIZADO O PAGAMENTO
    $sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
    $paramTestePag = array(
      ":cod" => $codnota
    );
    $nomecategoria = "Avulso";
    $dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);


    foreach ($dataTableTestePag as $infopagamento) {

      $dinheiropag = (float) $infopagamento['dinheiro'];
      $pixpag = (float) $infopagamento['pix'];
      $debitopag = (float) $infopagamento['debito'];
      $creditopag = (float) $infopagamento['credito'];
      $totalpag = (float) $infopagamento['total'];
      $subtotalpag = (float) $infopagamento['subtotal'];
      $descontopag = (float) $infopagamento['desconto'];
      $gorjetapag = (float) $infopagamento['gorjeta'];


      $tipopagamento = $infopagamento['tipo'];
      $numparcelas = (int) $infopagamento['numparcelas'];

      if ($tipopagamento == 2 && $numparcelas != 0) {
        $valorparcela = $totalpag / $numparcelas;
      }

    }

    if ($dataTableTestePag == null) {
      echo "<div class='alert alert-danger'>Pagamento não realizado</div>";
    }


    if ($tipopagamento == 2) {
      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Crédiário Parcelado em <b>$numparcelas x</b>
                      <span id=''><b>R$ " . number_format($valorparcela, 2, ',', '.') . "</b></span>
                    </li>
                    ";
    }

    if ($dinheiropag != 0) {
      $dinheiropag = number_format($dinheiropag, 2, ',', '.');

      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Dinheiro
                      <span id=''><b>R$ $dinheiropag</b></span>
                    </li>";
    }
    if ($pixpag != 0) {
      $pixpag = number_format($pixpag, 2, ',', '.');

      echo "  <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Pix
                      <span><b>R$ $pixpag</b></span>
                    </li>
                  ";
    }

    if ($debitopag != 0) {
      $debitopag = number_format($debitopag, 2, ',', '.');

      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Cartao de Débito
                      <span><b>R$ $debitopag</b></span>
                    </li>
                  ";
    }
    if ($creditopag != 0) {
      $creditopag = number_format($creditopag, 2, ',', '.');
      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Cartão de Crédito
                      <span><b>R$ $creditopag</b></span>
                    </li>";
    }
    if ($subtotalpag != 0) {
      $subtotalpag = number_format($subtotalpag, 2, ',', '.');

      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Subtotal
                      <span><b>R$ $subtotalpag</b></span>
                    </li>
                  ";
    }
    if ($descontopag != 0) {
      $descontopag = number_format($descontopag, 2, ',', '.');

      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center px-0'>
                      Desconto
                      <span><b>R$ $descontopag</b></span>
                    </li>
                  ";
    }

    if ($totalpag != 0) {
      $totalpag = number_format($totalpag, 2, ',', '.');

      echo " 
                    <li class='list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3'>
                      <div>
                        <strong>Total Final</strong>
                      </div>
                      <span><strong><b>R$ $totalpag</b></strong></span>
                    </li>
                  ";
    }

    if ($gorjetapag != 0) {
      $gorjetapag = number_format($gorjetapag, 2, ',', '.');


      echo "<li class='list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3'>
                      <div>
                        <strong>Troco</strong>
                      </div>
                      <span><strong>R$ $gorjetapag</strong></span>
                    </li>
                  ";
    }
    echo "</ul>
              </div>
            </div>";


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
                  <td style=''><b>Obs</b></td>
                </tr>
                ";
    //$sql = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod ASC");
    $sqlPedidos2 = "SELECT * FROM pedidos WHERE usuario = :cod ORDER BY cod DESC";
    $paramPedidos2 = array(
      ":cod" => $codnota
    );

    $dataTablePedidos2 = $banco->ExecuteQuery($sqlPedidos2, $paramPedidos2);
    foreach ($dataTablePedidos2 as $resultadopedidos2) {

      $codpedido = $resultadopedidos2['cod'];
      $codservico = $resultadopedidos2['servico'];

      if ($codservico == 0) {
        $nomeservico = $resultadopedidos2['obs'] . "<b><small>(Avulso)</small>" . "<b>";
        $obs = "";
      } else {
        $categoria = $resultadopedidos2['categoria'];
        //$sql3 = mysqli_query($conn, "SELECT * FROM categoriaserfin WHERE cod=$categoria LIMIT 1");
        $sqlCategorias = "SELECT * FROM categoriaserfin WHERE cod=  :cod LIMIT 1";
        $paramCategorias = array(
          ":cod" => $categoria
        );
        $nomecategoria = "Avulso";
        $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
        foreach ($dataTableCategorias as $resultadocategorias) {
          $nomecategoria = $resultadocategorias['nome'];
        }
        //$sql2 = mysqli_query($conn, "SELECT * FROM servicos WHERE cod=" . $codservico . " LIMIT 1");
        $sqlServicos = "SELECT * FROM servicos WHERE cod= :cod LIMIT 1";
        $paramServicos = array(
          ":cod" => $codservico
        );

        $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
        foreach ($dataTableServicos as $resultadoservicos) {
          $nomeservico = $resultadoservicos['nome'] . "<b><small>(" . $nomecategoria . ")</small>" . "<b>";
          $obs = $resultadopedidos2['obs'];
        }
      }
      $valor = (float) $resultadopedidos2['valor'];
      $qtd = (float) $resultadopedidos2['qtd'];
      $valorunt = $valor / $qtd;
      $total = $total + $valor;
      $codpedido = $resultadopedidos2['cod'];
      $nomecategoria = "";
      $valorunt = number_format($valorunt, 2, ',', '.');
      $valor = number_format($valor, 2, ',', '.');

      echo "
                  
                    <tr>
                      <td>" . $nomeservico . "</td>
                      <td>$qtd</td>
                      <td>R$ " . $valorunt . "</td>
                      <td>R$ " . $valor . "</td>
                      <td>$obs</td>
                        
                    </tr>
                 ";
      $contador++;
    }
    $total = number_format($total, 2, ',', '.');
    echo "
                <tr>
                  <td colspan='3' style='text-align:right;'>Total:</td>
                  <td colspan='3'><b>R$ " . $total . "</b></td>
                  
                </tr>
                ";
    echo "</table>";


    break;

  //VALIDACAO PARA PEDIDOS PARA ENTREGA COM PAGAMENTO REALIZADO E PENDENTE
  case 18:
    $param = $_GET['param'];
    $valor = $_GET['valor'];

    $ano = date("Y");
    $mes = date('m');
    $dia = date('d');
    $status = 3;



    echo "
          <div class='row' style='width:100%;'>
          <input value='" . date("Y-m-d") . "' style='width:48%; margin:10px;' onkeyup='validacao(19, this.value, txtPesquisaAtivoEntrega.value, 19)' class='form-control form-control-lg' id='DataParaPequisaAtivo' name='DataParaPequisaAtivo' type='date' />
          <select style='width:48%; margin:10px;' onchange='validacao(19, DataParaPequisaAtivo.value, this.value, 19)' name='txtPesquisaAtivoEntrega' id='txtPesquisaAtivoEntrega'>
              <option value='3' selected>Pagamento Realizado</option>
              <option value='1' >Pagamento Pendente</option>

          </select>    
          </div> 
    
    ";

    echo "<div id='ResultadoValidacao19'>
    <table class='table table-hover' style='font-size:14pt; width:100%;'>
            <tr style='text-align:left;'>
                <td><b>Cod. Nota</b></td>
                <td><b>Cliente</b></td>
                <td><b>Valor Total</b></td>
                <td><b>Funcionário</b></td>
                <td><b>Data e Hora</b></td>
                <td><b>Tipo</b></td>
                <td></td>
            </tr>
            ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
// Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {

      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 3 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 3 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
          <tr style='text-align:left;'>
                <td>$codnota</td>
                <td>$nomeCli</td>
                <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                <td>$nomefunc</td>
                <td>$datahora</td>
                <td>$textotipo</td>
                <td>
                        <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                              <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                              <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                              <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                            </svg>
                        </a>
                        <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                            <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                              <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                            </svg>
                        </a>
                
                </td>

                </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table></div>";
    break;

  //VALIDACAO PARA PEDIDOS PARA ENTREGA COM PAGAMENTO REALIZADO E PENDENTE - PESQUISA
  case 19:
    $param = $_GET['param'];
    $status = $_GET['valor'];

    $t = explode("-", $param);
    $ano = $t[0];
    $mes = $t[1];
    $dia = $t[2];
    echo "
      <table class='table table-hover' style='font-size:14pt; width:100%;'>
              <tr style='text-align:left;'>
                  <td><b>Cod. Nota</b></td>
                  <td><b>Cliente</b></td>
                  <td><b>Valor Total</b></td>
                  <td><b>Funcionário</b></td>
                  <td><b>Data e Hora</b></td>
                  <td><b>Tipo</b></td>
                  <td></td>
              </tr>
              ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {

      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 3 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 3 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }
    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
            <tr style='text-align:left;'>
                  <td>$codnota</td>
                  <td>$nomeCli</td>
                  <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                  <td>$nomefunc</td>
                  <td>$datahora</td>
                  <td>$textotipo</td>
                  <td>
                          <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                                <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                                <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                                <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                              </svg>
                          </a>
                          <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                              <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                                <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                              </svg>
                          </a>
                  
                  </td>
  
                  </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table>";
    break;

  //VALIDACAO PARA PEDIDOS NO BALCÃO COM PAGAMENTO REALIZADO E PENDENTE
  case 20:
    $param = $_GET['param'];
    $valor = $_GET['valor'];

    $ano = date("Y");
    $mes = date('m');
    $dia = date('d');
    $status = 3;



    echo "
        <div class='row' style='width:100%;'>
        <input value='" . date("Y-m-d") . "' style='width:48%; margin:10px;' onkeyup='validacao(21, this.value, txtPesquisaAtivoEntrega.value, 19)' class='form-control form-control-lg' id='DataParaPequisaAtivo' name='DataParaPequisaAtivo' type='date' />
        <select style='width:48%; margin:10px;' onchange='validacao(21, DataParaPequisaAtivo.value, this.value, 19)' name='txtPesquisaAtivoEntrega' id='txtPesquisaAtivoEntrega'>
            <option value='3' selected>Pagamento Realizado</option>
            <option value='1' >Pagamento Pendente</option>

        </select>    
        </div> 

    ";

    echo "<div id='ResultadoValidacao19'>
    <table class='table table-hover' style='font-size:14pt; width:100%;'>
          <tr style='text-align:left;'>
              <td><b>Cod. Nota</b></td>
              <td><b>Cliente</b></td>
              <td><b>Valor Total</b></td>
              <td><b>Funcionário</b></td>
              <td><b>Data e Hora</b></td>
              <td><b>Tipo</b></td>
              <td></td>
          </tr>
          ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 1 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 1 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
        <tr style='text-align:left;'>
              <td>$codnota</td>
              <td>$nomeCli</td>
              <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
              <td>$nomefunc</td>
              <td>$datahora</td>
              <td>$textotipo</td>
              <td>
                      <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                            <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                            <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                            <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                          </svg>
                      </a>
                      <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                          <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                            <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                          </svg>
                      </a>
              
              </td>

              </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table></div>";
    break;

  //VALIDACAO PARA PEDIDOS PARA ENTREGA COM PAGAMENTO REALIZADO E PENDENTE - PESQUISA
  case 21:
    $param = $_GET['param'];
    $status = $_GET['valor'];

    $t = explode("-", $param);
    $ano = $t[0];
    $mes = $t[1];
    $dia = $t[2];
    echo "
      <table class='table table-hover' style='font-size:14pt; width:100%;'>
              <tr style='text-align:left;'>
                  <td><b>Cod. Nota</b></td>
                  <td><b>Cliente</b></td>
                  <td><b>Valor Total</b></td>
                  <td><b>Funcionário</b></td>
                  <td><b>Data e Hora</b></td>
                  <td><b>Tipo</b></td>
                  <td></td>
              </tr>
              ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {

      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 1 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 1 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }
    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
            <tr style='text-align:left;'>
                  <td>$codnota</td>
                  <td>$nomeCli</td>
                  <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                  <td>$nomefunc</td>
                  <td>$datahora</td>
                  <td>$textotipo</td>
                  <td>
                          <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                                <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                                <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                                <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                              </svg>
                          </a>
                          <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                              <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                                <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                              </svg>
                          </a>
                  
                  </td>
  
                  </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table>";
    break;


  //VALIDACAO PARA PEDIDOS NO BALCÃO COM PAGAMENTO REALIZADO E PENDENTE
  case 22:
    $param = $_GET['param'];
    $valor = $_GET['valor'];

    $ano = date("Y");
    $mes = date('m');
    $dia = date('d');
    $status = 3;



    echo "
      <div class='row' style='width:100%;'>
      <input value='" . date("Y-m-d") . "' style='width:48%; margin:10px;' onkeyup='validacao(23, this.value, txtPesquisaAtivoEntrega.value, 19)' class='form-control form-control-lg' id='DataParaPequisaAtivo' name='DataParaPequisaAtivo' type='date' />
      <select style='width:48%; margin:10px;' onchange='validacao(23, DataParaPequisaAtivo.value, this.value, 19)' name='txtPesquisaAtivoEntrega' id='txtPesquisaAtivoEntrega'>
          <option value='3' selected>Pagamento Realizado</option>
          <option value='1' >Pagamento Pendente</option>

      </select>    
      </div> 

  ";

    echo "<div id='ResultadoValidacao19'>
  <table class='table table-hover' style='font-size:14pt; width:100%;'>
        <tr style='text-align:left;'>
            <td><b>Cod. Nota</b></td>
            <td><b>Cliente</b></td>
            <td><b>Valor Total</b></td>
            <td><b>Funcionário</b></td>
            <td><b>Data e Hora</b></td>
            <td><b>Tipo</b></td>
            <td></td>
        </tr>
        ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 2 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 2 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
      <tr style='text-align:left;'>
            <td>$codnota</td>
            <td>$nomeCli</td>
            <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
            <td>$nomefunc</td>
            <td>$datahora</td>
            <td>$textotipo</td>
            <td>
                    <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                          <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                          <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                          <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                        </svg>
                    </a>
                    <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                        <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                          <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                        </svg>
                    </a>
            
            </td>

            </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table></div>";
    break;

  //VALIDACAO PARA PEDIDOS RETIRADA NO BALCÃO COM PAGAMENTO REALIZADO E PENDENTE - PESQUISA
  case 23:
    $param = $_GET['param'];
    $status = $_GET['valor'];

    $t = explode("-", $param);
    $ano = $t[0];
    $mes = $t[1];
    $dia = $t[2];
    echo "
    <table class='table table-hover' style='font-size:14pt; width:100%;'>
            <tr style='text-align:left;'>
                <td><b>Cod. Nota</b></td>
                <td><b>Cliente</b></td>
                <td><b>Valor Total</b></td>
                <td><b>Funcionário</b></td>
                <td><b>Data e Hora</b></td>
                <td><b>Tipo</b></td>
                <td></td>
            </tr>
            ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
// Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {

      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 2 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 2 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }
    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
          <tr style='text-align:left;'>
                <td>$codnota</td>
                <td>$nomeCli</td>
                <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                <td>$nomefunc</td>
                <td>$datahora</td>
                <td>$textotipo</td>
                <td>
                        <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                              <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                              <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                              <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                            </svg>
                        </a>
                        <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                            <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                              <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                            </svg>
                        </a>
                
                </td>

                </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table>";
    break;

  //VALIDACAO PARA PEDIDOS PARA BALCÃO COM PAGAMENTO REALIZADO E PENDENTE
  case 22:
    $param = $_GET['param'];
    $valor = $_GET['valor'];

    $ano = date("Y");
    $mes = date('m');
    $dia = date('d');
    $status = 3;



    echo "
      <div class='row' style='width:100%;'>
      <input value='" . date("Y-m-d") . "' style='width:48%; margin:10px;' onkeyup='validacao(23, this.value, txtPesquisaAtivoEntrega.value, 19)' class='form-control form-control-lg' id='DataParaPequisaAtivo' name='DataParaPequisaAtivo' type='date' />
      <select style='width:48%; margin:10px;' onchange='validacao(23, DataParaPequisaAtivo.value, this.value, 19)' name='txtPesquisaAtivoEntrega' id='txtPesquisaAtivoEntrega'>
          <option value='3' selected>Pagamento Realizado</option>
          <option value='1' >Pagamento Pendente</option>

      </select>    
      </div> 

  ";

    echo "<div id='ResultadoValidacao19'>
  <table class='table table-hover' style='font-size:14pt; width:100%;'>
        <tr style='text-align:left;'>
            <td><b>Cod. Nota</b></td>
            <td><b>Cliente</b></td>
            <td><b>Valor Total</b></td>
            <td><b>Funcionário</b></td>
            <td><b>Data e Hora</b></td>
            <td><b>Tipo</b></td>
            <td></td>
        </tr>
        ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND tipo_pedido = 2 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas WHERE status = :status AND dia = :dia AND mes = :mes AND ano = :ano AND func = $codfunc AND tipo_pedido = 2 ORDER BY cod DESC";
      $paramNota = array(
        ":status" => $status,
        ":dia" => $dia,
        ":mes" => $mes,
        ":ano" => $ano,
      );
    }

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
      <tr style='text-align:left;'>
            <td>$codnota</td>
            <td>$nomeCli</td>
            <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
            <td>$nomefunc</td>
            <td>$datahora</td>
            <td>$textotipo</td>
            <td>
                    <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                          <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                          <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                          <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                        </svg>
                    </a>
                    <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                        <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                          <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                        </svg>
                    </a>
            
            </td>

            </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table></div>";
    break;

  //VALIDACAO PARA PESQUISAR POR FILTRO
  case 24:
    echo "FUNCAO PARA FILTRO DE PESQUISA";

    break;

  //VALIDACAO PARA PESQUISAR ULTIMOS 20 PEDIDOS
  case 25:

    $param = $_GET['param'];
    $valor = $_GET['valor'];


    echo "<table class='table table-hover' style='font-size:14pt; width:100%;'>
              <tr style='text-align:left;'>
                  <td><b>Cod. Nota</b></td>
                  <td><b>Cliente</b></td>
                  <td><b>Valor Total</b></td>
                  <td><b>Funcionário</b></td>
                  <td><b>Data e Hora</b></td>
                  <td><b>Tipo</b></td>
                  <td></td>
              </tr>
              ";

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    //$sql = mysqli_query($conn, "SELECT * FROM notas WHERE status = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    if ($_SESSION['permissaoF'] == 1) {

      $sqlNota = "SELECT * FROM notas ORDER BY cod DESC LIMIT 20";

    } else {
      $codfunc = $_SESSION['codF'];
      $sqlNota = "SELECT * FROM notas ORDER BY cod DESC LIMIT 20";

    }

    $qtdTotalFinal = 0;
    $valorTotalFinal = 0;
    $nomecli = "";


    $dataTableNota = $banco->ExecuteQuery($sqlNota);
    foreach ($dataTableNota as $resultadonota) {
      $codnota = $resultadonota['cod'];
      $codcli = $resultadonota['usuario'];

      $dia = $resultadonota['dia'];
      $mes = $resultadonota['mes'];
      $ano = $resultadonota['ano'];
      $hora = $resultadonota['hora'];

      $func = $resultadonota['func'];
      $tipo = $resultadonota['tipo_pedido'];


      if ($codcli != 0) {
        $nomeCli = "";
        $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
        $paramCli = array(
          ":id" => $codcli
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomecli = $resultadocli['nome'];
        }
      } else {
        $nomeCli = $resultadonota['nomeCli'];
      }
      $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramCli = array(
        ":cod" => $func
      );
      $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
      foreach ($dataTableCli as $resultadocli) {
        $nomefunc = $resultadocli['nome'];
      }
      $textotipo = "";
      if ($tipo == 1) {
        $textotipo = "Venda Expressa";
      } else if ($tipo == 2) {
        $textotipo = "Retirada no Balcão";
      } else if ($tipo == 3) {
        $textotipo = "Entrega";
      }

      $validacaopagamento = 0;

      $sqlPagCli = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE cod_orcamento = $codnota ORDER BY cod ASC LIMIT 1");
      while ($pag = mysqli_fetch_object($sqlPagCli)) {
        $valorTotalFinal = (float) $pag->total;
        $validacaopagamento = 1;
      }

      if ($validacaopagamento == 0) {
        $sqlPedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = $codnota ORDER BY cod ASC");
        while ($pedidos = mysqli_fetch_object($sqlPedidos)) {
          $qtdTotalFinal = $qtdTotalFinal + (int) $pedidos->qtd;
          $valorTotalFinal = $valorTotalFinal + (float) $pedidos->valor;
        }
      }

      $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;



      echo "
            <tr style='text-align:left;'>
                  <td>$codnota</td>
                  <td>$nomeCli</td>
                  <td>" . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                  <td>$nomefunc</td>
                  <td>$datahora</td>
                  <td>$textotipo</td>
                  <td>
                          <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                                <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                                <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                                <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                              </svg>
                          </a>
                          <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                              <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                                <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                              </svg>
                          </a>
                  
                  </td>
  
                  </tr>";
      $codnota = 0;
      $nomeCli = "";
      $valorTotalFinal = 0;
      $nomefunc = 0;
      $datahora = 0;
      $textotipo = 0;
    }

    echo "</table>";


    break;

  //FUNCAO PARA PESQUISAR PRODUTOS EM HOME
  case 26:

    $param = $_GET['param'];
    $categoria = $_GET['param'];
    $valor = $_GET['valor'];

    $sqlServicoscont = "SELECT count(*) as total from servicos WHERE nome LIKE :nome";
    $paramServicoscount = array(
      ":nome" => "%{$valor}%"
    );

    $dataTableServicos222 = $banco->ExecuteQuery($sqlServicoscont, $paramServicoscount);
    foreach ($dataTableServicos222 as $resultado22) {
      $totalderegistros = $resultado22['total'];
    }


    if ($param == 0) {
      echo " <table class='table table-striped table-sm'>    <thead>
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
      //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
      // Exibe todos os valores encontrados

      $sqlServicos2 = "SELECT * FROM servicos WHERE  codbarra = :nome AND codbarra != '' ORDER BY cod ASC LIMIT 1";
      $paramServicos2 = array(
        ":nome" => $valor
      );

      $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);

      if ($dataTableServicos2 == null) {

        $sqlServicos2 = "SELECT * FROM servicos WHERE codbusca = :nome AND codbusca != 0 ORDER BY cod ASC LIMIT 1";
        $paramServicos2 = array(
          ":nome" => $valor
        );

        $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);
      }
      if ($dataTableServicos2 == null) {


        //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
        $sqlServicos = "SELECT * FROM servicos WHERE nome LIKE :nome ORDER BY cod ASC";
        $paramServicos = array(
          ":nome" => "%{$valor}%"
        );
        $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);
      }
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

        echo "
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
          echo "
                                               <small style='color:red;'> Sem Estoque
                                                </small>";
        } else {
          echo "
                                        <b>Qtd:</b>$qtdservico</br>
                                      
                                          
                                       ";
        }
        echo "  </td> <td>
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
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(29, 1, $codservico)' href='javascript: func'>Informações Estoque</a></li>
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


      echo "</table>";
    } else {

      echo " <table class='table table-striped table-sm'>
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
      //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
      // Exibe todos os valores encontrados

      $sqlServicos2 = "SELECT * FROM servicos WHERE  codbarra = :nome AND codbarra != '' ORDER BY cod ASC LIMIT 1";
      $paramServicos2 = array(
        ":nome" => $valor
      );

      $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);

      if ($dataTableServicos2 == null) {

        $sqlServicos2 = "SELECT * FROM servicos WHERE codbusca = :nome AND codbusca != 0 ORDER BY cod ASC LIMIT 1";
        $paramServicos2 = array(
          ":nome" => $valor
        );

        $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);
      }
      if ($dataTableServicos2 == null) {


        //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
        $sqlServicos = "SELECT * FROM servicos WHERE nome LIKE :nome AND categoria = $categoria ORDER BY cod ASC";
        $paramServicos = array(
          ":nome" => "%{$valor}%"
        );
        $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);
      }
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

        echo "
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
          echo "
                                               <small style='color:red;'> Sem Estoque
                                                </small>";
        } else {
          echo "
                                        <b>Qtd:</b>$qtdservico</br>
                                      
                                          
                                       ";
        }
        echo "  </td> <td>
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
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(29, 1, $codservico)' href='javascript: func'>Informações Estoque</a></li>
                                              <li><hr class='dropdown-divider'></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(30, 1, $codservico)' href='javascript: func'>Atualizar Imagem do Produto</a></li>
                                             <li><hr class='dropdown-divider'></li>
                                               <li>$textoteste</li>
                                            </ul>
                                          </div>
                                        </td>
				</tr>				";
      }
      echo "</table>";
    }

    break;

  //VALIDACAO PARA EDITAR INFORMAÇÕES DO PRODUTO/SERVIÇO
  case 27:
    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM servicos WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $tiposervico = $resultadoservicos['tipo'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];
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
    }
    $contadorindex = 4;
    echo "
			<h2 style='padding:5px; color:#000; border-bottom: 2px solid #337AB7; width: 100%; '><span class='glyphicon glyphicon-retweet'></span> Editar Informações do Produto ou Serviço</h2>
                            
				<form method='post' name='frmCadastro' id='frmCadastro' novalidate enctype='multipart/form-data'>
                    <div class='row'>
                    <div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px;  border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtNomeProduto' class='control-label'>Nome Produto</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtNomeProdutoEd' name='txtNomeProdutoEd' value='$nomeservico' autofocus>
                        </div>
                    </div>  
				          	<div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtDescricaoProduto' class='control-label'>Descrição</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtDescricaoProdutoEd' name='txtDescricaoProdutoEd' value='$descricaoservico'  >
                        </div>
                    </div>  
                    <div class='col-12 col-md-6' style='text-align: left;' id='ResultadoValidacao55'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' for='txtValor' class='control-label'>Valor Unt.</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtValorEd' name='txtValorEd' value='$valorunt' onchange='validacao(32, 1, this.value, 55)'>
                        </div>
                    </div>
      ";


    echo "     </br>         <div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>  
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' class='control-label'>Categoria</label>
                            <select tabindex='$contadorindex' style='height:92px; font-size: 15pt; width: 100%; ' type='text' id='txtCategoriaEd' name='txtCategoriaEd' class='form-control' value='' >
								";
    // $sqlCategorias = mysqli_query($conn, "SELECT * FROM categoriaserfin ORDER BY cod ASC");
    $sqlCat = "SELECT * FROM categoriaserfin ORDER BY cod ASC";

    $dataTableCat = $banco->ExecuteQuery($sqlCat);
    foreach ($dataTableCat as $resultadocat) {
      $nomecategoria = $resultadocat['nome'];
      $codcategoria = $resultadocat['cod'];

      if ($codcategoria == $categoriaservico) {
        $textoselect = "selected='selected'";
      } else {
        $textoselect = "";
      }
      echo "<option value='$codcategoria' $textoselect>$nomecategoria</option>
											";
    }



    echo "			
									
							</select>
                        </div>
                    </div>
                    
                               <input type='hidden' class='form-control' id='txtTipoServ' name='txtTipoServ' value='$est_maxservico' />
                        
                        <div id='ResultadoValidacao1213'>
                            
                        ";
    if ($tiposervico == 1) {
      echo "
                <input  type='hidden'  id='txtEst_maxEd' name='txtEst_maxEd' value='$est_maxservico' >
                           <input type='hidden'  id='txtEst_mimEd' name='txtEst_mimEd' value='$est_mimservico' >
                             <input type='hidden' id='txtCod_BarraEd' name='txtCod_BarraEd' value='$cod_barraservico' >
                       
                    
                ";
    } else {
      echo "
                            <input  type='hidden' class='form-control' id='txtEst_maxEd' name='txtEst_maxEd' value='0' >
                            <input  type='hidden' class='form-control' id='txtEst_mimEd' name='txtEst_mimEd' value='0' >
                            <input  type='hidden' class='form-control' id='txtCod_BarraEd' name='txtCod_BarraEd' value='0' >
                    
                ";
    }
    $contadorindex++;
    echo " 

                                
                        </div>

					<div class='col-12 col-md-12' style='text-align: center;'>
                    <a tabindex='$contadorindex' style='margin-top:10px; margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' onclick='EditarInformacoes(31, $codservico, txtNomeProdutoEd.value, txtDescricaoProdutoEd.value, txtValorEd.value, txtCategoriaEd.value, txtEst_maxEd.value, txtEst_mimEd.value, txtCod_BarraEd.value)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
                    </div>
					<ul id='ValidacaoProdutosEditar' style='list-style: none; margin-left: 25px;'>
                       
                    </ul>";

    $tiposervico = $resultadoservicos['tipo'];
    $est_maxservico = $resultadoservicos['est_max'];
    $est_mimservico = $resultadoservicos['est_mim'];
    $cod_barraservico = $resultadoservicos['codbarra'];

    echo " </div>
                </form>
				

						       
	";


    break;

  //VALIDACAO PARA EDITAR INFORMAÇÕES DE BUSCA DO PRODUTO/SERVIÇO
  case 28:
    $codservico = (float) $_GET['valor'];
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
    }
    $contadorindex = 4;
    echo "
  <h2 style='padding:5px; color:#000; border-bottom: 2px solid #337AB7; width: 100%; '><span class='glyphicon glyphicon-retweet'></span> Editar Informações de Busca do Produto</h2>
                        
    <form method='post' name='frmCadastro' id='frmCadastro' novalidate enctype='multipart/form-data'>
                        <input type='hidden' id='txtNomeProdutoEd' name='txtNomeProdutoEd' value='$nomeservico' autofocus>
                        <input type='hidden' id='txtDescricaoProdutoEd' name='txtDescricaoProdutoEd' value='$descricaoservico'  >
                       <input type='hidden' id='txtValorEd' name='txtValorEd' value='$valorunt' onchange='Validacao(7, 1, this.value, 55)'>
                       <input type='hidden' id='txtCategoriaEd' name='txtCategoriaEd' value='$catservico' onchange='Validacao(7, 1, this.value, 55)'>
                           
 

            ";






    echo "   <div class='col-12 col-md-12' style='text-align: left;'>
                    <div class='form-group label-floating'>
                        <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtNomeProduto' class='control-label'>Cod. Barra</label>
                        <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtCod_BarraEd' name='txtCod_BarraEd' value='$cod_barraservico' autofocus>
                    </div>
                </div>          
                   <div class='col-12 col-md-12' style='text-align: left;'>
                    <div class='form-group label-floating' id='ResultadoValidacao1111'>
                    ";
    if ($cod_buscaservico != null) {
      echo "    <label style='padding:5px; border-bottom: 2px solid; width: 100%;' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtNomeProduto' class='control-label'>Cod. Busca</label>
                        <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' disabled class='form-control' id='txtCod_BuscaEd2' name='txtCod_BuscaEd2' value='$cod_buscaservico' autofocus>
                        <input type='hidden' class='form-control' id='txtCod_BuscaEd' name='txtCod_BuscaEd' value='$cod_buscaservico' />
                   ";
    } else {
      echo "
                        <h2 style='color:red;'>Esse item não possui codigo. Deseja gerar um codigo para esse produto? Clique no botão 'Gerar Codigo'.</h2>
                        <a tabindex='$contadorindex' style='margin-top:10px; padding:27px; font-size: 15pt; width: 91%; margin-left:7%; margin-bottom:10px;' onClick='validacao(33, $codservico, 0, 1111)' href='javascript:void' class='btn btn-warning btn-lg btn-block'><span class='glyphicon glyphicon-floppy-saved'></span>Gerar Codigo</a>
                ";
    }

    echo "</div>
                </div>          
                           <input type='hidden' class='form-control' id='txtTipoServ' name='txtTipoServ' value='$est_maxservico' />
                         
                    <div id='ResultadoValidacao1213'>
                        
                    ";
    if ($tiposervico == 1) {
      echo "
            <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtEst_maxEd' name='txtEst_maxEd' value='$est_maxservico' >
                       <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtEst_mimEd' name='txtEst_mimEd' value='$est_mimservico' >
               
               
                
            ";
    } else {
      echo "
                        <input  type='hidden' class='form-control' id='txtEst_maxEd' name='txtEst_maxEd' value='0' >
                        <input  type='hidden' class='form-control' id='txtEst_mimEd' name='txtEst_mimEd' value='0' >
            ";
    }
    echo " 

                            
                    </div>

      <div class='col-12 col-md-12' style='text-align: center;'>
                <a tabindex='$contadorindex' style='margin-left: 5%; padding:20px; font-size: 15pt; width: 90%; margin-top:10px;' onclick='EditarInformacoes(31, $codservico, txtNomeProdutoEd.value, txtDescricaoProdutoEd.value, txtValorEd.value, txtCategoriaEd.value, txtEst_maxEd.value, txtEst_mimEd.value, txtCod_BarraEd.value)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
                </div>
      <ul id='ValidacaoProdutosEditar' style='list-style: none; margin-left: 25px;'>
                   
                </ul>";

    $tiposervico = $resultadoservicos['tipo'];
    $est_maxservico = $resultadoservicos['est_max'];
    $est_mimservico = $resultadoservicos['est_mim'];
    $cod_barraservico = $resultadoservicos['codbarra'];

    echo " 
            </form>
    

               
";

    break;
  //VALIDACAO PARA EDITAR INFORMAÇÕES DE ESTOQUE DO PRODUTO/SERVIÇO
  case 29:

    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM servicos WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $tiposervico = $resultadoservicos['tipo'];
      $qtdservico = $resultadoservicos['qtd'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];
      $cod_buscaservico = $resultadoservicos['codbusca'];
      $categoriaservico = (float) $resultadoservicos['categoria'];
      $codcategoria = (float) $resultadoservicos['categoria'];
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
    }
    $contadorindex = 4;

    if ($tiposervico == 0) {
      echo "
			<h2 style='padding:5px; color:#000; border-bottom: 2px solid #337AB7; width: 100%; '><span class='glyphicon glyphicon-retweet'></span> Editar Informações de Busca do Produto</h2>
                            
				<form method='post' name='frmCadastro' id='frmCadastro' novalidate enctype='multipart/form-data'>
                            <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtNomeProdutoEd' name='txtNomeProdutoEd' value='$nomeservico' autofocus>
                               <input type='hidden' class='form-control' id='txtTipoServ' name='txtTipoServ' value='$est_maxservico' />
                      
                            <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtDescricaoProdutoEd' name='txtDescricaoProdutoEd' value='$descricaoservico'  >
                           <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtValorEd' name='txtValorEd' value='$valorunt' onchange='Validacao(7, 1, this.value, 55)'>
                           <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtCategoriaEd' name='txtCategoriaEd' value='$codcategoria' onchange='Validacao(7, 1, this.value, 55)'>
                  <div class='col-12 col-md-12' style='text-align: left;'>
                            <div class='form-group label-floating'>  
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' class='control-label'>Tipo</label>
                                <select tabindex='$contadorindex' onchange='validacao(35, this.value, $codservico, 1212)' style='height:62px; font-size: 15pt; width: 100%; ' type='text' id='txtTipoServico' name='txtTipoServico' class='form-control'>
                                    <option  value='0'>SERVIÇO</option>
                                    <option  value='1'>PRODUTO</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class='row' id='ResultadoValidacao1212'>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtEstMax' name='txtEstMax' value='$est_maxservico' autofocus>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtEstMin' name='txtEstMin' value='$est_mimservico' >
                                <input type='hidden' id='txtFornecedor' name='txtFornecedor' value='1' >
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtCodBarra' name='txtCodBarra' value='$qtdservico'>
                       
                        </div>
                          
      ";



      $contadorindex++;


      echo "          
                   			<div class='col-12 col-md-12' style='text-align: center;'>
                    <a tabindex='$contadorindex' style='margin-left: 2%; padding:20px; font-size: 15pt; width: 95%; margin-top:10px;' onclick='EditarInformacoes(34, $codservico, txtEstMax.value, txtEstMin.value, txtCodBarra.value, txtTipoServico.value, 0, 0, 0)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
                    </div>
					<ul id='ValidacaoProdutosEditar' style='list-style: none; margin-left: 25px;'>
                       
                    </ul>";

      $tiposervico = $resultadoservicos['tipo'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];

      echo " 
                </form>
				

						       
	";
    } else {
      echo "
			<h5 style='padding:5px; color:#000; border-bottom: 2px solid #337AB7; width: 100%; '><span class='glyphicon glyphicon-retweet'></span> Editar Informações de Busca do Produto</h5>
                            
				<form method='post' name='frmCadastro' id='frmCadastro' novalidate enctype='multipart/form-data'>
        <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtNomeProdutoEd' name='txtNomeProdutoEd' value='$nomeservico' autofocus>
                               <input type='hidden' class='form-control' id='txtTipoServ' name='txtTipoServ' value='$est_maxservico' />
                      
                            <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtDescricaoProdutoEd' name='txtDescricaoProdutoEd' value='$descricaoservico'  >
                           <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtValorEd' name='txtValorEd' value='$valorunt' onchange='Validacao(7, 1, this.value, 55)'>
                           <input style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtCategoriaEd' name='txtCategoriaEd' value='$codcategoria' onchange='Validacao(7, 1, this.value, 55)'>
               
                   <div class='col-12 col-md-12' style='text-align: left;'>
                            <div class='form-group label-floating'>  
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' class='control-label'>Tipo</label>
                                <select tabindex='$contadorindex' onchange='validacao(35, this.value, $codservico, 1212)' style='height:62px; font-size: 15pt; width: 100%; ' id='txtTipoServico' name='txtTipoServico' class='form-control' value='' >
                                                    <option  value='1'>PRODUTO</option>
                                                       <option  value='0'>SERVIÇO</option>
                                 </select>
                            </div>
                        </div>
                        
                        <div class='row' id='ResultadoValidacao1212'>
                            
                               <div class='col-12 col-md-4' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtEstMax' class='control-label'>Est Máx.</label>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtEstMax' name='txtEstMax' value='$est_maxservico' autofocus>
                            </div>
                        </div>  
                        <div class='col-12 col-md-4' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtEstMin' class='control-label'>Est Min.</label>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtEstMin' name='txtEstMin' value='$est_mimservico' >
                            </div>
                        </div>  
                            
                        
                        <div class='col-12 col-md-4' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' for='txtCodBarra' class='control-label'>Qtd. Produto</label>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtCodBarra' name='txtCodBarra' value='$qtdservico'>
                            </div>
                        </div>
                       </div>  
                                
      ";



      $contadorindex++;


      echo "          
                   			<div class='col-12 col-md-12' style='text-align: center;'>
                    <a tabindex='$contadorindex' style='margin-top:10px; margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' onclick='EditarInformacoes(34, $codservico, txtEstMax.value, txtEstMin.value, txtCodBarra.value, txtTipoServico.value, 0, 0, 0)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
                    </div>
					<ul id='ValidacaoProdutosEditar' style='list-style: none; margin-left: 25px;'>
                       
                    </ul>";

      $tiposervico = $resultadoservicos['tipo'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];

      echo " 
                </form>
				

						       
	";
    }


    break;
  //VALIDACAO PARA EDITAR IMAGEM DO PRODUTO/SERVIÇO
  case 30:
    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM servicos WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $tiposervico = $resultadoservicos['tipo'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];
      $cod_buscaservico = $resultadoservicos['codbusca'];
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
    }
    $contadorindex = 4;

    $tiposervico = $resultadoservicos['tipo'];
    $est_maxservico = $resultadoservicos['est_max'];
    $est_mimservico = $resultadoservicos['est_mim'];
    $cod_barraservico = $resultadoservicos['codbarra'];

    echo " 
          					<h2 style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 70%; '>Trocar Foto</h2>
						<form method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
                				<div class='col-12 col-md-12'>
                        				<div class='form-group label-floating'>
                            				<label for='flImagem' class='control-label'></label>            
                            				<input type='file' id='flImagemLivre' name='flImagemLivre'  accept='image/*' />
                       					</div>
						</div>
						<div class='col-12 col-md-12'>
                        				<div class='form-group label-floating'>
                            			<input value='$img' name='txtImagemAtual' id='txtImagemAtual' type='hidden' />
						<input value='$codservico' name='txtCodser' id='txtCodser' type='hidden' />
						<input style='margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' type='submit' name='btnAlterarImagem' id='btnAlterarImagem'  value='Salvar Foto'  class='btn btn-success btn-lg btn-block' />
							</div>
						</div>
						</form>     
                                                <div class='col-12 col-md-12' style='text-align: center;'></br>
                    					<img src='$img' alt='...' style='width:40%;'>
						</div>
					       
	";


    break;

  //SALVAR INFORMAÇÕES DO PEDIDO PARA EDITAR E COD DE BARRA
  case 31:
    $codservico = $_GET['cod'];
    $nomeproduto = $_GET['nome'];
    $descricaoproduto = $_GET['descricao'];
    $valorproduto = $_GET['valor'];
    $categoriaproduto = $_GET['categoria'];
    $est_maxproduto = $_GET['est_max'];
    $est_mimproduto = $_GET['est_mim'];
    $cod_barraproduto = $_GET['cod_barra'];

    $pontos = '.';
    $result = str_replace($pontos, "", $valorproduto);
    $result = str_replace(",", ".", $result);
    $valorproduto = $result;

    //$query = mysqli_query($conn, "UPDATE servicos SET nome = '$nomeproduto', descricao = '$descricaoproduto', valor = '$valorproduto', categoria = $categoriaproduto  WHERE cod = $codservico");
    $sql = "UPDATE servicos SET nome = :nome, descricao = :descricao, categoria = :categoria, valor=:valor, est_max = :est_max, est_mim = :est_mim, codbarra = :cod_barra WHERE cod= :cod";
    $param = array(
      ":cod" => $codservico,
      ":nome" => $nomeproduto,
      ":descricao" => $descricaoproduto,
      ":categoria" => $categoriaproduto,
      ":valor" => $valorproduto,
      ":est_max" => $est_maxproduto,
      ":est_mim" => $est_mimproduto,
      ":cod_barra" => $cod_barraproduto
    );

    $banco->ExecuteNonQuery($sql, $param);

    echo " <table class='table table-striped table-sm'>    <thead>
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
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
    // Exibe todos os valores encontrados

    $sqlServicos = "SELECT * FROM servicos WHERE cod = :nome ORDER BY cod ASC LIMIT 10";
    $paramServicos = array(
      ":nome" => $codservico
    );
    $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);

    if ($dataTableServicos2 == null) {

      $sqlServicos2 = "SELECT * FROM servicos WHERE codbusca = :nome AND codbusca != 0 ORDER BY cod ASC LIMIT 1";
      $paramServicos2 = array(
        ":nome" => $valor
      );

      $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);
    }
    if ($dataTableServicos2 == null) {


      //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
      $sqlServicos = "SELECT * FROM servicos WHERE nome LIKE :nome ORDER BY cod ASC";
      $paramServicos = array(
        ":nome" => "%{$valor}%"
      );
      $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    }
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

      echo "
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
        echo "
                                               <small style='color:red;'> Sem Estoque
                                                </small>";
      } else {
        echo "
                                        <b>Qtd:</b>$qtdservico</br>
                                      
                                          
                                       ";
      }
      echo "  </td> <td>
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
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(29, 1, $codservico)' href='javascript: func'>Informações Estoque</a></li>
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


    echo "</table>";


    break;

  //Validação para colocar mascará no dinheiro. 
  case 32:
    $valor = $_GET['valor'];
    $pontos = '.';
    $result = str_replace($pontos, "", $valor);
    $valor = str_replace(",", ".", $result);
    $valor = (float) $valor;
    $valor = number_format($valor, 2, ',', '.');

    echo "
          <div class='form-group label-floating'>
                          <label style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtValor' class='control-label'>Valor Unt.</label>
                          <input tabindex='7' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtValorEd' name='txtValorEd' value='$valor' onchange='validacao(32, 1, this.value, 55)'>
                      </div>
  ";
    break;

  //validacao para gerar codigo de busca do produto
  case 33:
    $contadorindex = 0;
    $ultimodigio = 0;
    $param = $_GET['param'];


    $sqlCat = "SELECT * FROM servicos WHERE codbusca !=0 ORDER BY codbusca DESC LIMIT 1";


    $dataTableCat = $banco->ExecuteQuery($sqlCat);

    if ($dataTableCat != null) {
      foreach ($dataTableCat as $resultadoservicos) {

        $ultimodigio = (float) $resultadoservicos['codbusca'];
        $ultimodigio = $ultimodigio + 1;
      }
    } else {
      $ultimodigio = 1000;
    }
    $sqlPag = mysqli_query($conn, "SELECT * FROM servicos  WHERE codbusca != 0 ORDER BY codbusca DESC LIMIT 1");

    $query = mysqli_query($conn, "UPDATE servicos SET codbusca = $ultimodigio WHERE cod = $param");
    if ($query) {
      echo "    <label style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtNomeProduto' class='control-label'>Cod. Busca</label>
                          <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' disabled class='form-control' id='txtCod_BuscaEd2' name='txtCod_BuscaEd2' value='$ultimodigio' autofocus>
                          <input type='hidden' class='form-control' id='txtCod_BuscaEd' name='txtCod_BuscaEd' value='$ultimodigio' />
                     ";
    }
    break;
  //VALIDACAO PARA SALVAR INFORMACOES DO ESTOQUE 
  case 34:

    $codservico = $_GET['cod'];
    $nomeproduto = $_GET['nome'];
    $descricaoproduto = $_GET['descricao'];
    $valorproduto = $_GET['valor'];
    $categoriaproduto = $_GET['categoria'];
    $est_maxproduto = $_GET['est_max'];
    $est_mimproduto = $_GET['est_mim'];
    $cod_barraproduto = $_GET['cod_barra'];

    $pontos = '.';
    $result = str_replace($pontos, "", $valorproduto);
    $result = str_replace(",", ".", $result);
    $valorproduto = $result;

    //$query = mysqli_query($conn, "UPDATE servicos SET nome = '$nomeproduto', descricao = '$descricaoproduto', valor = '$valorproduto', categoria = $categoriaproduto  WHERE cod = $codservico");
    $sql = "UPDATE servicos SET est_max = :est_max, est_mim = :est_mim, qtd = :qtd, tipo=:tipo WHERE cod= :cod";
    $param = array(
      ":cod" => $codservico,
      ":est_max" => $nomeproduto,
      ":est_mim" => $descricaoproduto,
      ":qtd" => $valorproduto,
      ":tipo" => $categoriaproduto
    );

    $banco->ExecuteNonQuery($sql, $param);

    echo " <table class='table table-striped table-sm'>    <thead>
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
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
    // Exibe todos os valores encontrados

    $sqlServicos = "SELECT * FROM servicos WHERE cod = :nome ORDER BY cod ASC LIMIT 10";
    $paramServicos = array(
      ":nome" => $codservico
    );
    $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);

    if ($dataTableServicos2 == null) {

      $sqlServicos2 = "SELECT * FROM servicos WHERE codbusca = :nome AND codbusca != 0 ORDER BY cod ASC LIMIT 1";
      $paramServicos2 = array(
        ":nome" => $valor
      );

      $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos2, $paramServicos2);
    }
    if ($dataTableServicos2 == null) {


      //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE nome LIKE '%" . $valor . "%' ORDER BY cod ASC LIMIT 10");
      $sqlServicos = "SELECT * FROM servicos WHERE nome LIKE :nome ORDER BY cod ASC";
      $paramServicos = array(
        ":nome" => "%{$valor}%"
      );
      $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    }
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

      echo "
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
        echo "
                                               <small style='color:red;'> Sem Estoque
                                                </small>";
      } else {
        echo "
                                        <b>Qtd:</b>$qtdservico</br>
                                      
                                          
                                       ";
      }
      echo "  </td> <td>
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
                                              <li><a class='dropdown-item' onclick='PagPesquisarProdutos(29, 1, $codservico)' href='javascript: func'>Informações Estoque</a></li>
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


    echo "</table>";

    break;

  //validacao para TROCAR SE PRODUTO OU SERVIÇO
  case 35:
    $param1 = $_GET['param'];
    $valor = $_GET['valor'];

    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM servicos WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $valor
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $tiposervico = $resultadoservicos['tipo'];
      $qtdservico = $resultadoservicos['qtd'];
      $est_maxservico = $resultadoservicos['est_max'];
      $est_mimservico = $resultadoservicos['est_mim'];
      $cod_barraservico = $resultadoservicos['codbarra'];
    }
    if ($param1 == 1) {
      echo "         <div class='col-12 col-md-4' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtEstMax' class='control-label'>Est Máx.</label>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtEstMax' name='txtEstMax' value='$est_maxservico' autofocus>
                            </div>
                        </div>  
                        <div class='col-12 col-md-4' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtEstMin' class='control-label'>Est Min.</label>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtEstMin' name='txtEstMin' value='$est_mimservico' >
                            </div>
                        </div>  
                          <input type='hidden' id='txtFornecedor' name='txtFornecedor' value='1' >
                            
                        
                        </div>  
                        <div class='col-12 col-md-4' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' for='txtCodBarra' class='control-label'>Qtd. Produto</label>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtCodBarra' name='txtCodBarra' value='$qtdservico'>
                            </div>
                        </div>
                       
            
            ";
    } else {
      echo "
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtEstMax' name='txtEstMax' value='$est_maxservico' autofocus>
                                <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtEstMin' name='txtEstMin' value='$est_mimservico' >
                                  <input type='hidden' id='txtFornecedor' name='txtFornecedor' value='1' >
                                  <input tabindex='4' style='padding:30px; font-size: 15pt; width: 100%; ' type='hidden' class='form-control' id='txtCodBarra' name='txtCodBarra' value='$qtdservico'>
                       
            
            ";
    }
    break;
  //validacao para buscar usuarios
  case 36:

    $param = $_GET['param'];
    $categoria = $_GET['param'];
    $valor = $_GET['valor'];





    echo " <table class='table table-striped table-sm'>    <thead>
					   <thead>
                                            <tr style='text-align:left; font-size: 12pt;'>
						<td style=''><b>NOME</b></td>
						<td style=''><b>USUÁRIO</b></td>
						<td style=''><b>CPF/CNPJ</b></td>
						<td style=''><b>E-MAIL</b></td>
						<td style=''><b>PERMISSÃO</b></td>
						<td><b>ENDEREÇO</b></td>
						<td><b>CONTATO</b></td>
						<td></td>
            
					</tr>    </thead>";




    if ($categoria == 0) {
      $sqlServicos = "SELECT * FROM usuarios WHERE nome LIKE :nome ORDER BY cod ASC";
      $paramServicos = array(
        ":nome" => "%{$valor}%"
      );

    } else {
      $sqlServicos = "SELECT * FROM usuarios WHERE nome LIKE :nome AND permissao = :permissao ORDER BY cod ASC";
      $paramServicos = array(
        ":nome" => "%{$valor}%",
        ":permissao" => $categoria,
      );


    }
    $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);

    foreach ($dataTableServicos2 as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];

      $nomeservico = $resultadoservicos['nome'];
      $usuario = $resultadoservicos['usuario'];
      $cpf = $resultadoservicos['cpf'];


      $email = $resultadoservicos['email'];
      $permissao = $resultadoservicos['permissao'];
      $rua = $resultadoservicos['rua'];
      $bairro = $resultadoservicos['bairro'];
      $numero = $resultadoservicos['numero'];
      $contato = $resultadoservicos['celular'];
      if ($permissao == 1) {
        $textopermissao = "Administrador";
      } else if ($permissao == 2) {
        $textopermissao = "Gerente";
      } else if ($permissao == 3) {
        $textopermissao = "PDV";
      }

      $enderecompleto = "<b>Endereço: </b>$rua / <b>nº </b> $numero / <b>Bairro:</b> $bairro";
      $textoteste = "<a onclick='PagPesquisarProdutos(2, txtNomeProdutoP.value, $codservico)' href='javascript: func' style='color:#fff; width:100%;' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span> Apagar</a>";


      echo "
				<tr style='text-align:left; font-size: 12pt;'>
					<td style=''>
          $nomeservico
          </td>
          <td style=''>
          $usuario
          </td>
          <td style=''>
          $cpf
          </td>
          <td style=''>
          $email
                </td>
          <td>$textopermissao</td>
              
                                        <td>$enderecompleto</td>
					<td>$contato</td>
				  <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-primary' data-bs-toggle='dropdown' aria-expanded='false'>
                                              <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-gear' viewBox='0 0 16 16'>
                                                <path d='M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0'/>
                                                <path d='M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z'/>
                                              </svg>
                                            </button>
                                            <ul class='dropdown-menu'>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(37, 1, $codservico)' href='javascript: func'>Atualizar informações do usuário</a></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(38, 1, $codservico)' href='javascript: func'>Atualizar Senha</a></li>
                                              
                                              <li><hr class='dropdown-divider'></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(39, 1, $codservico)' href='javascript: func'>Atualizar Imagem do Usuário</a></li>
                                              <li><hr class='dropdown-divider'></li>
                                              <li>$textoteste</li>
                                            </ul>
                                          </div>
                                        </td>
				</tr>
				";

    }
    echo "</table>";

    break;
  //VALIDACAO PARA EDITAR INFORMAÇÕES DO USUÁRIO
  case 37:

    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $permissao = $resultadoservicos['permissao'];
      $nome = $resultadoservicos['nome'];
      $usuario = $resultadoservicos['usuario'];
      $email = $resultadoservicos['email'];
      $cpf = $resultadoservicos['cpf'];
      $rua = $resultadoservicos['rua'];
      $bairro = $resultadoservicos['bairro'];
      $numero = $resultadoservicos['numero'];
      $celular = $resultadoservicos['celular'];


    }
    $contadorindex = 4;
    echo "
			<h2 style='padding:5px; color:#000; border-bottom: 2px solid #337AB7; width: 100%; '><span class='glyphicon glyphicon-retweet'></span> Editar Informações do Usuário</h2>
                            
				<form method='post' name='frmCadastro' id='frmCadastro' novalidate enctype='multipart/form-data'>
                    <div class='row'>
                    <div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px;  border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtNomeProduto' class='control-label'>Nome</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtNomeUsuCad' name='txtNomeUsuCad' value='$nome' autofocus>
                        </div>
                    </div>  
				          	<div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtDescricaoProduto' class='control-label'>Usuário</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtUsuarioCad' name='txtUsuarioCad' value='$usuario'  >
                        </div>
                    </div>  
                    <div class='col-12 col-md-4' style='text-align: left;' id=''>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' for='txtValor' class='control-label'>CPF/CNPJ</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtCpfCad' name='txtCpfCad' value='$cpf' onchange=''>
                        </div>
                    </div>
                    <div class='col-12 col-md-4' style='text-align: left;' id=''>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' for='txtValor' class='control-label'>E-mail</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtEmailCad' name='txtEmailCad' value='$email' onchange=''>
                        </div>
                    </div>
                    <div class='col-12 col-md-4' style='text-align: left;' id=''>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' for='txtValor' class='control-label'>Contato</label>
                            <input tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='text' class='form-control' id='txtContatoCad' name='txtContatoCad' value='$celular' onchange=''>
                        </div>
                    </div>
      ";


    echo "     </br>         <div class='col-12 col-md-12' style='text-align: left;'>
                        <div class='form-group label-floating'>  
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' class='control-label'>Permissão</label>
                            <select tabindex='$contadorindex' style='height:92px; font-size: 15pt; width: 100%; ' type='text' id='txtPermissaoCad2' name='txtPermissaoCad2' class='form-control' >
                              <option value='1'";
    if ($permissao == 1) {
      echo "selected='selected'";
    }
    echo ">Adminstrador</option>
                              <option value='2' ";
    if ($permissao == 2) {
      echo "selected='selected'";
    }
    echo ">Gerente</option>
                              <option value='3' ";
    if ($permissao == 3) {
      echo "selected='selected'";
    }
    echo ">PDV</option>
                            
							</select>
                        </div>
                    </div>
                    
                        
                            
                      ";
    $contadorindex++;
    echo " 
					<div class='col-12 col-md-12' style='text-align: center;'>
                    <a tabindex='$contadorindex' style='margin-top:10px; margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' onclick='EditarInformacoesUsuario(40, $codservico, txtNomeUsuCad.value, txtUsuarioCad.value, txtCpfCad.value, txtEmailCad.value, txtContatoCad.value, txtPermissaoCad2.value, 0)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
                    </div>
					";




    echo " </div>
                </form>
				

						       
	";


    break;

  //VALIDACAO PARA ATUALIZAR SENHA DO USUÁRIO
  case 38:

    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $permissao = $resultadoservicos['permissao'];
      $nome = $resultadoservicos['nome'];
      $usuario = $resultadoservicos['usuario'];
      $email = $resultadoservicos['email'];
      $cpf = $resultadoservicos['cpf'];
      $rua = $resultadoservicos['rua'];
      $bairro = $resultadoservicos['bairro'];
      $numero = $resultadoservicos['numero'];
      $celular = $resultadoservicos['celular'];


    }
    $contadorindex = 4;
    echo "
			<h2 style='padding:5px; color:#000; border-bottom: 2px solid #337AB7; width: 100%; '><span class='glyphicon glyphicon-retweet'></span> Editar Informações do Usuário</h2>
                            
				<form method='post' name='frmCadastro' id='frmCadastro' novalidate enctype='multipart/form-data'>
                    <div class='row'>
                    <div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px;  border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtNomeProduto' class='control-label'>Nova Senha</label>
                            <input onkeyup='validacaopagamento(42, this.value, txtUsuarioCad.value, $codservico, 0, 0, 0, 42)' tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='password' class='form-control' id='txtNomeUsuCad' name='txtNomeUsuCad' value='' autofocus>
                        </div>
                    </div>  
                    ";
    $contadorindex++;
    echo " 
				          	<div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; border-bottom: 2px solid; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtDescricaoProduto' class='control-label'>Confirmar Senha</label>
                            <input onkeyup='validacaopagamento(42, txtNomeUsuCad.value, this.value, $codservico, 0, 0, 0, 42)' tabindex='$contadorindex' style='padding:30px; font-size: 15pt; width: 100%; ' type='password' class='form-control' id='txtUsuarioCad' name='txtUsuarioCad' value=''  >
                        </div>
                    </div>  
                    ";
    $contadorindex++;
    echo " 
					<div class='col-12 col-md-12' style='text-align: center;' id='ResultadoValidacao42'>
                    <a tabindex='$contadorindex' style='margin-top:10px; margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' onclick='EditarInformacoesUsuario(41, $codservico, txtNomeUsuCad.value, txtUsuarioCad.value, 0, 0, 0, 0, 0)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
                    </div>
					";




    echo " </div>
                </form>
				

						       
	";

    break;

  //VALIDACAO PARA ATUALIZAR FOTO DO USUÁRIO
  case 39:
    $codservico = (float) $_GET['valor'];
    //$sqlServicos = mysqli_query($conn, "SELECT * FROM servicos WHERE cod = $codservico ORDER BY cod ASC LIMIT 1");
    $sqlServicos = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
    $paramServicos = array(
      ":cod" => $codservico
    );
    $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];

      $img = $resultadoservicos['foto'];
      if ($img == null) {
        $img = "imagem mostrar pedido sem foto";
      } else {
        $img = "Interface/img/Usuarios/$img";
      }
    }


    echo " 
          					<h2 style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 70%; '>Trocar Foto</h2>
						<form method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
                				<div class='col-12 col-md-12'>
                        				<div class='form-group label-floating'>
                            				<label for='flImagem' class='control-label'></label>            
                            				<input type='file' id='flImagemLivre' name='flImagemLivre'  accept='image/*' />
                       					</div>
						</div>
						<div class='col-12 col-md-12'>
                        				<div class='form-group label-floating'>
                            			<input value='$img' name='txtImagemAtual' id='txtImagemAtual' type='hidden' />
						<input value='$codservico' name='txtCodser' id='txtCodser' type='hidden' />
						<input style='margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' type='submit' name='btnAlterarImagemUsu' id='btnAlterarImagemUsu'  value='Salvar Foto'  class='btn btn-success btn-lg btn-block' />
							</div>
						</div>
						</form>     
                                                <div class='col-12 col-md-12' style='text-align: center;'></br>
                    					<img src='$img' alt='...' style='width:40%;'>
						</div>
					       
	";



    break;

  //validacao para salvar informação do ususario
  case 40:
    $codusu = $_GET['cod'];
    $nome = $_GET['nome'];
    $usuario = $_GET['descricao'];
    $cpf = $_GET['valor'];
    $email = $_GET['categoria'];
    $celular = $_GET['est_max'];
    $permissao = $_GET['est_mim'];
    $cod_barraproduto = $_GET['cod_barra'];


    //$query = mysqli_query($conn, "UPDATE servicos SET nome = '$nomeproduto', descricao = '$descricaoproduto', valor = '$valorproduto', categoria = $categoriaproduto  WHERE cod = $codservico");
    $sql = "UPDATE usuarios SET nome = :nome, usuario = :usuario, cpf = :cpf, email=:email, celular = :celular, permissao = :permissao WHERE cod= :cod";
    $param = array(
      ":cod" => $codusu,
      ":nome" => $nome,
      ":usuario" => $usuario,
      ":cpf" => $cpf,
      ":email" => $email,
      ":celular" => $celular,
      ":permissao" => $permissao
    );

    $banco->ExecuteNonQuery($sql, $param);




    echo " <table class='table table-striped table-sm'>    <thead>
					   <thead>
                                            <tr style='text-align:left; font-size: 12pt;'>
						<td style=''><b>NOME</b></td>
						<td style=''><b>USUÁRIO</b></td>
						<td style=''><b>CPF/CNPJ</b></td>
						<td style=''><b>E-MAIL</b></td>
						<td style=''><b>PERMISSÃO</b></td>
						<td><b>ENDEREÇO</b></td>
						<td><b>CONTATO</b></td>
						<td></td>
            
					</tr>    </thead>";




    $sqlServicos = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC";
    $paramServicos = array(
      ":cod" => $codusu
    );

    $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);

    foreach ($dataTableServicos2 as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];

      $nomeservico = $resultadoservicos['nome'];
      $usuario = $resultadoservicos['usuario'];
      $cpf = $resultadoservicos['cpf'];


      $email = $resultadoservicos['email'];
      $permissao = $resultadoservicos['permissao'];
      $rua = $resultadoservicos['rua'];
      $bairro = $resultadoservicos['bairro'];
      $numero = $resultadoservicos['numero'];
      $contato = $resultadoservicos['celular'];
      if ($permissao == 1) {
        $textopermissao = "Administrador";
      } else if ($permissao == 2) {
        $textopermissao = "Gerente";
      } else if ($permissao == 3) {
        $textopermissao = "PDV";
      }

      $enderecompleto = "<b>Endereço: </b>$rua / <b>nº </b> $numero / <b>Bairro:</b> $bairro";
      $textoteste = "<a onclick='PagPesquisarProdutos(2, txtNomeProdutoP.value, $codservico)' href='javascript: func' style='color:#fff; width:100%;' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span> Apagar</a>";


      echo "
				<tr style='text-align:left; font-size: 12pt;'>
					<td style=''>
          $nomeservico
          </td>
          <td style=''>
          $usuario
          </td>
          <td style=''>
          $cpf
          </td>
          <td style=''>
          $email
                </td>
          <td>$textopermissao</td>
              
                                        <td>$enderecompleto</td>
					<td>$contato</td>
				  <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-primary' data-bs-toggle='dropdown' aria-expanded='false'>
                                              <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-gear' viewBox='0 0 16 16'>
                                                <path d='M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0'/>
                                                <path d='M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z'/>
                                              </svg>
                                            </button>
                                            <ul class='dropdown-menu'>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(37, 1, $codservico)' href='javascript: func'>Atualizar informações do usuário</a></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(38, 1, $codservico)' href='javascript: func'>Atualizar Senha</a></li>
                                              
                                              <li><hr class='dropdown-divider'></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(39, 1, $codservico)' href='javascript: func'>Atualizar Imagem do Usuário</a></li>
                                              <li><hr class='dropdown-divider'></li>
                                              <li>$textoteste</li>
                                            </ul>
                                          </div>
                                        </td>
				</tr>
				";

    }
    echo "</table>";

    break;

  //validacao para alterar senha do usuario via adminstrador
  case 41:

    $codusu = $_GET['cod'];
    $nome = $_GET['nome'];
    $usuario = $_GET['descricao'];
    $cpf = $_GET['valor'];
    $email = $_GET['categoria'];
    $celular = $_GET['est_max'];
    $permissao = $_GET['est_mim'];
    $cod_barraproduto = $_GET['cod_barra'];
    $senha = md5($nome);


    //$query = mysqli_query($conn, "UPDATE servicos SET nome = '$nomeproduto', descricao = '$descricaoproduto', valor = '$valorproduto', categoria = $categoriaproduto  WHERE cod = $codservico");
    $sql = "UPDATE usuarios SET senha = :senha WHERE cod= :cod";
    $param = array(
      ":cod" => $codusu,
      ":senha" => $senha,
    );

    $banco->ExecuteNonQuery($sql, $param);





    echo " <table class='table table-striped table-sm'>    <thead>
					   <thead>
                                            <tr style='text-align:left; font-size: 12pt;'>
						<td style=''><b>NOME</b></td>
						<td style=''><b>USUÁRIO</b></td>
						<td style=''><b>CPF/CNPJ</b></td>
						<td style=''><b>E-MAIL</b></td>
						<td style=''><b>PERMISSÃO</b></td>
						<td><b>ENDEREÇO</b></td>
						<td><b>CONTATO</b></td>
						<td></td>
            
					</tr>    </thead>";




    $sqlServicos = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC";
    $paramServicos = array(
      ":cod" => $codusu
    );

    $dataTableServicos2 = $banco->ExecuteQuery($sqlServicos, $paramServicos);

    foreach ($dataTableServicos2 as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];

      $nomeservico = $resultadoservicos['nome'];
      $usuario = $resultadoservicos['usuario'];
      $cpf = $resultadoservicos['cpf'];


      $email = $resultadoservicos['email'];
      $permissao = $resultadoservicos['permissao'];
      $rua = $resultadoservicos['rua'];
      $bairro = $resultadoservicos['bairro'];
      $numero = $resultadoservicos['numero'];
      $contato = $resultadoservicos['celular'];
      if ($permissao == 1) {
        $textopermissao = "Administrador";
      } else if ($permissao == 2) {
        $textopermissao = "Gerente";
      } else if ($permissao == 3) {
        $textopermissao = "PDV";
      }

      $enderecompleto = "<b>Endereço: </b>$rua / <b>nº </b> $numero / <b>Bairro:</b> $bairro";
      $textoteste = "<a onclick='PagPesquisarProdutos(2, txtNomeProdutoP.value, $codservico)' href='javascript: func' style='color:#fff; width:100%;' class='btn btn-danger btn-sm'><span class='glyphicon glyphicon-trash'></span> Apagar</a>";


      echo "
				<tr style='text-align:left; font-size: 12pt;'>
					<td style=''>
          $nomeservico
          </td>
          <td style=''>
          $usuario
          </td>
          <td style=''>
          $cpf
          </td>
          <td style=''>
          $email
                </td>
          <td>$textopermissao</td>
              
                                        <td>$enderecompleto</td>
					<td>$contato</td>
				  <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-primary' data-bs-toggle='dropdown' aria-expanded='false'>
                                              <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' class='bi bi-gear' viewBox='0 0 16 16'>
                                                <path d='M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0'/>
                                                <path d='M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z'/>
                                              </svg>
                                            </button>
                                            <ul class='dropdown-menu'>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(37, 1, $codservico)' href='javascript: func'>Atualizar informações do usuário</a></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(38, 1, $codservico)' href='javascript: func'>Atualizar Senha</a></li>
                                              
                                              <li><hr class='dropdown-divider'></li>
                                              <li><a class='dropdown-item' onclick='PagPesquisarUsuario(39, 1, $codservico)' href='javascript: func'>Atualizar Imagem do Usuário</a></li>
                                              <li><hr class='dropdown-divider'></li>
                                              <li>$textoteste</li>
                                            </ul>
                                          </div>
                                        </td>
				</tr>
				";

    }
    echo "</table>";

    break;

  //validacao para verificar se senha são identicas
  case 42:
    $param = $_GET['param1'];
    $valor = $_GET['param2'];
    $codservico = $_GET['param3'];
    $contadorindex = 5;
    if ($param == $valor) {
      echo "<b style='color:green; text-align:left;'>As senhas são idênticas.</b>
             <a tabindex='$contadorindex' style='margin-top:10px; margin-left: 5%; padding:20px; font-size: 15pt; width: 90%;' onclick='EditarInformacoesUsuario(41, $codservico, txtNomeUsuCad.value, txtUsuarioCad.value, 0, 0, 0, 0, 0)'  class='btn btn-outline-success'><span class='glyphicon glyphicon-floppy-saved'></span> Salvar Informações</a>
               
        ";

    } else {
      echo "<b style='color:red'>As senhas não são idênticas.</b>
        
        
      ";
    }

    break;

  //VALIDACAO PARA PESQUISAR EM OUTROS MESES CREDIARIOS EM ABERTO
  case 43:
    $codnota = $_GET['codnota'];

    $t = explode("-", $codnota);

    $mes = $t[1];
    $ano = $t[0];

    echo "<h3 style='padding:5px; color:000; border-bottom: 2px solid #337AB7; width: 100%; '>Lista de Pendências Crediário - ANO : $ano<span class='blog-post-meta'></h3>
        
";
    echo "<table class='table' style='font-size:12pt; width:100%;'>
			<tr style='text-align:center;'>
				<td><b>Mês</b></td>
				<td><b>nº de Contas a Receber</b></td>
				<td><b>Total em Crediário</b></td>
				<td><b>Total Recebido</b></td>
				<td><b>Total a Receber</b></td>
                                </tr>
                                ";

    for ($i = 1; $i <= 12; $i++) {

      $contadorcontas = 0;
      $totalemcrediario = 0;
      $valortotalparcelas = 0;

      $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 AND mes = $i AND ano = $ano ORDER BY cod ASC");
      // Exibe todos os valores encontrados

      while ($financeirocli = mysqli_fetch_object($sql)) {
        $contadorparcelas = 0;
        $codfin = $financeirocli->cod;
        $codnota = $financeirocli->cod_orcamento;
        $valortotalfinanceiro = (float) $financeirocli->total;
        $diaatual = date('d');
        $anoatual = date('Y');
        $mesatual = date('m');

        $diapag = 0;
        $mespag = 0;
        $anopag = 0;
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

        $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($finpar = mysqli_fetch_object($sqlPar)) {
          $contadorparcelas++;
        }

        if ($numparcelas != $contadorparcelas) {
          $contadorcontas++;
          $totalemcrediario = $totalemcrediario + $valortotalfinanceiro;

          $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
          // Exibe todos os valores encontrados
          while ($finpar = mysqli_fetch_object($sqlPar)) {
            $valortotalparcelas = $valortotalparcelas + (float) $finpar->valor;
            $diapag = $finpar->dia;
            $mespag = $finpar->mes;
            $anopag = $finpar->ano;
          }


          $textostatus = "";
          if ($diapag == 0) {
            $textostatus = " Nenhuma Parcela Paga";
          }
        }
      }
      if ($contadorcontas != 0) {
        echo " <tr style='text-align:center;'>
				<td>" . mostraMes($i) . "</td>
				<td>$contadorcontas</td>
				<td>" . number_format($totalemcrediario, 2, ',', '.') . "</td>
				<td>" . number_format($valortotalparcelas, 2, ',', '.') . "</td>
				<td>" . number_format($totalemcrediario - $valortotalparcelas, 2, ',', '.') . "</td>
				
                                </tr>
                                ";
      }
    }
    echo "</table>";
    echo "<h3 style='padding:5px; color:000; border-bottom: 2px solid #337AB7; width: 100%; '>Lista de Pendências Crediário - " . mostraMes($mes) . " $ano<span class='blog-post-meta'></h3>
        ";
    echo " 
		<table class='table' style='font-size:12pt; width:100%;'>
			<tr style='text-align:center;'>
				<td><b>Cod. Nota</b></td>
				<td><b>Cliente</b></td>
				<td><b>Valor Total</b></td>
				<td><b>Recebido</b></td>
				<td><b>A receber</b></td>
				<td><b>Pagamento</b></td>
				<td><b>Status</b></td>
				<td><b></b></td>
			</tr>
		";
    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 AND mes = $mes AND ano = $ano ORDER BY cod ASC");
    // Exibe todos os valores encontrados
    $valortotalfinanceiro2 = 0;
    while ($financeirocli = mysqli_fetch_object($sql)) {
      $contadorparcelas = 0;
      $codfin = $financeirocli->cod;
      $codfin2 = $financeirocli->cod;
      $codnota = $financeirocli->cod_orcamento;
      $codnota2 = $financeirocli->cod_orcamento;
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

      $sqlPar22 = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin2 ORDER BY cod ASC LIMIT 1");
      // Exibe todos os valores encontrados
      while ($finpar = mysqli_fetch_object($sqlPar22)) {
        $diapag2 = $finpar->dia;
        $mespag2 = $finpar->mes;
        $anopag2 = $finpar->ano;
      }


      $areceber = 0;
      $areceber = number_format($valortotalfinanceiro2 - $valortotalparcelas, 2, ',', '.');
      $valortotalparcelas = number_format($valortotalparcelas, 2, ',', '.');

      $textostatus = "";
      if ($diapag == 0) {
        $textostatus = " Nenhuma Parcela Paga";
      }
      if ($numparcelas != $contadorparcelas) {
        echo "
									<tr style='text-align:center;'>
										<td>$codnota</td>
										<td>$nomecli</td>
										<td>$pagamentototal</td>
										<td>$valortotalparcelas</td>
										<td>$areceber</td>
										<td>$textopagamentotipo</td>
										<td><span style='color:red'>Crediário em Aberto.";
        if ($diapag != 0) {
          echo " 
                                                                                    Ultimo Pagamento:$diapag2/$mespag2/$anopag2</span> ";
        } else {
          echo $textostatus;
        }
        echo "      </td>
										<td> 
                             <a class='btn btn-outline-primary' onclick='validacao(17, $codnota, 0, 17)' href='javascript: func' type='button' data-bs-toggle='modal' data-bs-target='#exampleModalDetalhesPedido' >
                          <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-folder-plus' viewBox='0 0 16 16'>
                          <path d='m.5 3 .04.87a2 2 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2m5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19q-.362.002-.683.12L1.5 2.98a1 1 0 0 1 1-.98z'/>
                          <path d='M13.5 9a.5.5 0 0 1 .5.5V11h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V12h-1.5a.5.5 0 0 1 0-1H13V9.5a.5.5 0 0 1 .5-.5'/>
                        </svg>
                    </a>
                    <a class='btn btn-outline-primary' href='?pagina=carinhocompras&cod=$codnota' type='button' >
                        <svg xmlns='http://www.w3.org/2000/svg' width='25' height='25' fill='currentColor' class='bi bi-arrow-right-square-fill' viewBox='0 0 16 16'>
                          <path d='M0 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2zm4.5-6.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5a.5.5 0 0 1 0-1'/>
                        </svg>
                    </a>
                    </td>
									</tr>
            ";
      }
    }
    echo "</table>";

    break;

  //VALIDACAO PARA BUSCAR CREDIARIOS EM ATRASO
  case 44:

    echo "<h3 style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; '>Lista de Pagamentos Atrasados - <span class='blog-post-meta'>" . mostraMes(date('m')) . " " . date('d') . ", " . date('Y') . "</h3>
    ";

    echo "
<div id='vamosvertudo44'>
<table class='table' style='font-size:16pt;'>
<tr style='text-align:center; background-color:#337AB7; color:#fff; '>
<td><b>Cod. Nota</b></td>
<td><b>Cliente</b></td>
<td><b>Qtd Pedidos</b></td>
<td><b>Valor Total</b></td>
<td><b>Pagamento</b></td>
<td><b></b></td>
</tr>
";
    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 ORDER BY cod ASC");
    // Exibe todos os valores encontrados

    while ($financeirocli = mysqli_fetch_object($sql)) {
      $codfin = $financeirocli->cod;
      $codnota = $financeirocli->cod_orcamento;
      $valortotalfinanceiro = (float) $financeirocli->total;
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
      }
      if (($valortotalfinanceiro - $valortotalparcelas) != 0) {
        $codparcela;
        $diferenca = 0;
        $diferencadia = 0;

        if ($anoatual == $anopag && $anopag != 0) {
          $diferenca = $mesatual - $mespag;
          $dia = $diapag;
          $mes = $mespag;
          $ano = $anopag;
          if ($diferenca == 1) {
            $diferencadia = $diaatual - $diapag;
            if ($diferencadia > 0) {
              echo "
      <tr style='text-align:center;'>
        <td>$codnota</td>
        <td>$nomecli</td>
        <td>$qtdpedidos</td>
        <td>$pagamentototal</td>
        <td>$textopagamentotipo</td>
        <td> <a href='javascript: func' onclick='PagVerTudo(10, $codnota, 44)' class='btn btn-primary btn-sm btn-block' style='font-size:14pt;'><span class='glyphicon glyphicon-play'></span> Ver Tudo </a></td>
      </tr>
    ";
            }
          } else if ($diferenca > 1) {
            echo "
      <tr style='text-align:center;'>
        <td>$codnota</td>
        <td>$nomecli</td>
        <td>$qtdpedidos</td>
        <td>$pagamentototal</td>
        <td>$textopagamentotipo</td>
        <td><a href='javascript: func' onclick='PagVerTudo(10, $codnota, 44)' class='btn btn-primary btn-sm btn-block' style='font-size:14pt;'><span class='glyphicon glyphicon-play'></span> Ver Tudo </a></td>
      </tr>
    ";
          }
        } else if ($anoatual > $anopag && $anopag != 0) {
          //  echo "ano atual maior que o ano Parcela";
          $mesatual = $mesatual + 12;
          $diferenca = $mesatual - $mespag;
          if ($diferenca == 1) {
            $diferencadia = $diaatual - $diapag;
            if ($diferencadia > 0) {
              echo "
      <tr style='text-align:center;'>
        <td>$codnota</td>
        <td>$nomecli</td>
        <td>$qtdpedidos</td>
        <td>$pagamentototal</td>
        <td>$textopagamentotipo</td>
        <td><a href='javascript: func' onclick='PagVerTudo(10, $codnota, 44)' class='btn btn-primary btn-sm btn-block' style='font-size:14pt;'><span class='glyphicon glyphicon-play'></span> Ver Tudo </a></td>
      </tr>
    ";
            }
          } else if ($diferenca > 1) {
            echo "
      <tr style='text-align:center;'>
        <td>$codnota</td>
        <td>$nomecli</td>
        <td>$qtdpedidos</td>
        <td>$pagamentototal</td>
        <td>$textopagamentotipo</td>
        <td><a href='javascript: func' onclick='PagVerTudo(10, $codnota, 44)' class='btn btn-primary btn-sm btn-block' style='font-size:14pt;'><span class='glyphicon glyphicon-play'></span> Ver Tudo </a></td>
      </tr>
    ";
          }
        }
      }
    }
    echo "</table></div>";

    break;
  //pagina inicial do filtro para faturamento   
  case 45:

    echo "
          <div class='row' style='background-color:#fff; margin-bottom:10px;'>
            <div class='col-sm-12 col-xl-4'>
              <label>Deseja consultar qual período</label>
              <select onchange='validacaorelatorios(46, this.value, txtTipopagamentoAvista.value, txtTipopagamento.value, 0, 0, 0, 46)' name='txtConsultaPeriodo' id='txtConsultaPeriodo' class='form-control' style='font-size:12pt; padding:20px;'>
                  <option value='1'>Dia</option>
                  <option value='2'>Mes</option>
                  <option value='3'>Ano</option>
              </select>
            </div>  
            <div class='col-sm-12 col-xl-4'>
              <label>Tipo de Pagamento</label>
              <select onchange='validacaorelatorios(46, txtConsultaPeriodo.value, this.value, txtTipopagamento.value, 0, 0, 0, 46)' name='txtTipopagamentoAvista' id='txtTipopagamentoAvista' class='form-control' style='font-size:12pt; padding:20px;'>
                  <option value='0'>TODOS</option>
                  <option value='1'>Á VISTA</option>
                  <option value='2'>CRÉDIARIO</option>
              </select>
            </div>  
        
            <div class='col-sm-12 col-xl-4'>
              <label>Tipo de Pagamento</label>
              <select onchange='validacaorelatorios(46, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, this.value, 0, 0, 0, 46)' name='txtTipopagamento' id='txtTipopagamento' class='form-control' style='font-size:12pt; padding:20px;'>
                  <option value='0'>Todos</option>
                  <option value='1'>Dinheiro</option>
                  <option value='2'>Débito</option>
                  <option value='3'>Crédito</option>
                  <option value='4'>Pix</option>
                  <option value='5'>Desconto</option>
              </select>
            </div>  

          </div>  
          <div id='ResultadoValidacao46'>
          </div>
      ";
    break;
  //pagina de resultado do filtro para faturamento   
  case 46:


    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];

    if ($param1 == 1) {
      echo "
                <div class='col-sm-12 col-xl-3'>
                        <label>Data</label>
                        <input onkeyup='validacaorelatorios(47, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, txtTipopagamento.value, this.value, 0, 0, 47)' class='form-control' style='font-size:12pt; padding:20px;' type='date' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m-d') . "' />
                      </div>  
                       <div id='ResultadoValidacao47'> 
              ";


      $dia_hoje = date("d");
      $mes_hoje = date("m");
      $ano_hoje = date("Y");


      if ($param3 == 0) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 1) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 2) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 3) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 4) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 5) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      }

      echo "<table class='table table-striped table-sm' style='font-size:10pt; width:100%'>
                      <tr>
                          <td><b>Cod. Nota</b></td>
                          <td><b>Tipo Pagamento</b></td>
                          <td><b>Cliente</b></td>
                         
                          <td><b>Funcionário</b></td>
                          <td><b>Data e Hora</b></td>
                          <td><b>Tipo Venda</b></td>
                           <td><b>Valor Total	</b></td>
                           <td><b>SubTotal</b></td>
                          <td><b>Dinheiro</b></td>
                          <td><b>Débito</b></td>
                          <td><b>Crédito</b></td>
                          <td><b>Pix</b></td>
                          <td><b>Desconto</b></td>

                      </tr>
              
          ";

      $textotipo = 0;
      $valorTotalFinal = 0;
      $nomecli = "";

      $totalfinalsubtotal = 0;
      $totalfinaldinheiro = 0;
      $totalfinaldebito = 0;
      $totalfinalcredito = 0;
      $totalfinalpix = 0;
      $totalfinaldesconto = 0;

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];

        $sqlNota = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
        $paramNota = array(
          ":cod" => $cod_orcamento
        );


        $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
        foreach ($dataTableNota as $resultadonota) {
          $codnota = $resultadonota['cod'];
          $codcli = $resultadonota['usuario'];

          $dia = $resultadonota['dia'];
          $mes = $resultadonota['mes'];
          $ano = $resultadonota['ano'];
          $hora = $resultadonota['hora'];

          $func = $resultadonota['func'];
          $tipo = $resultadonota['tipo_pedido'];


          if ($codcli != 0) {
            $nomeCli = "";
            $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
            $paramCli = array(
              ":id" => $codcli
            );
            $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
            foreach ($dataTableCli as $resultadocli) {
              $nomecli = $resultadocli['nome'];
            }
          } else {
            $nomecli = $resultadonota['nomeCli'];
          }
          $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
          $paramCli = array(
            ":cod" => $func
          );
          $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
          foreach ($dataTableCli as $resultadocli) {
            $nomefunc = $resultadocli['nome'];
          }
          $textotipovenda = "";
          if ($tipo == 1) {
            $textotipovenda = "Venda Expressa";
          } else if ($tipo == 2) {
            $textotipovenda = "Retirada no Balcão";
          } else if ($tipo == 3) {
            $textotipovenda = "Entrega";
          }

          $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;

        }

        $numparcelas = (float) $resultadonotaPag['numparcelas'];
        $tipo = (float) $resultadonotaPag['tipo'];
        if ($tipo == 1) {
          $textotipo = "Pagamento á vista";
        } else {
          $textotipo = "Pagamento Parcelado" . $numparcelas . "x";
        }


        $total = (float) $resultadonotaPag['total'];
        $subtotal = (float) $resultadonotaPag['subtotal'];
        $gorjeta = (float) $resultadonotaPag['gorjeta'];
        $dinheiro = (float) $resultadonotaPag['dinheiro'];
        $debito = (float) $resultadonotaPag['debito'];
        $credito = (float) $resultadonotaPag['credito'];
        $pix = (float) $resultadonotaPag['pix'];
        $desconto = (float) $resultadonotaPag['desconto'];

        $valorTotalFinal = $valorTotalFinal + $total;


        $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
        $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
        $totalfinaldebito = $totalfinaldebito + $debito;
        $totalfinalcredito = $totalfinalcredito + $credito;
        $totalfinalpix = $totalfinalpix + $pix;
        $totalfinaldesconto = $totalfinaldesconto + $desconto;



        echo "<tr>
                    <td>$cod_orcamento</td>
                    <td>$textotipo</td>
                    <td>$nomecli</td>
                    <td>$nomefunc</td>
                    <td>$datahora</td>
                    <td>$textotipovenda</td>
                    
                    <td>R$ " . number_format($total, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($dinheiro, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($debito, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($credito, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($pix, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($desconto, 2, ',', '.') . "</td>
                 
                 </tr>";

      }
      echo "<tr>
              
              <td colspan='6' style='text-align:right;'>TOTAL FINAL</td>
              <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinalsubtotal, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinaldesconto, 2, ',', '.') . "</td>
           
           </tr>";

      echo "</table></div>";
    }
    if ($param1 == 2) {
      echo "
      <div class='col-sm-12 col-xl-3'>
              <label>Mês/Ano</label>
              <input onkeyup='validacaorelatorios(48, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, txtTipopagamento.value, this.value, 0, 0, 48)' class='form-control' style='font-size:12pt; padding:20px;' type='month' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m') . "' />
            </div>  
    ";
      echo "
               
                       <div id='ResultadoValidacao48'> 
              ";


      $dia_hoje = date("d");
      $mes_hoje = (float) date("m");
      $ano_hoje = date("Y");


      if ($param3 == 0) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 1) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 2) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 3) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 4) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 5) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      }

      echo "<table class='table table-striped table-sm' style='font-size:10pt; width:100%'>
                      <tr>
                          <td><b>Data e Hora</b></td>
                           <td><b>Valor Total	</b></td>
                           <td><b>SubTotal</b></td>
                          <td><b>Dinheiro</b></td>
                          <td><b>Débito</b></td>
                          <td><b>Crédito</b></td>
                          <td><b>Pix</b></td>
                          <td><b>Desconto</b></td>

                      </tr>
              
          ";

      $textotipo = 0;
      $valorTotalFinal = 0;
      $nomecli = "";

      $totalfinalsubtotal = 0;
      $totalfinaldinheiro = 0;
      $totalfinaldebito = 0;
      $totalfinalcredito = 0;
      $totalfinalpix = 0;
      $totalfinaldesconto = 0;

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];

        $sqlNota = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
        $paramNota = array(
          ":cod" => $cod_orcamento
        );


        $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
        foreach ($dataTableNota as $resultadonota) {
          $codnota = $resultadonota['cod'];
          $codcli = $resultadonota['usuario'];

          $dia = $resultadonota['dia'];
          $mes = $resultadonota['mes'];
          $ano = $resultadonota['ano'];
          $hora = $resultadonota['hora'];

          $func = $resultadonota['func'];
          $tipo = $resultadonota['tipo_pedido'];


          if ($codcli != 0) {
            $nomeCli = "";
            $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
            $paramCli = array(
              ":id" => $codcli
            );
            $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
            foreach ($dataTableCli as $resultadocli) {
              $nomecli = $resultadocli['nome'];
            }
          } else {
            $nomecli = $resultadonota['nomeCli'];
          }
          $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
          $paramCli = array(
            ":cod" => $func
          );
          $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
          foreach ($dataTableCli as $resultadocli) {
            $nomefunc = $resultadocli['nome'];
          }
          $textotipovenda = "";
          if ($tipo == 1) {
            $textotipovenda = "Venda Expressa";
          } else if ($tipo == 2) {
            $textotipovenda = "Retirada no Balcão";
          } else if ($tipo == 3) {
            $textotipovenda = "Entrega";
          }

          $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;

        }

        $numparcelas = (float) $resultadonotaPag['numparcelas'];
        $tipo = (float) $resultadonotaPag['tipo'];
        if ($tipo == 1) {
          $textotipo = "Pagamento á vista";
        } else {
          $textotipo = "Pagamento Parcelado" . $numparcelas . "x";
        }


        $total = (float) $resultadonotaPag['total'];
        $subtotal = (float) $resultadonotaPag['subtotal'];
        $gorjeta = (float) $resultadonotaPag['gorjeta'];
        $dinheiro = (float) $resultadonotaPag['dinheiro'];
        $debito = (float) $resultadonotaPag['debito'];
        $credito = (float) $resultadonotaPag['credito'];
        $pix = (float) $resultadonotaPag['pix'];
        $desconto = (float) $resultadonotaPag['desconto'];

        $valorTotalFinal = $valorTotalFinal + $total;


        $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
        $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
        $totalfinaldebito = $totalfinaldebito + $debito;
        $totalfinalcredito = $totalfinalcredito + $credito;
        $totalfinalpix = $totalfinalpix + $pix;
        $totalfinaldesconto = $totalfinaldesconto + $desconto;



        echo "
                 <tr>
                    <td>$datahora</td>
                    
                    <td>R$ " . number_format($total, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($dinheiro, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($debito, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($credito, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($pix, 2, ',', '.') . "</td>
                    <td>R$ " . number_format($desconto, 2, ',', '.') . "</td>
                 
                 </tr>";

      }
      echo "<tr>
              
              <td colspan='1' style='text-align:right;'>TOTAL FINAL</td>
              <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinalsubtotal, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
              <td>R$ " . number_format($totalfinaldesconto, 2, ',', '.') . "</td>
           
           </tr>";

      echo "</table></div>";
    }
    if ($param1 == 3) {
      echo "
      <div class='col-sm-12 col-xl-3'>
              <label>Ano</label>
              <input onkeyup='validacaorelatorios(49, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, txtTipopagamento.value, this.value, 0, 0, 49)'  min='2025' max='2050' class='form-control' style='font-size:12pt; padding:20px;' type='number' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y') . "' />
            </div>  
    ";

      echo "
               
    <div id='ResultadoValidacao49'> 
";


      $dia_hoje = date("d");
      $mes_hoje = (float) date("m");
      $ano_hoje = date("Y");


      if ($param3 == 0) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE   ano = :ano ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE   ano = :ano AND tipo = :tipo ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 1) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE   ano = :ano AND tipo = :tipo AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 2) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND tipo = :tipo AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 3) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND tipo = :tipo AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 4) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND tipo = :tipo AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 5) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE   ano = :ano AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE   ano = :ano AND tipo = :tipo AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      }

      echo "<table class='table table-striped table-sm' style='font-size:10pt; width:100%'>
   <tr>
       <td><b>Data e Hora</b></td>
        <td><b>Valor Total	</b></td>
        <td><b>SubTotal</b></td>
       <td><b>Dinheiro</b></td>
       <td><b>Débito</b></td>
       <td><b>Crédito</b></td>
       <td><b>Pix</b></td>
       <td><b>Desconto</b></td>

   </tr>

";

      $textotipo = 0;
      $valorTotalFinal = 0;
      $nomecli = "";

      $totalfinalsubtotal = 0;
      $totalfinaldinheiro = 0;
      $totalfinaldebito = 0;
      $totalfinalcredito = 0;
      $totalfinalpix = 0;
      $totalfinaldesconto = 0;

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];

        $sqlNota = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
        $paramNota = array(
          ":cod" => $cod_orcamento
        );


        $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
        foreach ($dataTableNota as $resultadonota) {
          $codnota = $resultadonota['cod'];
          $codcli = $resultadonota['usuario'];

          $dia = $resultadonota['dia'];
          $mes = $resultadonota['mes'];
          $ano = $resultadonota['ano'];
          $hora = $resultadonota['hora'];

          $func = $resultadonota['func'];
          $tipo = $resultadonota['tipo_pedido'];


          if ($codcli != 0) {
            $nomeCli = "";
            $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
            $paramCli = array(
              ":id" => $codcli
            );
            $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
            foreach ($dataTableCli as $resultadocli) {
              $nomecli = $resultadocli['nome'];
            }
          } else {
            $nomecli = $resultadonota['nomeCli'];
          }
          $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
          $paramCli = array(
            ":cod" => $func
          );
          $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
          foreach ($dataTableCli as $resultadocli) {
            $nomefunc = $resultadocli['nome'];
          }
          $textotipovenda = "";
          if ($tipo == 1) {
            $textotipovenda = "Venda Expressa";
          } else if ($tipo == 2) {
            $textotipovenda = "Retirada no Balcão";
          } else if ($tipo == 3) {
            $textotipovenda = "Entrega";
          }

          $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;

        }

        $numparcelas = (float) $resultadonotaPag['numparcelas'];
        $tipo = (float) $resultadonotaPag['tipo'];
        if ($tipo == 1) {
          $textotipo = "Pagamento á vista";
        } else {
          $textotipo = "Pagamento Parcelado" . $numparcelas . "x";
        }


        $total = (float) $resultadonotaPag['total'];
        $subtotal = (float) $resultadonotaPag['subtotal'];
        $gorjeta = (float) $resultadonotaPag['gorjeta'];
        $dinheiro = (float) $resultadonotaPag['dinheiro'];
        $debito = (float) $resultadonotaPag['debito'];
        $credito = (float) $resultadonotaPag['credito'];
        $pix = (float) $resultadonotaPag['pix'];
        $desconto = (float) $resultadonotaPag['desconto'];

        $valorTotalFinal = $valorTotalFinal + $total;


        $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
        $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
        $totalfinaldebito = $totalfinaldebito + $debito;
        $totalfinalcredito = $totalfinalcredito + $credito;
        $totalfinalpix = $totalfinalpix + $pix;
        $totalfinaldesconto = $totalfinaldesconto + $desconto;



        echo "
<tr>
 <td>$datahora</td>
 
 <td>R$ " . number_format($total, 2, ',', '.') . "</td>
 <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
 <td>R$ " . number_format($dinheiro, 2, ',', '.') . "</td>
 <td>R$ " . number_format($debito, 2, ',', '.') . "</td>
 <td>R$ " . number_format($credito, 2, ',', '.') . "</td>
 <td>R$ " . number_format($pix, 2, ',', '.') . "</td>
 <td>R$ " . number_format($desconto, 2, ',', '.') . "</td>

</tr>";

      }
      echo "<tr>

<td colspan='1' style='text-align:right;'>TOTAL FINAL</td>
<td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
<td>R$ " . number_format($totalfinalsubtotal, 2, ',', '.') . "</td>
<td>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</td>
<td>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</td>
<td>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</td>
<td>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
<td>R$ " . number_format($totalfinaldesconto, 2, ',', '.') . "</td>

</tr>";

      echo "</table></div>";

    }







    break;

  //VALIDACAO PARA PESQUISAR FATURAMENTO EM OUTRAS DATAS
  case 47:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param4);

    $dia_hoje = $partes['2'];
    $mes_hoje = $partes['1'];
    $ano_hoje = $partes['0'];

    if ($param1 == 1) {

      if ($param3 == 0) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 1) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND dinheiro !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 2) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND debito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 3) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND credito !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }

      } else if ($param3 == 4) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND pix !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      } else if ($param3 == 5) {
        if ($param2 == 0) {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
          );
        } else {
          $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE dia = :dia AND mes = :mes AND ano = :ano AND tipo = :tipo AND desconto !=0 ORDER BY cod DESC";
          $paramNotaPag = array(
            ":dia" => $dia_hoje,
            ":mes" => $mes_hoje,
            ":ano" => $ano_hoje,
            ":tipo" => $param2,
          );
        }
      }

      echo "<table class='table table-striped table-sm' style='font-size:10pt; width:100%'>
                        <tr>
                            <td><b>Cod. Nota</b></td>
                            <td><b>Tipo Pagamento</b></td>
                            <td><b>Cliente</b></td>
                           
                            <td><b>Funcionário</b></td>
                            <td><b>Data e Hora</b></td>
                            <td><b>Tipo Venda</b></td>
                             <td><b>Valor Total	</b></td>
                             <td><b>SubTotal</b></td>
                            <td><b>Dinheiro</b></td>
                            <td><b>Débito</b></td>
                            <td><b>Crédito</b></td>
                            <td><b>Pix</b></td>
                            <td><b>Desconto</b></td>
  
                        </tr>
                
            ";

      $textotipo = 0;
      $valorTotalFinal = 0;
      $nomecli = "";

      $totalfinalsubtotal = 0;
      $totalfinaldinheiro = 0;
      $totalfinaldebito = 0;
      $totalfinalcredito = 0;
      $totalfinalpix = 0;
      $totalfinaldesconto = 0;

      $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
      foreach ($dataTableNotaPag as $resultadonotaPag) {
        $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];

        $sqlNota = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
        $paramNota = array(
          ":cod" => $cod_orcamento
        );


        $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
        foreach ($dataTableNota as $resultadonota) {
          $codnota = $resultadonota['cod'];
          $codcli = $resultadonota['usuario'];

          $dia = $resultadonota['dia'];
          $mes = $resultadonota['mes'];
          $ano = $resultadonota['ano'];
          $hora = $resultadonota['hora'];

          $func = $resultadonota['func'];
          $tipo = $resultadonota['tipo_pedido'];


          if ($codcli != 0) {
            $nomeCli = "";
            $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
            $paramCli = array(
              ":id" => $codcli
            );
            $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
            foreach ($dataTableCli as $resultadocli) {
              $nomecli = $resultadocli['nome'];
            }
          } else {
            $nomecli = $resultadonota['nomeCli'];
          }
          $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
          $paramCli = array(
            ":cod" => $func
          );
          $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
          foreach ($dataTableCli as $resultadocli) {
            $nomefunc = $resultadocli['nome'];
          }
          $textotipovenda = "";
          if ($tipo == 1) {
            $textotipovenda = "Venda Expressa";
          } else if ($tipo == 2) {
            $textotipovenda = "Retirada no Balcão";
          } else if ($tipo == 3) {
            $textotipovenda = "Entrega";
          }

          $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;

        }

        $numparcelas = (float) $resultadonotaPag['numparcelas'];
        $tipo = (float) $resultadonotaPag['tipo'];
        if ($tipo == 1) {
          $textotipo = "Pagamento á vista";
        } else {
          $textotipo = "Pagamento Parcelado" . $numparcelas . "x";
        }


        $total = (float) $resultadonotaPag['total'];
        $subtotal = (float) $resultadonotaPag['subtotal'];
        $gorjeta = (float) $resultadonotaPag['gorjeta'];
        $dinheiro = (float) $resultadonotaPag['dinheiro'];
        $debito = (float) $resultadonotaPag['debito'];
        $credito = (float) $resultadonotaPag['credito'];
        $pix = (float) $resultadonotaPag['pix'];
        $desconto = (float) $resultadonotaPag['desconto'];

        $valorTotalFinal = $valorTotalFinal + $total;


        $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
        $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
        $totalfinaldebito = $totalfinaldebito + $debito;
        $totalfinalcredito = $totalfinalcredito + $credito;
        $totalfinalpix = $totalfinalpix + $pix;
        $totalfinaldesconto = $totalfinaldesconto + $desconto;



        echo "<tr>
                      <td>$cod_orcamento</td>
                      <td>$textotipo</td>
                      <td>$nomecli</td>
                      <td>$nomefunc</td>
                      <td>$datahora</td>
                      <td>$textotipovenda</td>
                      
                      <td>R$ " . number_format($total, 2, ',', '.') . "</td>
                      <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
                      <td>R$ " . number_format($dinheiro, 2, ',', '.') . "</td>
                      <td>R$ " . number_format($debito, 2, ',', '.') . "</td>
                      <td>R$ " . number_format($credito, 2, ',', '.') . "</td>
                      <td>R$ " . number_format($pix, 2, ',', '.') . "</td>
                      <td>R$ " . number_format($desconto, 2, ',', '.') . "</td>
                   
                   </tr>";

      }
      echo "<tr>
                
                <td colspan='6' style='text-align:right;'>TOTAL FINAL</td>
                <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
                <td>R$ " . number_format($totalfinalsubtotal, 2, ',', '.') . "</td>
                <td>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</td>
                <td>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</td>
                <td>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</td>
                <td>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
                <td>R$ " . number_format($totalfinaldesconto, 2, ',', '.') . "</td>
             
             </tr>";

      echo "</table>";
    }
    if ($param1 == 2) {
      echo "
        <div class='col-sm-12 col-xl-3'>
                <label>Mês/Ano</label>
                <input class='form-control' style='font-size:12pt; padding:20px;' type='month' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m') . "' />
              </div>  
      ";
    }
    if ($param1 == 3) {
      echo "
        <div class='col-sm-12 col-xl-3'>
                <label>Data</label>
                <input  min='2025' max='2050' class='form-control' style='font-size:12pt; padding:20px;' type='number' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y') . "' />
              </div>  
      ";
    }


    break;

  //VALIDACAO PARA A PESQUISA RELATORIO DE FATURAMENTO
  case 48:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param4);

    $mes_hoje = $partes['1'];
    $ano_hoje = $partes['0'];


    if ($param3 == 0) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }

    } else if ($param3 == 1) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND dinheiro !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND tipo = :tipo AND dinheiro !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    } else if ($param3 == 2) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND debito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND tipo = :tipo AND debito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    } else if ($param3 == 3) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND credito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND tipo = :tipo AND credito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }

    } else if ($param3 == 4) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND pix !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE mes = :mes AND ano = :ano AND tipo = :tipo AND pix !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    } else if ($param3 == 5) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND desconto !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano AND tipo = :tipo AND desconto !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    }

    echo "<table class='table table-striped table-sm' style='font-size:10pt; width:100%'>
              <tr>
                  <td><b>Data e Hora</b></td>
                   <td><b>Valor Total	</b></td>
                   <td><b>SubTotal</b></td>
                  <td><b>Dinheiro</b></td>
                  <td><b>Débito</b></td>
                  <td><b>Crédito</b></td>
                  <td><b>Pix</b></td>
                  <td><b>Desconto</b></td>

              </tr>
      
  ";

    $textotipo = 0;
    $valorTotalFinal = 0;
    $nomecli = "";

    $totalfinalsubtotal = 0;
    $totalfinaldinheiro = 0;
    $totalfinaldebito = 0;
    $totalfinalcredito = 0;
    $totalfinalpix = 0;
    $totalfinaldesconto = 0;

    $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
    foreach ($dataTableNotaPag as $resultadonotaPag) {
      $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];

      $sqlNota = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
      $paramNota = array(
        ":cod" => $cod_orcamento
      );


      $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
      foreach ($dataTableNota as $resultadonota) {
        $codnota = $resultadonota['cod'];
        $codcli = $resultadonota['usuario'];

        $dia = $resultadonota['dia'];
        $mes = $resultadonota['mes'];
        $ano = $resultadonota['ano'];
        $hora = $resultadonota['hora'];

        $func = $resultadonota['func'];
        $tipo = $resultadonota['tipo_pedido'];


        if ($codcli != 0) {
          $nomeCli = "";
          $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
          $paramCli = array(
            ":id" => $codcli
          );
          $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
          foreach ($dataTableCli as $resultadocli) {
            $nomecli = $resultadocli['nome'];
          }
        } else {
          $nomecli = $resultadonota['nomeCli'];
        }
        $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramCli = array(
          ":cod" => $func
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomefunc = $resultadocli['nome'];
        }
        $textotipovenda = "";
        if ($tipo == 1) {
          $textotipovenda = "Venda Expressa";
        } else if ($tipo == 2) {
          $textotipovenda = "Retirada no Balcão";
        } else if ($tipo == 3) {
          $textotipovenda = "Entrega";
        }

        $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;

      }

      $numparcelas = (float) $resultadonotaPag['numparcelas'];
      $tipo = (float) $resultadonotaPag['tipo'];
      if ($tipo == 1) {
        $textotipo = "Pagamento á vista";
      } else {
        $textotipo = "Pagamento Parcelado" . $numparcelas . "x";
      }


      $total = (float) $resultadonotaPag['total'];
      $subtotal = (float) $resultadonotaPag['subtotal'];
      $gorjeta = (float) $resultadonotaPag['gorjeta'];
      $dinheiro = (float) $resultadonotaPag['dinheiro'];
      $debito = (float) $resultadonotaPag['debito'];
      $credito = (float) $resultadonotaPag['credito'];
      $pix = (float) $resultadonotaPag['pix'];
      $desconto = (float) $resultadonotaPag['desconto'];

      $valorTotalFinal = $valorTotalFinal + $total;


      $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
      $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
      $totalfinaldebito = $totalfinaldebito + $debito;
      $totalfinalcredito = $totalfinalcredito + $credito;
      $totalfinalpix = $totalfinalpix + $pix;
      $totalfinaldesconto = $totalfinaldesconto + $desconto;



      echo "<tr>
            <td>$datahora</td>
            
            <td>R$ " . number_format($total, 2, ',', '.') . "</td>
            <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
            <td>R$ " . number_format($dinheiro, 2, ',', '.') . "</td>
            <td>R$ " . number_format($debito, 2, ',', '.') . "</td>
            <td>R$ " . number_format($credito, 2, ',', '.') . "</td>
            <td>R$ " . number_format($pix, 2, ',', '.') . "</td>
            <td>R$ " . number_format($desconto, 2, ',', '.') . "</td>
         
         </tr>";

    }
    echo "<tr>
      
      <td colspan='1' style='text-align:right;'>TOTAL FINAL</td>
      <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinalsubtotal, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinaldesconto, 2, ',', '.') . "</td>
   
   </tr>";

    echo "</table>";

    break;
  //relatorio para faturamento anual PARA FILTRO PARA O ANO
  case 49:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param4);

    $ano_hoje = $partes['0'];


    if ($param3 == 0) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND tipo = :tipo ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }

    } else if ($param3 == 1) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND dinheiro !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND tipo = :tipo AND dinheiro !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    } else if ($param3 == 2) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND debito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND tipo = :tipo AND debito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    } else if ($param3 == 3) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND credito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND tipo = :tipo AND credito !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }

    } else if ($param3 == 4) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND pix !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE ano = :ano AND tipo = :tipo AND pix !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    } else if ($param3 == 5) {
      if ($param2 == 0) {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND desconto !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
        );
      } else {
        $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  ano = :ano AND tipo = :tipo AND desconto !=0 ORDER BY cod DESC";
        $paramNotaPag = array(
          ":ano" => $ano_hoje,
          ":tipo" => $param2,
        );
      }
    }

    echo "<table class='table table-striped table-sm' style='font-size:10pt; width:100%'>
              <tr>
                  <td><b>Data e Hora</b></td>
                   <td><b>Valor Total	</b></td>
                   <td><b>SubTotal</b></td>
                  <td><b>Dinheiro</b></td>
                  <td><b>Débito</b></td>
                  <td><b>Crédito</b></td>
                  <td><b>Pix</b></td>
                  <td><b>Desconto</b></td>

              </tr>
      
  ";

    $textotipo = 0;
    $valorTotalFinal = 0;
    $nomecli = "";

    $totalfinalsubtotal = 0;
    $totalfinaldinheiro = 0;
    $totalfinaldebito = 0;
    $totalfinalcredito = 0;
    $totalfinalpix = 0;
    $totalfinaldesconto = 0;

    $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
    foreach ($dataTableNotaPag as $resultadonotaPag) {
      $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];

      $sqlNota = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
      $paramNota = array(
        ":cod" => $cod_orcamento
      );


      $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
      foreach ($dataTableNota as $resultadonota) {
        $codnota = $resultadonota['cod'];
        $codcli = $resultadonota['usuario'];

        $dia = $resultadonota['dia'];
        $mes = $resultadonota['mes'];
        $ano = $resultadonota['ano'];
        $hora = $resultadonota['hora'];

        $func = $resultadonota['func'];
        $tipo = $resultadonota['tipo_pedido'];


        if ($codcli != 0) {
          $nomeCli = "";
          $sqlCli = "SELECT * FROM clientes WHERE id = :id ORDER BY id ASC LIMIT 1";
          $paramCli = array(
            ":id" => $codcli
          );
          $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
          foreach ($dataTableCli as $resultadocli) {
            $nomecli = $resultadocli['nome'];
          }
        } else {
          $nomecli = $resultadonota['nomeCli'];
        }
        $sqlCli = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramCli = array(
          ":cod" => $func
        );
        $dataTableCli = $banco->ExecuteQuery($sqlCli, $paramCli);
        foreach ($dataTableCli as $resultadocli) {
          $nomefunc = $resultadocli['nome'];
        }
        $textotipovenda = "";
        if ($tipo == 1) {
          $textotipovenda = "Venda Expressa";
        } else if ($tipo == 2) {
          $textotipovenda = "Retirada no Balcão";
        } else if ($tipo == 3) {
          $textotipovenda = "Entrega";
        }

        $datahora = $dia . '/' . $mes . '/' . $ano . ' ' . $hora;

      }

      $numparcelas = (float) $resultadonotaPag['numparcelas'];
      $tipo = (float) $resultadonotaPag['tipo'];
      if ($tipo == 1) {
        $textotipo = "Pagamento á vista";
      } else {
        $textotipo = "Pagamento Parcelado" . $numparcelas . "x";
      }


      $total = (float) $resultadonotaPag['total'];
      $subtotal = (float) $resultadonotaPag['subtotal'];
      $gorjeta = (float) $resultadonotaPag['gorjeta'];
      $dinheiro = (float) $resultadonotaPag['dinheiro'];
      $debito = (float) $resultadonotaPag['debito'];
      $credito = (float) $resultadonotaPag['credito'];
      $pix = (float) $resultadonotaPag['pix'];
      $desconto = (float) $resultadonotaPag['desconto'];

      $valorTotalFinal = $valorTotalFinal + $total;


      $totalfinalsubtotal = $totalfinalsubtotal + $subtotal;
      $totalfinaldinheiro = $totalfinaldinheiro + $dinheiro;
      $totalfinaldebito = $totalfinaldebito + $debito;
      $totalfinalcredito = $totalfinalcredito + $credito;
      $totalfinalpix = $totalfinalpix + $pix;
      $totalfinaldesconto = $totalfinaldesconto + $desconto;



      echo "<tr>
            <td>$datahora</td>
            
            <td>R$ " . number_format($total, 2, ',', '.') . "</td>
            <td>R$ " . number_format($subtotal, 2, ',', '.') . "</td>
            <td>R$ " . number_format($dinheiro, 2, ',', '.') . "</td>
            <td>R$ " . number_format($debito, 2, ',', '.') . "</td>
            <td>R$ " . number_format($credito, 2, ',', '.') . "</td>
            <td>R$ " . number_format($pix, 2, ',', '.') . "</td>
            <td>R$ " . number_format($desconto, 2, ',', '.') . "</td>
         
         </tr>";

    }
    echo "<tr>
      
      <td colspan='1' style='text-align:right;'>TOTAL FINAL</td>
      <td>R$ " . number_format($valorTotalFinal, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinalsubtotal, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinaldinheiro, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinaldebito, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinalcredito, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinalpix, 2, ',', '.') . "</td>
      <td>R$ " . number_format($totalfinaldesconto, 2, ',', '.') . "</td>
   
   </tr>";

    echo "</table>";



    break;

  //pagina inicial do filtro para faturamento   
  case 50:
    echo "
  <div class='col-sm-12 col-xl-3'>
          <label>Mês/Ano</label>
          <input onkeyup='validacaorelatorios(51, 0, 0, 0, this.value, 0, 0, 51)' class='form-control' style='font-size:12pt; padding:20px;' type='month' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m') . "' />
        </div>  
";
    echo "
           
                   <div id='ResultadoValidacao51'>
                   </DIV> 
          ";
    break;


  //VALIDACAO PARA GERAR BALANÇO MENSAL
  case 51:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param4);

    $mes_hoje = $partes['1'];
    $ano_hoje = $partes['0'];


    $totalfinaldia = 0;
    $valortotalent = 0;
    $totaldesp = 0;



    //SCRIP PARA BUSCAR FATURAMENTO MENSAL 

    $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano ORDER BY cod DESC";
    $paramNotaPag = array(
      ":mes" => $mes_hoje,
      ":ano" => $ano_hoje
    );

    $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
    foreach ($dataTableNotaPag as $resultadonotaPag) {
      $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];
      //SQL PARA MONTAR FATURAMENTO, DESPESA E SALDO DIÁRIO
      $sqlNota = "SELECT * FROM notas WHERE cod = :cod AND status = 3 ORDER BY cod DESC LIMIT 1";
      $paramNota = array(
        ":cod" => $cod_orcamento,
      );
      $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
      foreach ($dataTableNota as $resultadonota) {
        $totalfinaldia = $totalfinaldia + (float) $resultadonotaPag['total'];
      }
    }

    //SCRIPT PARA BUSCAR VALOR TOTAL EM ESTOQUE
    $sqlNotaEntrada = "SELECT * FROM entradas WHERE  mes = :mes AND ano = :ano ORDER BY cod DESC";
    $paramNotaEntrada = array(
      ":mes" => $mes_hoje,
      ":ano" => $ano_hoje
    );

    $dataTableNotaEntrada = $banco->ExecuteQuery($sqlNotaEntrada, $paramNotaEntrada);
    foreach ($dataTableNotaEntrada as $resultadonotaEntrada) {
      $cod_entrada = (float) $resultadonotaEntrada['cod'];
      //SQL PARA MONTAR FATURAMENTO, DESPESA E SALDO DIÁRIO
      $sqlNota = "SELECT * FROM lista_entradas WHERE cod_entrada = :cod ORDER BY cod DESC LIMIT 1";
      $paramNota = array(
        ":cod" => $cod_entrada,
      );
      $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
      foreach ($dataTableNota as $resultadonota) {
        $valortotalent = $valortotalent + (float) $resultadonota['valor_total'];
      }
    }


    //SCRIPT PARA BUSCAR DESPESAS

    $sqlNotaDesp = "SELECT * FROM financeiro_empresa WHERE  mes = :mes AND ano = :ano ORDER BY id  DESC";
    $paramNotaDesp = array(
      ":mes" => $mes_hoje,
      ":ano" => $ano_hoje
    );

    $dataTableNotaDesp = $banco->ExecuteQuery($sqlNotaDesp, $paramNotaDesp);
    foreach ($dataTableNotaDesp as $resultadonotaDesp) {

      $totaldesp = $totaldesp + (float) $resultadonotaDesp['valor'];

    }

    echo " 
  
<table class='table table-bordered' style='width: 100%;'>  

    <tr>
        <td colspan='' style='text-align: center;'>
            <b>FATURAMENTO</b>
        </td>
        <td colspan='' style='text-align: center;'>
            <b>INVESTIMENTO EM ESTOQUE</b>
        </td>
        <td colspan='' style='text-align: center;'>
            <b>DESPESAS</b>
        </td>
        <td colspan='' style='text-align: center;'>
            <b>LUCRO FINAL</b>
        </td>
    </tr>
    <tr>
        <td colspan='' style='text-align: center;'>
            R$ " . number_format($totalfinaldia, 2, ',', '.') . "
        </td>
        <td colspan='' style='text-align: center;'>
            R$ " . number_format($valortotalent, 2, ',', '.') . "
        </td>
        <td colspan='' style='text-align: center;'>
            R$ " . number_format($totaldesp, 2, ',', '.') . "

        </td>
        <td colspan='' style='text-align: center;'>
            R$ " . number_format($totalfinaldia - $valortotalent - $totaldesp, 2, ',', '.') . "
        </td>
    </tr>
</table>
  ";

    $totalfinaldia = 0;

    $tipopagamentoavista = 0;
    $tipopagamentocrediario = 0;


    $tipopagamentodinheiro = 0;
    $tipopagamentopix = 0;
    $tipopagamentodebito = 0;
    $tipopagamentocredito = 0;
    $tipopagamentodesconto = 0;
    $tipopagamentocrediario = 0;

    //SCRIP PARA BUSCAR FATURAMENTO MENSAL 

    $sqlNotaPag = "SELECT * FROM financeiro_clientes WHERE  mes = :mes AND ano = :ano ORDER BY cod DESC";
    $paramNotaPag = array(
      ":mes" => $mes_hoje,
      ":ano" => $ano_hoje
    );

    $dataTableNotaPag = $banco->ExecuteQuery($sqlNotaPag, $paramNotaPag);
    foreach ($dataTableNotaPag as $resultadonotaPag) {
      $cod_orcamento = (float) $resultadonotaPag['cod_orcamento'];
      //SQL PARA MONTAR FATURAMENTO, DESPESA E SALDO DIÁRIO
      $sqlNota = "SELECT * FROM notas WHERE cod = :cod AND status = 3 ORDER BY cod DESC LIMIT 1";
      $paramNota = array(
        ":cod" => $cod_orcamento,
      );
      $dataTableNota = $banco->ExecuteQuery($sqlNota, $paramNota);
      foreach ($dataTableNota as $resultadonota) {
        $totalfinaldia = $totalfinaldia + (float) $resultadonotaPag['total'];

        if ($resultadonotaPag['dinheiro'] != 0) {
          $tipopagamentodinheiro = $tipopagamentodinheiro + ($resultadonotaPag['dinheiro'] - $resultadonotaPag['gorjeta']);
        }
        if ($resultadonotaPag['debito'] != 0) {
          $tipopagamentodebito = $tipopagamentodebito + (float) $resultadonotaPag['debito'];
        }
        if ($resultadonotaPag['credito'] != 0) {
          $tipopagamentocredito = $tipopagamentocredito + (float) $resultadonotaPag['credito'];
        }
        if ($resultadonotaPag['pix'] != 0) {
          $tipopagamentopix = $tipopagamentopix + (float) $resultadonotaPag['pix'];
        }
        if ($resultadonotaPag['desconto'] != 0) {
          $tipopagamentodesconto = $tipopagamentodesconto + (float) $resultadonotaPag['desconto'];
        }

        if ($resultadonotaPag['tipo'] == 1) {
          $tipopagamentoavista = $tipopagamentoavista + (float) $resultadonotaPag['total'];

        } else {
          $tipopagamentocrediario = $tipopagamentocrediario + (float) $resultadonotaPag['total'];
        }


      }
    }


    echo "<table class='table table-bordered' style='width: 100%;'>  

            <tr>
                <td colspan='8' style='text-align: center;'>
                    <b>Faturamento</b>
                </td>
            </tr>
            <tr style='text-align: center;'>
                <td rowspan='2'>
                    </br>
                    Total 
                </td>
                <td colspan='2'>
                    Forma de Pagamento
                </td> 
                <td colspan='5'>
                    Tipo Pagamento
                </td>

            </tr>
        <tr>
            <th>Á vista</th>
            <th>Crediário</th>
            <th>Dinheiro</th>
            <th>Débito</th>
            <th>Crédito</th>
            <th>Pix</th>
            <th>Desconto</th>
        </tr>   
        <tr>
            <td>
                   R$ " . number_format($totalfinaldia, 2, ',', '.') . "
            </td>
            <td>
                   R$ " . number_format($tipopagamentoavista, 2, ',', '.') . "
            </td>
            <td>       R$ " . number_format($tipopagamentocrediario, 2, ',', '.') . "
            </td>
            <td>       R$ " . number_format($tipopagamentodinheiro, 2, ',', '.') . "
            </td>
            <td>       R$ " . number_format($tipopagamentodebito, 2, ',', '.') . "
            </td>
            <td>       R$ " . number_format($tipopagamentocredito, 2, ',', '.') . "
            </td>
            
            <td>       R$ " . number_format($tipopagamentopix, 2, ',', '.') . "
            </td>
            <td>       R$ " . number_format($tipopagamentodesconto, 2, ',', '.') . "
            </td>
            
        </tr>   
            
    </table>

    <h3>Crediários em Aberto</h3>
    <table class='table table-bordered' style='width: 100%;'>
    
    ";
    $mes = $mes_hoje;
    $ano = $ano_hoje;

    echo "
			<tr style='text-align:center;'>
				<td colspan='2'><b>nº de Contas a Receber</b></td>
				<td colspan='2'><b>Total em Crediário</b></td>
				<td colspan='2'><b>Total Recebido</b></td>
				<td colspan='2'><b>Total a Receber</b></td>
                                </tr>
                                ";


    $contadorcontas = 0;
    $totalemcrediario = 0;
    $valortotalparcelas = 0;

    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 AND mes = $mes_hoje AND ano = $ano_hoje ORDER BY cod ASC");
    // Exibe todos os valores encontrados

    while ($financeirocli = mysqli_fetch_object($sql)) {
      $contadorparcelas = 0;
      $codfin = $financeirocli->cod;
      $codnota = $financeirocli->cod_orcamento;
      $valortotalfinanceiro = (float) $financeirocli->total;
      $diaatual = date('d');
      $anoatual = date('Y');
      $mesatual = date('m');

      $diapag = 0;
      $mespag = 0;
      $anopag = 0;
      $codparcela = 0;

      $troco = $financeirocli->gorjeta;
      $pagamentototal = (float) $financeirocli->total;
      $pagamentototal2 = $pagamentototal;
      $numparcelas = (float) $financeirocli->numparcelas;
      if ($numparcelas != 0) {
        $valorparcela = $pagamentototal / $numparcelas;
      } else {
        $valorparcela = $pagamentototal;
      }
      $valorparcela = number_format($valorparcela, 2, ',', '.');
      $valorparcela2 = $valorparcela;
      $pagamentototal = number_format($pagamentototal, 2, ',', '.');
      $tipopag1 = $financeirocli->tipo;
      $tipopag2 = $financeirocli->tipopag;

      $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
      // Exibe todos os valores encontrados
      while ($finpar = mysqli_fetch_object($sqlPar)) {
        $contadorparcelas++;
      }

      if ($numparcelas != $contadorparcelas) {
        $contadorcontas++;
        $totalemcrediario = $totalemcrediario + $valortotalfinanceiro;

        $sqlPar = mysqli_query($conn, "SELECT * FROM pag_par_pro WHERE financeiro_pac = $codfin ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($finpar = mysqli_fetch_object($sqlPar)) {
          $valortotalparcelas = $valortotalparcelas + (float) $finpar->valor;
          $diapag = $finpar->dia;
          $mespag = $finpar->mes;
          $anopag = $finpar->ano;
        }


        $textostatus = "";
        if ($diapag == 0) {
          $textostatus = " Nenhuma Parcela Paga";
        }
      }
    }
    $VALORTOTALARECEBER = 0;
    if ($contadorcontas != 0) {
      echo " <tr style='text-align:center;'>
				
				<td colspan='2'>$contadorcontas</td>
				<td colspan='2'>" . number_format($totalemcrediario, 2, ',', '.') . "</td>
				<td colspan='2'>" . number_format($valortotalparcelas, 2, ',', '.') . "</td>
				<td colspan='2'>" . number_format($totalemcrediario - $valortotalparcelas, 2, ',', '.') . "</td>
				
                                </tr>
                                ";
      $VALORTOTALARECEBER = $totalemcrediario - $valortotalparcelas;
    }

    echo "<tr>
      <th>Cód. Nota</th>
      <th>Cliente</th>
      <th>Qtd Pedidos</th>
      <th>Valor Total</th>
      <th>Recebido</th>
      <th>A Receber</th>
      <th>Pagamento</th>
      <th>Status</th>
    </tr>";

    $sql = mysqli_query($conn, "SELECT * FROM financeiro_clientes WHERE tipo = 2 AND tipopag = 2 AND mes = $mes AND ano = $ano ORDER BY cod ASC");
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
      if ($numparcelas != 0) {
        $valorparcela = $pagamentototal / $numparcelas;
      } else {
        $valorparcela = $pagamentototal;
      }
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
        echo "
									<tr style='text-align:center;'>
										<td>$codnota</td>
										<td>$nomecli</td>
										<td>$qtdpedidos</td>
										<td>$pagamentototal</td>
										<td>$valortotalparcelas</td>
										<td>$areceber</td>
										<td>$textopagamentotipo</td>
										<td><span style='color:red'>Crediário em Aberto.";
        if ($diapag != 0) {
          echo " 
                                                                                    Ultimo Pagamento:$diapag/$mespag/$anopag</span> ";
        } else {
          echo $textostatus;
        }
        echo "      </td>
										</td>
									</tr>
            ";
      }
    }
    echo "
  </table>

  

  <h3>Entradas</h3>
  <table class='table table-bordered' style='width: 100%;'>
    
    <tr>
      <th></th>
      <th>Qtd</th>
      <th>Valor Total</th>
    </tr>
    ";
    $qtdtotalentfinal = 0;
    $qtdtotalsaifinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;
    for ($i = 1; $i <= 31; $i++) {
      $qtdtotalent = 0;
      $valortotalent = 0;
      $qtdtotalsai = 0;
      $valortotalsai = 0;
      $trueorfalsecat = 0;
      $trueorfalsecat = 1;

      $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE dia = $i AND mes = $mes_hoje AND ano = $ano_hoje ORDER BY cod ASC");
      while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
        $cod_entrada = $listent->cod_entrada;
        $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE cod = $cod_entrada ORDER BY cod ASC LIMIT 1");
        while ($ent = mysqli_fetch_object($sqlEntradas)) {
          $qtdtotalent = $qtdtotalent + $listent->qtd;
          $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;

          $valortotalent = $valortotalent + $listent->valor_total;
          $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
        }
      }
      $saldoqtd = $qtdtotalent;

      if ($saldoqtd != 0) {
        echo "
                                                                                                                                                                <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'>Dia " . $i . "</td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                             </tr>    ";
      }
    }
    $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;

    echo "                                                                           <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                             </tr>    ";

    echo "      
                                                                                                    ";


    echo " 
  </table>

  <h3>Despesas</h3>
  <table class='table table-bordered' style='width: 100%;'>
    <tr>
      <th>Descrição Categoria</th>
      <th>Total</th>
    </tr>
    ";
    $sqlCategorias = "SELECT * FROM lc_cat WHERE cod_usu = :cod ORDER BY id ASC";
    $paramCategorias = array(
      ":cod" => 1
    );
    $TOTALFINALCATEGORIAS = 0;
    $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
    foreach ($dataTableCategorias as $resultadocategorias) {
      $idcat = $resultadocategorias['id'];
      $nomecat = $resultadocategorias['nome'];
      $sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE mes = $mes AND ano = $ano AND cat = $idcat ORDER BY id ASC");
      // Exibe todos os valores encontrados
      $totalfinaldiacat = 0;
      while ($dividadia = mysqli_fetch_object($sqlDividasDia)) {
        $cod = $dividadia->id;
        $pontos = ',';
        $result = str_replace($pontos, "", $dividadia->valor);
        $valor_total = (float) $result;
        $total = $valor_total;

        $TOTALFINALCATEGORIAS = $TOTALFINALCATEGORIAS + $total;
        $totalfinaldiacat = $totalfinaldiacat + $total;
      }

      echo "<tr>
                    <td>$nomecat</td>
                    <td>R$ " . number_format($totalfinaldiacat, 2, ',', '.') . "</td>
                  </tr> ";
      $totalfinaldiacat = 0;
    }
    echo "
    <tr><th>Total Final:</th><th>R$ " . number_format($TOTALFINALCATEGORIAS, 2, ',', '.') . "</th></tr>
  </table>";


    break;

  //VALIDACAO PARA GERAR RELATORIO DE SALDO DE PRODUTO
  case 52:
    echo "                   <div class='row' style='margin-bottom:5px;'>
                                                <input name='txtAtaSD' id='txtAtaSD' type='hidden' value='txtAtaSD'/>
								<div class='col-12 col-md-12' id=''>
                                                                <label style='color:#337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtCategoriaSD'><span></span> Categorias:</label>
                                                                    <select Onchange='validacaorelatorios(53, 0, 0, 0, this.value, 0, 0, 53)' href='javascript: func' style='height: 82px; font-size: 15pt; width: 100%; ' class='form-control' id='txtBairrosFiltros' name='txtBairrosFiltros' onchange=''>
                                                                    <option value='0'>Todas Categorias</option>
                                                                    ";
    $cod_orgao = $_SESSION['cod_orgaoF'];
    // $sqlFor2 = mysqli_query($conn, "SELECT * FROM categoria_produto WHERE cod_orgao = $cod_orgao ORDER BY cod ASC");
    $sqlFor = "SELECT * FROM categoriaserfin ORDER BY cod ASC";


    $dataTableFor = $banco->ExecuteQuery($sqlFor);
    foreach ($dataTableFor as $resultadofor) {
      $nomefornecedor = $resultadofor['nome'];
      echo "  <option value='" . $resultadofor['cod'] . "'>" . $nomefornecedor . ".</option>";
    }
    echo "             
                                                                    </select>
                                                                </div>
							
                                                                
                                                                		
                                                      
                                                     
							<div class='col-12 col-md-12' id='ResultadoValidacao53'>

                                                        </div>
							
							
                                       ";



    break;

  //VALIDACAO PARA GERAR RELATORIO DE SALDO DE PRODUTO - PARTE RESULTADO CONSULTA
  case 53:
    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $categoria = $param4;

    $ordem = 0;

    $valortotalfinalporatasai = 0;
    $valortotalfinalporataqtd = 0;
    $codfornecedor2 = 0;


    echo "<table class='table table-light table-bordered'>

    <tr style='text-align:center;'>
        <td><b>Ordem</b></td>
        <td style='width: 40%;'><b>Produto</b></td>
        <td><b>Categoria</b></td>
        <td><b>Valor Unt</b></td>
        <td><b>Qtd</b></td>
        <td><b>Valor Total</b></td>
        
    </tr>";



    $valorunt = 0;
    $valortotalporcat = 0;
    $valortotalfinal = 0;

    $truorfalsevalorfornecedor = 0;

    $qtdtotalentfinal = 0;
    $qtdtotalsaifinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;
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


    if ($categoria == 0) {

      $sqlProdutos = "SELECT * FROM servicos ORDER BY cod ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    } else {
      $sqlProdutos = "SELECT * FROM servicos WHERE categoria = :categoria ORDER BY nome ASC";
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
      $ordem++;

      $codproduto = $resultadoprodutos['cod'];
      $nomeproduto = $resultadoprodutos['nome'];
      $codbuscaproduto = $resultadoprodutos['codbusca'];
      $codbarraproduto = $resultadoprodutos['codbarra'];
      $apresentacao = $resultadoprodutos['apresentacao'];
      $qtd = $resultadoprodutos['qtd'];
      if ($qtd <= 0) {
        $qtd = 0;
      }
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


      $valortotalporcat = $valorunt * $qtd;
      $valortotalfinal = $valortotalfinal + $valortotalporcat;
      if ($qtd == null || $qtd < 0) {
        $qtd = 0;
      }

      $totalporproduto = 0;
      $totalporproduto = $qtd * $valorunt;
      echo " 
                        <tr style='text-align:center;'>
                            <td>$ordem</td>
			     
                            <td>$nomeproduto </br>
                                <B>Cod. Busca:</b> $codbuscaproduto / <B>Cod. Barra:</b> $codbarraproduto
                                </td>

                                
                            <td>$nomecategoria </br>
                            
                            <td>R$ " . number_format($valorunt, 2, ',', '.') . " </br>
                            
                            
                            <td>$qtd </br>
                            
                            
                            
                            <td>R$ " . number_format($totalporproduto, 2, ',', '.') . " </br>
                             ";
      $totalporproduto = 0;
    }
    $valortotalporcat = 0;
    echo " 
                        <tr>
                          
                            <td colspan='5' style='text-align:rigth;'>Total Final</td>
                            <td>R$ " . number_format($valortotalfinal, 2, ',', '.') . "</td>
                                   </tr>

                                            ";

    $totalporprodutoent = 0;
    $totalporprodutosaid = 0;



    $valortotalfinalporata = 0;
    $totalfinalporata = 0;
    $valortotalfinalporatasai = 0;
    $valortotalfinalporataqtd = 0;



    $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;


    echo "</table>";

    break;
  //FILTRO PARA LISTAR ENTRADAS 
  case 54:

    $dia_hoje = date('d');
    $mes_hoje = date('m');
    $ano_hoje = date('Y');
    echo "
          <div class='row' style='background-color:#fff; margin-bottom:10px;'>
            <div class='col-sm-12 col-xl-12'>
              <label>Deseja consultar qual período</label>
              <select onchange='validacaorelatorios(55, this.value, 0, 0, 0, 0, 0, 55)' name='txtConsultaPeriodo' id='txtConsultaPeriodo' class='form-control' style='font-size:12pt; padding:20px;'>
                  <option value='1'>Dia</option>
                  <option value='2'>Mes</option>
                  <option value='3'>Ano</option>
              </select>
            </div>  
        

          </div>  
          <div id='ResultadoValidacao55'>
            <div class='col-sm-12 col-xl-12'>
                          <label>Data</label>
                          <input onkeyup='validacaorelatorios(56, txtConsultaPeriodo.value, 0, 0, this.value, 0, 0, 56)' class='form-control' style='font-size:12pt; padding:20px;' type='date' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m-d') . "' />
                        </div>  
                        <div id='ResultadoValidacao56'> 
                        ";


    echo "<h3 style='width: 100%;'>Lista de Entradas: $dia_hoje/$mes_hoje/$ano_hoje</h3>
            <table class='table table-bordered' style='width:100%; font-size:14pt;'>
                                                                <tr style='text-align:center;'>
                                                                    <td><b>Cod. Nota</b></td>
                                                                    <td colspan=''><b>nº Nota Fiscal</b></td>
                                                                    <td colspan=''><b>Funcionário</b></td>
                                                                    <td colspan=''><b>Qtd</b></td>
                                                                    <td colspan=''><b>Valor Total</b></td>
                                                                    <td colspan=''><b></b></td>
                                                                    
                                                                </tr>

                                                                ";
    $cod_orgao = $_SESSION['cod_orgaoF'];
    $qtdtotalentfinal = 0;
    $qtdtotalsaifinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;

    $saldoqtd = 0;
    $qtdtotalent = 0;
    $qtdtotalsai = 0;
    $valortotalent = 0;
    $qtdtotalentfinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;
    $saldoqtdfinal = 0;
    //  $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE dia = $dia_hoje AND mes = $mes_hoje AND ano = $ano_hoje AND ata_pregao = $codentrada ORDER BY cod ASC");
    $sqlEntradas = "SELECT * FROM entradas WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod ASC";
    $paramEntradas = array(
      ":dia" => $dia_hoje,
      ":mes" => $mes_hoje,
      ":ano" => $ano_hoje
    );
    $dataTableEntradas = $banco->ExecuteQuery($sqlEntradas, $paramEntradas);
    foreach ($dataTableEntradas as $resultadoentradas) {
      $codentrada2 = $resultadoentradas['cod'];
      $notafiscal = $resultadoentradas['n_notafiscal'];
      $fornecedor = $resultadoentradas['fornecedor'];
      $cod_funcionario = $resultadoentradas['cod_funcionario'];
      //$sqlFornecedor = mysqli_query($conn, "SELECT * FROM fornecedores WHERE cod = $fornecedor ORDER BY cod ASC LIMIT 1");
      $nomefornecedor = "";
      $sqlFornecedores = "SELECT * FROM fornecedores WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramFornecedores = array(
        ":cod" => $fornecedor
      );
      $dataTableFornecedores = $banco->ExecuteQuery($sqlFornecedores, $paramFornecedores);
      foreach ($dataTableFornecedores as $resultadofornecedores) {
        $codfornecedor = $resultadofornecedores['cod'];
        $nomefornecedor = $resultadofornecedores['descricao'];
      }

      //$sqlFuncionario = mysqli_query($conn, "SELECT * FROM usuarios WHERE cod = $cod_funcionario ORDER BY cod ASC LIMIT 1");
      $nomefuncionario = "";
      $sqlUsu = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
      $paramUsu = array(
        ":cod" => $cod_funcionario
      );
      $dataTableUsu = $banco->ExecuteQuery($sqlUsu, $paramUsu);
      foreach ($dataTableUsu as $resultadousu) {
        $nomefuncionario = $resultadousu['nome'];
      }
      $qtdtotalent = 0;
      $valortotalent = 0;
      $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE cod_entrada = $codentrada2 ORDER BY cod ASC");
      while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
        $qtdtotalent = $qtdtotalent + $listent->qtd;
        $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;
        $valortotalent = $valortotalent + $listent->valor_total;
        $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
      }
      echo "                       <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:5%;'><b>$codentrada2</b>.</td>
                                                                                             <td>$notafiscal</td>
                                                                                                 
                                                                                             <td style='width:40%;'>$nomefuncionario</td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                             <td><a style='font-size:14pt;' target='_BLANK' href='?pagina=entradas&cod=$codentrada2' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-play'></span>Ver Tudo</a></td>
                                                                              </tr>";
    }
    echo "<tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td colspan='3' style='width:5%; text-align:rigth;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                            <td></td>
                                                                                            </tr></table>";

    echo " 
                        </div>
          </div>
      ";

    break;

  //RESULTADO FILTRO PARA LISTAR ENTRADAS
  case 55:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];

    if ($param1 == 1) {
      $dia_hoje = date("d");
      $mes_hoje = date("m");
      $ano_hoje = date("Y");
      echo "
                <div class='col-sm-12 col-xl-12'>
                        <label>Data</label>
                        <input onkeyup='validacaorelatorios(56, txtConsultaPeriodo.value, 0, 0, this.value, 0, 0, 56)' class='form-control' style='font-size:12pt; padding:20px;' type='date' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m-d') . "' />
                      </div>  
                       <div id='ResultadoValidacao56'> ";


      echo "<h3 style='width: 100%;'>Lista de Entradas: $dia_hoje/$mes_hoje/$ano_hoje</h3>
            <table class='table table-bordered' style='width:100%; font-size:14pt;'>
                                                                <tr style='text-align:center;'>
                                                                    <td><b>Cod. Nota</b></td>
                                                                    <td colspan=''><b>nº Nota Fiscal</b></td>
                                                                    <td colspan=''><b>Funcionário</b></td>
                                                                    <td colspan=''><b>Qtd</b></td>
                                                                    <td colspan=''><b>Valor Total</b></td>
                                                                    <td colspan=''><b></b></td>
                                                                    
                                                                </tr>

                                                                ";
      $cod_orgao = $_SESSION['cod_orgaoF'];
      $qtdtotalentfinal = 0;
      $qtdtotalsaifinal = 0;
      $valortotalentfinal = 0;
      $valortotalsaifinal = 0;

      $saldoqtd = 0;
      $qtdtotalent = 0;
      $qtdtotalsai = 0;
      $valortotalent = 0;
      $qtdtotalentfinal = 0;
      $valortotalentfinal = 0;
      $valortotalsaifinal = 0;
      $saldoqtdfinal = 0;
      //  $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE dia = $dia_hoje AND mes = $mes_hoje AND ano = $ano_hoje AND ata_pregao = $codentrada ORDER BY cod ASC");
      $sqlEntradas = "SELECT * FROM entradas WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod ASC";
      $paramEntradas = array(
        ":dia" => $dia_hoje,
        ":mes" => $mes_hoje,
        ":ano" => $ano_hoje
      );
      $dataTableEntradas = $banco->ExecuteQuery($sqlEntradas, $paramEntradas);
      foreach ($dataTableEntradas as $resultadoentradas) {
        $codentrada2 = $resultadoentradas['cod'];
        $notafiscal = $resultadoentradas['n_notafiscal'];
        $fornecedor = $resultadoentradas['fornecedor'];
        $cod_funcionario = $resultadoentradas['cod_funcionario'];
        //$sqlFornecedor = mysqli_query($conn, "SELECT * FROM fornecedores WHERE cod = $fornecedor ORDER BY cod ASC LIMIT 1");
        $nomefornecedor = "";
        $sqlFornecedores = "SELECT * FROM fornecedores WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramFornecedores = array(
          ":cod" => $fornecedor
        );
        $dataTableFornecedores = $banco->ExecuteQuery($sqlFornecedores, $paramFornecedores);
        foreach ($dataTableFornecedores as $resultadofornecedores) {
          $codfornecedor = $resultadofornecedores['cod'];
          $nomefornecedor = $resultadofornecedores['descricao'];
        }

        //$sqlFuncionario = mysqli_query($conn, "SELECT * FROM usuarios WHERE cod = $cod_funcionario ORDER BY cod ASC LIMIT 1");
        $nomefuncionario = "";
        $sqlUsu = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramUsu = array(
          ":cod" => $cod_funcionario
        );
        $dataTableUsu = $banco->ExecuteQuery($sqlUsu, $paramUsu);
        foreach ($dataTableUsu as $resultadousu) {
          $nomefuncionario = $resultadousu['nome'];
        }
        $qtdtotalent = 0;
        $valortotalent = 0;
        $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE cod_entrada = $codentrada2 ORDER BY cod ASC");
        while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
          $qtdtotalent = $qtdtotalent + $listent->qtd;
          $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;
          $valortotalent = $valortotalent + $listent->valor_total;
          $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
        }
        echo "                       <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:5%;'><b>$codentrada2</b>.</td>
                                                                                             <td>$notafiscal</td>
                                                                                                 
                                                                                             <td style='width:40%;'>$nomefuncionario</td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                             <td><a style='font-size:14pt;' target='_BLANK' href='?pagina=entradas&cod=$codentrada2' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-play'></span>Ver Tudo</a></td>
                                                                              </tr>";
      }
      echo "<tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td colspan='3' style='width:5%; text-align:rigth;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                            <td></td>
                                                                                            </tr></table>";
      echo " 
                       </div>
              ";
    }

    if ($param1 == 2) {
      echo "
      <div class='col-sm-12 col-xl-12'>
              <label>Mês/Ano</label>
              <input onkeyup='validacaorelatorios(56, txtConsultaPeriodo.value, 0, 0, this.value, 0, 0, 56)' class='form-control' style='font-size:12pt; padding:20px;' type='month' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m') . "' />
            </div>  
  
                       <div id='ResultadoValidacao56'> 
                       </div>
              ";
    }

    if ($param1 == 3) {
      echo "
                    <div class='col-sm-12 col-xl-12'>
              <label>Ano</label>
              <input onkeyup='validacaorelatorios(56, txtConsultaPeriodo.value, 0, 0, this.value, 0, 0, 56)'  min='2025' max='2050' class='form-control' style='font-size:12pt; padding:20px;' type='number' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y') . "' />
            </div>  
  
                       <div id='ResultadoValidacao56'> 
                       </div>
              ";
    }
    break;
  //RESULTADO VALIDACAO PARA DATA PARA PESQUISA DE ENTRADAS
  case 56:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param4);



    if ($param1 == 1) {
      $dia_hoje = $partes['2'];
      $mes_hoje = $partes['1'];
      $ano_hoje = $partes['0'];


      echo "<h3 style='width: 100%;'>Lista de Entradas: $dia_hoje/$mes_hoje/$ano_hoje</h3>
            <table class='table table-bordered' style='width:100%; font-size:14pt;'>
                                                                <tr style='text-align:center;'>
                                                                    <td><b>Cod. Nota</b></td>
                                                                    <td colspan=''><b>nº Nota Fiscal</b></td>
                                                                    <td colspan=''><b>Funcionário</b></td>
                                                                    <td colspan=''><b>Qtd</b></td>
                                                                    <td colspan=''><b>Valor Total</b></td>
                                                                    <td colspan=''><b></b></td>
                                                                    
                                                                </tr>

                                                                ";
      $cod_orgao = $_SESSION['cod_orgaoF'];
      $qtdtotalentfinal = 0;
      $qtdtotalsaifinal = 0;
      $valortotalentfinal = 0;
      $valortotalsaifinal = 0;

      $saldoqtd = 0;
      $qtdtotalent = 0;
      $qtdtotalsai = 0;
      $valortotalent = 0;
      $qtdtotalentfinal = 0;
      $valortotalentfinal = 0;
      $valortotalsaifinal = 0;
      $saldoqtdfinal = 0;
      //  $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE dia = $dia_hoje AND mes = $mes_hoje AND ano = $ano_hoje AND ata_pregao = $codentrada ORDER BY cod ASC");
      $sqlEntradas = "SELECT * FROM entradas WHERE dia = :dia AND mes = :mes AND ano = :ano  ORDER BY cod ASC";
      $paramEntradas = array(
        ":dia" => $dia_hoje,
        ":mes" => $mes_hoje,
        ":ano" => $ano_hoje
      );
      $dataTableEntradas = $banco->ExecuteQuery($sqlEntradas, $paramEntradas);
      foreach ($dataTableEntradas as $resultadoentradas) {
        $codentrada2 = $resultadoentradas['cod'];
        $notafiscal = $resultadoentradas['n_notafiscal'];
        $fornecedor = $resultadoentradas['fornecedor'];
        $cod_funcionario = $resultadoentradas['cod_funcionario'];
        //$sqlFornecedor = mysqli_query($conn, "SELECT * FROM fornecedores WHERE cod = $fornecedor ORDER BY cod ASC LIMIT 1");
        $nomefornecedor = "";
        $sqlFornecedores = "SELECT * FROM fornecedores WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramFornecedores = array(
          ":cod" => $fornecedor
        );
        $dataTableFornecedores = $banco->ExecuteQuery($sqlFornecedores, $paramFornecedores);
        foreach ($dataTableFornecedores as $resultadofornecedores) {
          $codfornecedor = $resultadofornecedores['cod'];
          $nomefornecedor = $resultadofornecedores['descricao'];
        }

        //$sqlFuncionario = mysqli_query($conn, "SELECT * FROM usuarios WHERE cod = $cod_funcionario ORDER BY cod ASC LIMIT 1");
        $nomefuncionario = "";
        $sqlUsu = "SELECT * FROM usuarios WHERE cod = :cod ORDER BY cod ASC LIMIT 1";
        $paramUsu = array(
          ":cod" => $cod_funcionario
        );
        $dataTableUsu = $banco->ExecuteQuery($sqlUsu, $paramUsu);
        foreach ($dataTableUsu as $resultadousu) {
          $nomefuncionario = $resultadousu['nome'];
        }
        $qtdtotalent = 0;
        $valortotalent = 0;
        $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE cod_entrada = $codentrada2 ORDER BY cod ASC");
        while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
          $qtdtotalent = $qtdtotalent + $listent->qtd;
          $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;
          $valortotalent = $valortotalent + $listent->valor_total;
          $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
        }
        echo "                       <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:5%;'><b>$codentrada2</b>.</td>
                                                                                             <td>$notafiscal</td>
                                                                                                 
                                                                                             <td style='width:40%;'>$nomefuncionario</td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                             <td><a style='font-size:14pt;' target='_BLANK' href='?pagina=entradas&cod=$codentrada2' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-play'></span>Ver Tudo</a></td>
                                                                              </tr>";
      }
      echo "<tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td colspan='3' style='width:5%; text-align:rigth;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                            <td></td>
                                                                                            </tr></table>";

      echo "  
                                                                                                                            </table>
                                                                         ";





    } else if ($param1 == 2) {

      $mes_hoje = $partes['1'];
      $ano_hoje = $partes['0'];



      echo "<h3 style='width: 100%; text-align:center;'><span></span>Mês " . mostraMes($mes_hoje) . "</h3>
                ";

      echo "<table class='table table-bordered' style='width:100%; font-size:14pt;'>
                                                                <tr style='text-align:center;'>
                                                                    <td colspan='3'><b>Entradas</b></td>
                                                                </tr>
                                                                <tr style='text-align:center;'>
                                                                    <td><b></b></td>
                                                                    <td><b>Qtd</b></td>
                                                                    <td><b>Valor Total</b></td>

                                                                </tr>

                                                                ";
      $cod_orgao = $_SESSION['cod_orgaoF'];
      $qtdtotalentfinal = 0;
      $qtdtotalsaifinal = 0;
      $valortotalentfinal = 0;
      $valortotalsaifinal = 0;
      for ($i = 1; $i <= 31; $i++) {
        $qtdtotalent = 0;
        $valortotalent = 0;
        $qtdtotalsai = 0;
        $valortotalsai = 0;
        $trueorfalsecat = 0;
        $trueorfalsecat = 1;

        $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE dia = $i AND mes = $mes_hoje AND ano = $ano_hoje ORDER BY cod ASC");
        while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
          $cod_entrada = $listent->cod_entrada;
          $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE cod = $cod_entrada ORDER BY cod ASC LIMIT 1");
          while ($ent = mysqli_fetch_object($sqlEntradas)) {
            $qtdtotalent = $qtdtotalent + $listent->qtd;
            $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;

            $valortotalent = $valortotalent + $listent->valor_total;
            $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
          }
        }
        $saldoqtd = $qtdtotalent;

        if ($saldoqtd != 0) {
          echo "
                                                                                                                                                                <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'><a style='width:100%;font-size:14pt;' class='btn btn-primary btn-sm' href='javascript: func' onclick='VerPesquisarDia(45, 0, 5, $i, $mes_hoje , parampaganoFIN.value, 0)'>Dia " . $i . ".</a></td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                             </tr>    ";
        }
      }
      $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;

      echo "                                                                           <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                             </tr></table>    ";





    } else if ($param1 == 3) {
      $ano_hoje = $partes['0'];

      echo " 
       <table class='table table-bordered' style='width:100%;font-size:14pt;'>
                                                                <tr style='text-align:center;'>
                                                                    <td colspan='3'><b>Entradas</b></td>
                                                                </tr>
                                                                <tr style='text-align:center;'>
                                                                    <td><b></b></td>
                                                                    <td><b>Qtd</b></td>
                                                                    <td><b>Valor Total</b></td>
                                                                </tr>

                                                                ";
      $cod_orgao = $_SESSION['cod_orgaoF'];
      $qtdtotalentfinal = 0;
      $qtdtotalsaifinal = 0;
      $valortotalentfinal = 0;
      $valortotalsaifinal = 0;
      for ($i = 1; $i <= 12; $i++) {
        $qtdtotalent = 0;
        $valortotalent = 0;
        $qtdtotalsai = 0;
        $valortotalsai = 0;
        $trueorfalsecat = 0;
        $trueorfalsecat = 1;

        $sqlListaEntradas = mysqli_query($conn, "SELECT * FROM lista_entradas WHERE mes = $i AND ano = $ano_hoje ORDER BY cod ASC");
        while ($listent = mysqli_fetch_object($sqlListaEntradas)) {
          $cod_entrada = $listent->cod_entrada;
          $sqlEntradas = mysqli_query($conn, "SELECT * FROM entradas WHERE cod = $cod_entrada ORDER BY cod ASC LIMIT 1");
          while ($ent = mysqli_fetch_object($sqlEntradas)) {
            $qtdtotalent = $qtdtotalent + $listent->qtd;
            $qtdtotalentfinal = $qtdtotalentfinal + $listent->qtd;

            $valortotalent = $valortotalent + $listent->valor_total;
            $valortotalentfinal = $valortotalentfinal + $listent->valor_total;
          }
        }
        $saldoqtd = $qtdtotalent;

        if ($saldoqtd != 0) {

          echo "
                                                                                                                                                                <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'><a href='javascript: func' style='width:100%;font-size:14pt;' class='btn btn-primary btn-sm' onclick='VerPesquisarDia(44, 0, 5, 0, $i, $ano_hoje, 0)'>" . mostraMes($i) . ".</a></td>
                                                                                             <td>$qtdtotalent</td>
                                                                                             <td>R$ " . number_format($valortotalent, 2, ',', '.') . "</td>
                                                                                            </tr>    ";
        }
      }
      $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;

      echo "                                                                           <tr id='ResultadoValidacao888' style='text-align:center;'>
                                                                                            <td style='width:50%;'><b>Total Final</b>.</td>
                                                                                             <td>$qtdtotalentfinal</td>
                                                                                             <td>R$ " . number_format($valortotalentfinal, 2, ',', '.') . "</td>
                                                                                            </tr></table>";
    }

    break;
  //validacao para produtos mais vendidos, filtro
  case 57:


    echo "
            <div class='card' style='width:100%;'>
                <div class='card-body' style='width:100%;'>
                    <div class='row' style='width:100%;'>
                        <div class='col-12 col-md-6' style='text-align: left;'>
                            <div class='form-group label-floating'>
                                <label style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtMesAno' class='control-label'>Selecione mês e o ano</label>
                                <input href='javascript: func' onkeyup='validacaorelatorios(58, this.value, txtCategoriaEd.value, 0, 0, 0, 0, 58)'  style='padding:30px; font-size: 15pt; width: 100%; ' type='month' class='form-control' id='txtDataAnoMes2' name='txtDataAnoMes2' value='" . date('Y-m') . "' >
                            </div>
                        </div>
                        <div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtCategoriaEd' class='control-label'>Selecione Categoria</label>
                             <select href='javascript: func' onchange='validacaorelatorios(58, txtDataAnoMes2.value, this.value, 0, 0, 0, 0, 58)'  style='height:92px; font-size: 15pt; width: 100%; ' type='text' id='txtCategoriaEd' name='txtCategoriaEd' class='form-control' value='0' >
								<option value='0'>Todas Categorias</option>";
    // $sqlCategorias = mysqli_query($conn, "SELECT * FROM categoriaserfin ORDER BY cod ASC");
    $sqlCat = "SELECT * FROM categoriaserfin ORDER BY cod ASC";

    $dataTableCat = $banco->ExecuteQuery($sqlCat);
    foreach ($dataTableCat as $resultadocat) {
      $nomecategoria = $resultadocat['nome'];
      $codcategoria = $resultadocat['cod'];

      echo "<option value='$codcategoria' >$nomecategoria</option>
											";
    }



    echo "			
									
							</select>
          
                        </div>
                        </div>
                         
                    </div>
                    <div id='ResultadoValidacao58'>
                    </div>
                </div>
            </div>
            ";

    break;
  //resultado da pesquisa para produtos mais vendidos
  case 58:
    $mesano = $_GET['param1'];
    $categoria = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', string: $mesano);

    $mes_hoje = $partes['1'];
    $ano_hoje = $partes['0'];




    if ($mesano == null) {
      $mes = date('m');
      $ano = date('Y');
    } else {
      $t = explode("-", $mesano);
      $ano = $t[0];
      $mes = $t[1];
    }


    echo "
            <h3 style='padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; '>Produtos mais vendidos " . ($mes) . "/$ano
              - <a style='font-size:16pt;' target='_blank' href='Imprimir.php?pagina=10&mes=$mes&ano=$ano&categoria=$categoria' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-print' ></span> Imprimir</a>
          
            </h3>
            
            ";
    $valor_total = 0;


    if ($categoria == 0) {
      $sqlServicos = "SELECT * FROM servicos ORDER BY nome ASC";
      $dataTableServicos = $banco->ExecuteQuery($sqlServicos);
    } else {
      $sqlServicos = "SELECT * FROM servicos WHERE categoria = :cod ORDER BY nome ASC";
      $paramServicos = array(
        ":cod" => $categoria
      );
      $dataTableServicos = $banco->ExecuteQuery($sqlServicos, $paramServicos);
    }
    foreach ($dataTableServicos as $resultadoservicos) {
      $codservico = $resultadoservicos['cod'];
      $nomeservico = $resultadoservicos['nome'];
      $valorservico = $resultadoservicos['valor'];
      // $valorservico = number_format($valorservico, 2, ',', '.');
      $qtd_total = 0;
      $valor_total = 0;
      $sqlListaSaidas = "SELECT * FROM pedidos WHERE servico = :cod_produto AND mes = $mes AND ano = $ano AND status = 3 ORDER BY cod ASC";
      $paramListaSaidas = array(
        ":cod_produto" => $codservico
      );
      $dataTableListaSaidas = $banco->ExecuteQuery($sqlListaSaidas, $paramListaSaidas);
      foreach ($dataTableListaSaidas as $resultadolistasaidas) {
        $qtd = (float) $resultadolistasaidas['qtd'];
        $valor = (float) $resultadolistasaidas['valor'];
        $qtd_total = $qtd_total + $qtd;
        $valor_total = $valor_total + $valor;
      }
      $jogo[$codservico] = [
        ["$nomeservico", "$valorservico", $qtd_total, "$valor_total"],
      ];
      // print_r($jogo);
// O array
      $arraynovo[$codservico] = array(
        array($valor_total, $codservico, $nomeservico, $valorservico, $qtd_total),
      );
    }




    // var_dump($jogo);

    //  var_dump($arraynovo);

    echo "<div class='table-responsive' style='font-size:14pt;'>
        <table class='table table-striped table-sm '>
          <thead>
                    <tr>
                        <td><b>Nome Serviço/Produto</b></td>
                        <td><b>Valor Unt.</b></td>
                        <td><b>Qtd Total</b></td>
                        <td><b>Valor Total</b></td>
                    </tr>
            </thead>          ";
    arsort($arraynovo);
    foreach ($arraynovo as $chave => $valor_total) {

      foreach ($arraynovo[$chave] as $resultadoservicos) {
        $valorservico = 0;
        $qtd_total = 0;
        $valor_total = 0;

        $valor_total = $resultadoservicos[0];
        $codservico = $resultadoservicos[1];
        $nomeservico = $resultadoservicos[2];
        $valorservico = (float) $resultadoservicos[3];
        $qtd_total = $resultadoservicos[4];
        echo "
                        <tr>
                        <td>$nomeservico</td>
                        <td>" . number_format($valorservico, 2, ',', '.') . "</td>
                        <td>$qtd_total</td>
                        <td>" . number_format($valor_total, 2, ',', '.') . "</td>
                    </tr>
            
                        ";
      }
    }


    echo "</table></div>";


    break;
  //funcao para validar informações de estoque critico
  case 59:

    echo "                   <div class='row' style='margin-bottom:5px;'>
                                                <input name='txtAtaSD' id='txtAtaSD' type='hidden' value='txtAtaSD'/>
								<div class='col-12 col-md-12' id=''>
                                                                <label style='color:#337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtCategoriaSD'><span></span> Categorias:</label>
                                                                    <select onchange='validacaorelatorios(60, this.value, 0, 0, 0, 0, 0, 60)' href='javascript: func' style='height: 82px; font-size: 15pt; width: 100%; ' class='form-control' id='txtBairrosFiltros' name='txtBairrosFiltros' onchange=''>
                                                                    <option value='0'>Todas Categorias</option>
                                                                    ";
    $cod_orgao = $_SESSION['cod_orgaoF'];
    // $sqlFor2 = mysqli_query($conn, "SELECT * FROM categoria_produto WHERE cod_orgao = $cod_orgao ORDER BY cod ASC");
    $sqlFor = "SELECT * FROM categoriaserfin ORDER BY cod ASC";


    $dataTableFor = $banco->ExecuteQuery($sqlFor);
    foreach ($dataTableFor as $resultadofor) {
      $nomefornecedor = $resultadofor['nome'];
      echo "  <option value='" . $resultadofor['cod'] . "'>" . $nomefornecedor . ".</option>";
    }
    echo "             
                                                                    </select>
                                                                </div>
							
                                                                
                                                                		
                                                      
                                    <div class='col-12 col-md-12' id='ResultadoValidacao60'>
                                                           
                                                     
							
							
                                       ";

    echo "
            <h3 style='margin-left: 10px; padding:5px;  width: 100%; '>Produtos em Estado Crítico
              - <a style='font-size:14pt;' target='_blank' href='Imprimir.php?pagina=11&codcat=0' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-print' ></span> Imprimir</a>
          
            </h3>
            <table class='table' style='font-size:14pt;'>
            <tr style='text-align:center;'>
                <td><b>Cod</b></td>
                <td><b>Apresentação</b></td>
                <td><b>Produto</b></td>
                <td><b>Categoria</b></td>
                <td><b>Est. Máx</b></td>
                <td><b>Qtd</b></td>
                <td><b>Est. min.</b></td>
                
            </tr>
            ";
    //$sql = mysqli_query($conn, "SELECT * FROM produtos ORDER BY cod ASC");
    $sqlProdutos = "SELECT * FROM servicos WHERE tipo = 1 ORDER BY qtd ASC";

    $apresentacao = "";
    $textoapresentacao = "";
    $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    foreach ($dataTableProdutos as $resultadoprodutos) {

      $codproduto = $resultadoprodutos['cod'];
      $nomeproduto = $resultadoprodutos['nome'];
      $apresentacao = $resultadoprodutos['apresentacao'];
      $qtd = $resultadoprodutos['qtd'];
      if ($qtd == null || $qtd < 0) {
        $qtd = 0;
      }

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


      if ($qtd < $est_min) {
        echo " <tr style='text-align:center;'>
                <td>$codproduto</td>
                <td>$textoapresentacao</td>
                <td>$nomeproduto</td>
                <td>$nomecategoria</td>
                <td>$est_max</td>
                    <td>$qtd</td>
                <td>$est_min</td>
                
            </tr>
            ";
      }
    }
    echo "</table>
               </div>                 
							
            ";


    break;
  //funcao para validar pesquisa de estoque critico por categoria
  case 60:
    $param1 = $_GET['param1'];
    echo "
            <h3 style='margin-left: 10px; padding:5px; color:337AB7; border-bottom: 2px solid #337AB7; width: 100%; '>Produtos em Estado Crítico
              - <a style='font-size:14pt;' target='_blank' href='Imprimir.php?pagina=11&codcat=$param1' class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-print' ></span> Imprimir</a>
          
            </h3>
            <table class='table' style='font-size:14pt;'>
            <tr style='text-align:center;'>
                <td><b>Cod</b></td>
                <td><b>Apresentação</b></td>
                <td><b>Produto</b></td>
                <td><b>Categoria</b></td>
                <td><b>Est. Máx</b></td>
                <td><b>Qtd</b></td>
                <td><b>Est. min.</b></td>
                
            </tr>
            ";
    $textoapresentacao = "";
    //$sql = mysqli_query($conn, "SELECT * FROM produtos ORDER BY cod ASC");


    if ($param1 == 0) {
      //   $sqlProdutos = mysqli_query($conn, "SELECT * FROM produtos WHERE descricao LIKE '%" . $descricao . "%' ORDER BY cod ASC");
      $sqlProdutos = "SELECT * FROM servicos WHERE tipo = 1 ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    } else {
      $sqlProdutos = "SELECT * FROM servicos WHERE categoria = $param1 AND tipo = 1 ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    }



    $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    foreach ($dataTableProdutos as $resultadoprodutos) {

      $codproduto = $resultadoprodutos['cod'];
      $nomeproduto = $resultadoprodutos['nome'];
      $apresentacao = $resultadoprodutos['apresentacao'];
      $qtd = $resultadoprodutos['qtd'];
      if ($qtd == null || $qtd < 0) {
        $qtd = 0;
      }
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


      if ($qtd < $est_min) {
        echo " <tr style='text-align:center;'>
                <td>$codproduto</td>
                <td>$textoapresentacao</td>
                <td>$nomeproduto</td>
                <td>$nomecategoria</td>
                <td>$est_max</td>
                    <td>$qtd</td>
                <td>$est_min</td>
                
            </tr>
            ";
      }
    }
    echo "</table>
        
        ";

    break;

  //validacao para gerar pedido automatico formulario para filtro
  case 61:
    echo "                   <div class='row' style='margin-bottom:5px;'>
                                                <input name='txtAtaSD' id='txtAtaSD' type='hidden' value='txtAtaSD'/>
								<div class='col-12 col-md-12' id=''>
                                                                <label style='color:#337AB7; border-bottom: 2px solid #337AB7; width: 100%; ' for='txtCategoriaSD'><span></span> Categorias:</label>
                                                                    <select onchange='validacaorelatorios(62, this.value, 0, 0, 0, 0, 0, 62)' href='javascript: func' style='height: 82px; font-size: 15pt; width: 100%; ' class='form-control' id='txtBairrosFiltros' name='txtBairrosFiltros' onchange=''>
                                                                    <option value='0'>Todas Categorias</option>
                                                                    ";
    $cod_orgao = $_SESSION['cod_orgaoF'];
    // $sqlFor2 = mysqli_query($conn, "SELECT * FROM categoria_produto WHERE cod_orgao = $cod_orgao ORDER BY cod ASC");
    $sqlFor = "SELECT * FROM categoriaserfin ORDER BY cod ASC";


    $dataTableFor = $banco->ExecuteQuery($sqlFor);
    foreach ($dataTableFor as $resultadofor) {
      $nomefornecedor = $resultadofor['nome'];
      echo "  <option value='" . $resultadofor['cod'] . "'>" . $nomefornecedor . ".</option>";
    }
    echo "             
                                                                    </select>
                                                                </div>
							
                                                                
                                                                		
                                                      
                                    <div class='col-12 col-md-12' id='ResultadoValidacao62'>
                                                           
                                                        </div>                 
							
							
							
                                       ";



    break;
  //validacao para resutlado de pedido automatico
  case 62:

    $param1 = $_GET['param1'];
    echo "    <a target='_BLANK' href='Imprimir.php?pagina=16&codcat=$param1' style='margin:10px; font-size: 15pt; width: 98%;'  class='btn btn-primary btn-lg btn-block active'><span class='glyphicon glyphicon-plus'></span>Imprimir </a>
                                                        ";
    echo "<table class='table table-bordered' style='font-size:14pt;'>
                <tr style='text-align:center;'>
                    <td><b>Produto</b></td>";

    echo "<td style='width:20%;'><b>Qtd Solicitada</b></td>
               
                 
                    <td><b>Qtd</b></td>
                    <td><b>Est. máx</b></td>
                    <td><b>Est. min.</b></td>
                </tr>

        ";

    if ($param1 == 0) {
      //   $sqlProdutos = mysqli_query($conn, "SELECT * FROM produtos WHERE descricao LIKE '%" . $descricao . "%' ORDER BY cod ASC");
      $sqlProdutos = "SELECT * FROM servicos WHERE tipo = 1 ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    } else {
      $sqlProdutos = "SELECT * FROM servicos WHERE categoria = $param1 AND tipo = 1 ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    }

    $textoapresentacao = "";

    foreach ($dataTableProdutos as $resultadoprodutos) {
      $codproduto = $resultadoprodutos['cod'];
      $apresentacao = $resultadoprodutos['apresentacao'];
      $descricao = $resultadoprodutos['nome'];
      $qtd = $resultadoprodutos['qtd'];
      $valor = $resultadoprodutos['valor'];
      $categoria = $resultadoprodutos['categoria'];
      $fornecedor = $resultadoprodutos['fornecedor'];
      $est_min = $resultadoprodutos['est_mim'];
      $est_max = $resultadoprodutos['est_max'];

      //     $valor = number_format($valor, 2, ',', '.');

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

      echo "<tr style='text-align:center;' id='ResultadoValidacao11$codproduto'>
                    <td style='width:20%;'>" . $descricao . '-' . $textoapresentacao . "</td>";
      $mediasemanal = 0;
      $contadormedia = 0;
      $totalfinal = 0;
      $resultadomedia = 0;

      if ($contadormedia != 0) {
        $resultadomedia = $mediasemanal / $contadormedia;
      }
      $qtdsolic = 0;

      $qtdsolic = $est_max - $qtd;

      if ($qtdsolic < 0) {
        $qtdsolic = "NÃO SOLICITADO";
      } else {
        $qtdsolic = "<input onchange='validacaorelatorios(63, this.value, $param1, $codproduto, 0, 0, 0, 11$codproduto)' name='qtdsolic$codproduto' style='padding:20px; font-size: 10pt; width: 100%; text-align:center;' type='text' class='form-control' id='qtdsolic$codproduto' placeholder='' value='$qtdsolic'>
			";
      }

      if ($qtd < 0) {
        $qtd = 0;
      }
      echo "<td>  $qtdsolic	</td>
                  
                    <td>$qtd</td>
                        
                        <td>$est_max</td>
                            <td>$est_min</td>
                              
                </tr>
                    ";
    }
    echo "</table>";

    break;
  //validacao para atualizar estoque maximo e gerar novo valor para pedido automatico
  case 63:

    $qtdnova = $_GET['param1'];
    $categoriatal = $_GET['param2'];
    $codproduto = $_GET['param3'];

    $sqlProdutos = "SELECT * FROM servicos WHERE cod = $codproduto ORDER BY nome ASC";
    $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);


    $textoapresentacao = "";

    foreach ($dataTableProdutos as $resultadoprodutos) {
      $qtdatual = $resultadoprodutos['qtd'];
    }
    if ($qtdatual == null) {
      $qtdatual = 0;
    }


    $novo_est = 0;
    $novo_est = $qtdnova + $qtdatual;

    $query = mysqli_query($conn, "UPDATE servicos SET est_max = $novo_est WHERE cod = $codproduto");
    if ($query) {



      $sqlProdutos = "SELECT * FROM servicos WHERE cod = $codproduto ORDER BY nome ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);


      $textoapresentacao = "";

      foreach ($dataTableProdutos as $resultadoprodutos) {
        $codproduto = $resultadoprodutos['cod'];
        $apresentacao = $resultadoprodutos['apresentacao'];
        $descricao = $resultadoprodutos['nome'];
        $qtd = $resultadoprodutos['qtd'];
        $valor = $resultadoprodutos['valor'];
        $categoria = $resultadoprodutos['categoria'];
        $fornecedor = $resultadoprodutos['fornecedor'];
        $est_min = $resultadoprodutos['est_mim'];
        $est_max = $resultadoprodutos['est_max'];

        $valor = number_format($valor, 2, ',', '.');

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

        echo "  <td style='width:20%;'>" . $descricao . '-' . $textoapresentacao . "</td>";
        $mediasemanal = 0;
        $contadormedia = 0;
        $totalfinal = 0;
        $resultadomedia = 0;

        if ($contadormedia != 0) {
          $resultadomedia = $mediasemanal / $contadormedia;
        }
        $qtdsolic = 0;

        $qtdsolic = $est_max - $qtd;

        if ($qtdsolic < 0) {
          $qtdsolic = "NÃO SOLICITADO";
        } else {
          $qtdsolic = "<input onchange='Validacao3param(43, this.value, $categoriatal, $codproduto, 11$codproduto)' name='qtdsolic$codproduto' style='padding:20px; font-size: 10pt; width: 100%; text-align:center;' type='text' class='form-control' id='qtdsolic$codproduto' placeholder='' value='$qtdsolic'>
			";
        }


        echo "<td>  $qtdsolic	</td>
                  
                    <td>$qtd </td>
                        
                        <td>$est_max</td>
                            <td>$est_min</td>
               
                    ";
      }
    }
    break;
  //pagina inicial para filtro para historico de saídas   
  case 64:

    echo "
          <div class='row' style='background-color:#fff; margin-bottom:10px;'>
            <div class='col-sm-12 col-xl-6'>
              <label>Deseja consultar qual período</label>
              <select onchange='validacaorelatorios(65, this.value, txtCategoriaEd.value, txtDataParaPesquisa.value, 0, 0, 0, 65)' name='txtConsultaPeriodo' id='txtConsultaPeriodo' class='form-control' style='font-size:12pt; padding:20px;'>
                  <option value='1'>Dia</option>
                  <option value='2'>Mes</option>
                  <option value='3'>Ano</option>
              </select>
            </div>  
            <div class='col-12 col-md-6' style='text-align: left;'>
                        <div class='form-group label-floating'>
                            <label style='width: 100%; ' for='txtCategoriaEd' class='control-label'>Selecione Categoria</label>
                             <select href='javascript: func' onchange='validacaorelatorios(66, txtConsultaPeriodo.value, this.value, txtDataParaPesquisa.value, 0, 0, 0, 66)'  style='height: 65px;px; font-size: 15pt; width: 100%; ' type='text' id='txtCategoriaEd' name='txtCategoriaEd' class='form-control' value='0' >
								<option value='0'>Todas Categorias</option>";
    // $sqlCategorias = mysqli_query($conn, "SELECT * FROM categoriaserfin ORDER BY cod ASC");
    $sqlCat = "SELECT * FROM categoriaserfin ORDER BY cod ASC";

    $dataTableCat = $banco->ExecuteQuery($sqlCat);
    foreach ($dataTableCat as $resultadocat) {
      $nomecategoria = $resultadocat['nome'];
      $codcategoria = $resultadocat['cod'];

      echo "<option value='$codcategoria' >$nomecategoria</option>
											";
    }



    echo "			
									
							</select>
          
                        </div>
                        </div>
        
          </div>  
          <div id='ResultadoValidacao65'>
            <div class='col-sm-12 col-xl-12'>
                         <label>Data</label>
                        <input onkeyup='validacaorelatorios(66, txtConsultaPeriodo.value, txtCategoriaEd.value, this.value, 0, 0, 0, 66)' class='form-control' style='font-size:12pt; padding:20px;' type='date' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m-d') . "' />
                     </div>  
                       <div id='ResultadoValidacao66'> 
                      </div>    
          </div>
      ";
    break;
  //validacao para mostrar formulario de data para pesquisar historico
  case 65:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];

    if ($param1 == 1) {
      echo "
                <div class='col-sm-12 col-xl-12'>
                        <label>Data</label>
                        <input onkeyup='validacaorelatorios(66, txtConsultaPeriodo.value, txtCategoriaEd.value, this.value, 0, 0, 0, 66)' class='form-control' style='font-size:12pt; padding:20px;' type='date' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m-d') . "' />
                      </div>  
                       <div id='ResultadoValidacao66'> 
              ";



      echo "</div>";
    }
    if ($param1 == 2) {
      echo "
      <div class='col-sm-12 col-xl-12'>
              <label>Mês/Ano</label>
              <input onkeyup='validacaorelatorios(66, txtConsultaPeriodo.value, txtCategoriaEd.value, this.value, 0, 0, 0, 66)' class='form-control' style='font-size:12pt; padding:20px;' type='month' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m') . "' />
            </div>  
    ";
      echo "
               
                       <div id='ResultadoValidacao66'> 
              ";


      $dia_hoje = date("d");
      $mes_hoje = (float) date("m");
      $ano_hoje = date("Y");

      echo "</div>";

    }
    if ($param1 == 3) {
      echo "
      <div class='col-sm-12 col-xl-12'>
              <label>Data</label>
              <input onkeyup='validacaorelatorios(66, txtConsultaPeriodo.value, txtCategoriaEd.value, this.value, 0, 0, 0, 66)'  min='2025' max='2050' class='form-control' style='font-size:12pt; padding:20px;' type='number' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y') . "' />
            </div>  
    ";

      echo "
               
    <div id='ResultadoValidacao66'> 
";


      $dia_hoje = date("d");
      $mes_hoje = (float) date("m");
      $ano_hoje = date("Y");

      echo "</div>";

    }

    break;
  //RESULTADO FINAL PARA PESQUISAR HISTORICO DE SAÍDAS POR PRODUTOS
  case 66:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param3);


    $categoria = $param2;

    $ordem = 0;

    $valortotalfinalporatasai = 0;
    $valortotalfinalporataqtd = 0;
    $codfornecedor2 = 0;

    if ($param1 == 1) {
      echo "<h3>Lista de Saídas Por Dia: $param3</h3>";
      $dia_hoje = $partes['2'];
      $mes_hoje = $partes['1'];
      $ano_hoje = $partes['0'];

    } else if ($param1 == 2) {

      $mes_hoje = $partes['1'];
      echo "<h3>Lista de Saídas Por Mês: " . mostraMes($mes_hoje) . "</h3>";

      $mes_hoje = $partes['1'];
      $ano_hoje = $partes['0'];

    } else if ($param1 == 3) {
      echo "<h3>Lista de Saídas Por Ano: $param3</h3>";

      $ano_hoje = $partes['0'];

    }
    echo "<table class='table table-light table-bordered'>

    <tr style='text-align:center;'>
        <td><b>Ordem</b></td>
        <td style='width: 40%;'><b>Produto</b></td>
        <td><b>Categoria</b></td>
        <td><b>Valor Unt</b></td>
        <td><b>Qtd</b></td>
        <td><b>Valor Total</b></td>
        
    </tr>";



    $valorunt = 0;
    $valortotalporcat = 0;
    $valortotalfinal = 0;

    $truorfalsevalorfornecedor = 0;

    $qtdtotalentfinal = 0;
    $qtdtotalsaifinal = 0;
    $valortotalentfinal = 0;
    $valortotalsaifinal = 0;
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


    if ($categoria == 0) {

      $sqlProdutos = "SELECT * FROM servicos ORDER BY cod ASC";
      $dataTableProdutos = $banco->ExecuteQuery($sqlProdutos);
    } else {
      $sqlProdutos = "SELECT * FROM servicos WHERE categoria = :categoria ORDER BY nome ASC";
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

      $qtdtotal = 0;
      $qtd = 0;
      if ($param1 == 1) {

        $sqlPedidos = "SELECT * FROM pedidos WHERE servico = :cod AND dia = :dia AND mes = :mes AND ano = :ano ORDER BY cod DESC";
        $paramPedidos = array(
          ":cod" => $codproduto,
          ":dia" => $dia_hoje,
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );

      } else if ($param1 == 2) {

        $sqlPedidos = "SELECT * FROM pedidos WHERE servico = :cod AND mes = :mes AND ano = :ano ORDER BY cod DESC ";
        $paramPedidos = array(
          ":cod" => $codproduto,
          ":mes" => $mes_hoje,
          ":ano" => $ano_hoje,
        );


      } else if ($param1 == 3) {

        $sqlPedidos = "SELECT * FROM pedidos WHERE servico = :cod AND ano = :ano ORDER BY cod DESC";
        $paramPedidos = array(
          ":cod" => $codproduto,
          ":ano" => $ano_hoje,
        );

      }
      $nomecategoria = "Avulso";
      $dataTablePedidos = $banco->ExecuteQuery($sqlPedidos, $paramPedidos);
      foreach ($dataTablePedidos as $resultadopedidos) {

        $codnota = $resultadopedidos['usuario'];

        $sqlNotasDia = mysqli_query($conn, "SELECT * FROM notas WHERE cod = $codnota AND status = 3 ORDER BY cod ASC");
        // Exibe todos os valores encontrados
        while ($notasdia = mysqli_fetch_object($sqlNotasDia)) {
          $qtd = $resultadopedidos['qtd'];
          if ($qtd <= 0) {
            $qtd = 0;
          }
          $qtdtotal = $qtdtotal + $qtd;

        }
      }

      $ordem++;

      $codproduto = $resultadoprodutos['cod'];
      $nomeproduto = $resultadoprodutos['nome'];
      $codbuscaproduto = $resultadoprodutos['codbusca'];
      $codbarraproduto = $resultadoprodutos['codbarra'];
      $apresentacao = $resultadoprodutos['apresentacao'];

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


      $valortotalporcat = $valorunt * $qtdtotal;
      $valortotalfinal = $valortotalfinal + $valortotalporcat;
      if ($qtd == null || $qtd < 0) {
        $qtd = 0;
      }

      $totalporproduto = 0;
      $totalporproduto = $qtd * $valorunt;
      if ($qtdtotal > 0) {
        echo " 
                        <tr style='text-align:center;'>
                            <td>$ordem</td>
			     
                            <td>$nomeproduto </br>
                                <B>Cod. Busca:</b> $codbuscaproduto / <B>Cod. Barra:</b> $codbarraproduto
                                </td>

                                
                            <td>$nomecategoria </br>
                            
                            <td>R$ " . number_format($valorunt, 2, ',', '.') . " </br>
                            
                            
                            <td>$qtdtotal </br>
                            
                            
                            
                            <td>R$ " . number_format($totalporproduto, 2, ',', '.') . " </br>
                             ";
        $totalporproduto = 0;
        $qtdtotal = 0;
      }
    }
    $valortotalporcat = 0;
    echo " 
                        <tr>
                          
                            <td colspan='5' style='text-align:rigth;'>Total Final</td>
                            <td>R$ " . number_format($valortotalfinal, 2, ',', '.') . "</td>
                                   </tr>

                                            ";

    $totalporprodutoent = 0;
    $totalporprodutosaid = 0;



    $valortotalfinalporata = 0;
    $totalfinalporata = 0;
    $valortotalfinalporatasai = 0;
    $valortotalfinalporataqtd = 0;



    $saldoqtdfinal = $qtdtotalentfinal - $qtdtotalsaifinal;


    echo "</table>";



    break;
  //FUNCOES RELACIONADAS A GERAÇÃO DE RELATÓRIO DE DESPESAS
  case 67:

    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];

    if ($param1 == 1) {
      echo "
                <div class='col-sm-12 col-xl-12'>
                        <label>Data</label>
                        <input onkeyup='validacaorelatorios(68, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, txtTipopagamento.value, this.value, 0, 0, 68)' class='form-control' style='font-size:12pt; padding:20px;' type='date' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m-d') . "' />
                      </div>  
                       <div id='ResultadoValidacao68'> 
                       </div>
              ";


      $dia_hoje = date("d");
      $mes_hoje = date("m");
      $ano_hoje = date("Y");
    }
    if ($param1 == 2) {
      echo "
                 <div class='col-sm-12 col-xl-12'>
              <label>Mês/Ano</label>
              <input onkeyup='validacaorelatorios(68, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, txtTipopagamento.value, this.value, 0, 0, 68)' class='form-control' style='font-size:12pt; padding:20px;' type='month' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y-m') . "' />
            </div> 
             <div id='ResultadoValidacao68'> 
              </div>
              ";


      $dia_hoje = date("d");
      $mes_hoje = date("m");
      $ano_hoje = date("Y");
    }
    if ($param1 == 3) {
      echo "
                <div class='col-sm-12 col-xl-12'>
              <label>Ano</label>
              <input onkeyup='validacaorelatorios(68, txtConsultaPeriodo.value, txtTipopagamentoAvista.value, txtTipopagamento.value, this.value, 0, 0, 68)'  min='2025' max='2050' class='form-control' style='font-size:12pt; padding:20px;' type='number' id='txtDataParaPesquisa' name'txtDataParaPesquisa' value='" . date('Y') . "' />
            </div>
                       <div id='ResultadoValidacao68'> 
                       </div>
              ";


      $dia_hoje = date("d");
      $mes_hoje = date("m");
      $ano_hoje = date("Y");
    }
    break;
  //RESULTADO PESQUISA PARA DESPESAS
  case 68:


    $param1 = $_GET['param1'];
    $param2 = $_GET['param2'];
    $param3 = $_GET['param3'];
    $param4 = $_GET['param4'];

    $partes = explode('-', $param4);

    if ($param1 == 1) {
      $dia_hoje = $partes['2'];
      $mes_hoje = $partes['1'];
      $ano_hoje = $partes['0'];
      //$sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE dia = $dia_hoje AND mes = $mes_hoje AND  ano = $ano_hoje ORDER BY id ASC");
      $totalfinaldiacat = 0;
      if ($param2 == 0) {
        $sqlFinEmpresa = "SELECT * FROM financeiro_empresa WHERE dia = :dia_hoje AND mes = :mes_hoje AND  ano = :ano_hoje ORDER BY id ASC";
        $paramFinEmpresa = array(
          ":dia_hoje" => $dia_hoje,
          ":mes_hoje" => $mes_hoje,
          ":ano_hoje" => $ano_hoje
        );


      } else {
        $sqlFinEmpresa = "SELECT * FROM financeiro_empresa WHERE cat = :categoria AND dia = :dia_hoje AND mes = :mes_hoje AND  ano = :ano_hoje ORDER BY id ASC";
        $paramFinEmpresa = array(
          ":categoria" => $param2,
          ":dia_hoje" => $dia_hoje,
          ":mes_hoje" => $mes_hoje,
          ":ano_hoje" => $ano_hoje
        );
      }
    }
    if ($param1 == 2) {

      $mes_hoje = $partes['1'];
      $ano_hoje = $partes['0'];
      //$sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE dia = $dia_hoje AND mes = $mes_hoje AND  ano = $ano_hoje ORDER BY id ASC");
      $totalfinaldiacat = 0;
      if ($param2 == 0) {
        $sqlFinEmpresa = "SELECT * FROM financeiro_empresa WHERE  mes = :mes_hoje AND  ano = :ano_hoje ORDER BY id ASC";
        $paramFinEmpresa = array(
          ":mes_hoje" => $mes_hoje,
          ":ano_hoje" => $ano_hoje
        );


      } else {
        $sqlFinEmpresa = "SELECT * FROM financeiro_empresa WHERE cat = :categoria AND mes = :mes_hoje AND  ano = :ano_hoje ORDER BY id ASC";
        $paramFinEmpresa = array(
          ":categoria" => $param2,
          ":mes_hoje" => $mes_hoje,
          ":ano_hoje" => $ano_hoje
        );
      }
    }
    if ($param1 == 3) {
      $ano_hoje = $partes['0'];
      //$sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE dia = $dia_hoje AND mes = $mes_hoje AND  ano = $ano_hoje ORDER BY id ASC");
      $totalfinaldiacat = 0;
      if ($param2 == 0) {
        $sqlFinEmpresa = "SELECT * FROM financeiro_empresa WHERE  ano = :ano_hoje ORDER BY id ASC";
        $paramFinEmpresa = array(
          ":ano_hoje" => $ano_hoje
        );


      } else {
        $sqlFinEmpresa = "SELECT * FROM financeiro_empresa WHERE cat = :categoria AND ano = :ano_hoje ORDER BY id ASC";
        $paramFinEmpresa = array(
          ":categoria" => $param2,
          ":ano_hoje" => $ano_hoje
        );
      }
    }


    echo "          <table class='table'>
                    <tr style='text-align:center;'>
                        <td><b>Descrição</b></td>
                        <td><b>Valor</b></td>
                        <td><b>Categoria</b></td>
                        <td></td>
                    </tr>
		";
    //$sqlDividasDia = mysqli_query($conn, "SELECT * FROM financeiro_empresa WHERE dia = $dia_hoje AND mes = $mes_hoje AND  ano = $ano_hoje ORDER BY id ASC");
    $totalfinaldiacat = 0;

    $dataTableFinEmpresa = $banco->ExecuteQuery($sqlFinEmpresa, $paramFinEmpresa);
    foreach ($dataTableFinEmpresa as $resultadofimempresa) {
      $cod = $resultadofimempresa['id'];
      $cat = $resultadofimempresa['cat'];
      $descricao = $resultadofimempresa['descricao'];
      $diad = $resultadofimempresa['dia'];
      $mesd = $resultadofimempresa['mes'];
      $anod = $resultadofimempresa['ano'];
      $pontos = ',';
      $result = str_replace($pontos, "", $resultadofimempresa['valor']);
      $valor_total = (float) $result;
      $total = $valor_total;
      //$sqlCategorias = mysqli_query($conn, "SELECT * FROM lc_cat WHERE id = $cat ORDER BY id ASC");
      $sqlCategorias = "SELECT * FROM lc_cat WHERE id = :cat ORDER BY id ASC";
      $paramCategorias = array(
        ":cat" => $cat
      );

      $dataTableCategorias = $banco->ExecuteQuery($sqlCategorias, $paramCategorias);
      foreach ($dataTableCategorias as $resultadocategorias) {
        $idcat = $resultadocategorias['id'];
        $nomecat = $resultadocategorias['nome'];
      }
      $totalfinaldiacat = $totalfinaldiacat + $total;
      echo "
                                    <tr style='text-align:center;'>
                                        <td>" . $descricao . " </td>
                                        <td>R$ " . number_format($total, 2, ',', '.') . "</td>
                                        <td>$nomecat</td>
                                            <td><a class='btn btn-primary btn-sm' onclick='Validacao(46, 2, 2, 87)' style='color:#fff;'><span class='glyphicon glyphicon-retweet'></span> Editar Dados</a>
                    </td>
                                    </tr>
                                        ";
    }
    echo "</div>";
    echo "<tr><td><b>Valor Total</b></td><td style='color:green;'>R$ " . number_format($totalfinaldiacat, 2, ',', '.') . "</td></tr>";


    break;
}
