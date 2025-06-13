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
  $codnota = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
  $tipo = 2;
  $listaNotas = $notasController->RetornarNotas($tipo, $cod);
  if ($listaNotas != null) {
    foreach ($listaNotas as $user477) {
      $status_nota = $user477->getStatus();
      $hora = $user477->getHora();
      $dia = $user477->getDia();
      $mes = $user477->getMes();
      $ano = $user477->getAno();
      $codcliente = $user477->getUsuario();
      $clienteavulso = $user477->getNomeCli();
      $codfunc = $user477->getFunc();
      $ordem = $user477->getOrdem();
      $tipo = $user477->getTipo_entrega();
      $cod_caixa = $user477->getCod_caixa();
      $tipo_entrega = $user477->getTipo_entrega();

      $data_hora = $dia . '/' . $mes . '/' . $ano . '  ' . $hora;
      $nome_func = $usuarioController->RetornarNomeUsuarios($codfunc);

      $textocliente = "";

      if ($codcliente == 0) {
        if ($clienteavulso != null) {
          $textocliente = $clienteavulso;
        } else {
          $textocliente = 'Nenhum cliente selecionado';
        }


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
      } else {

        $nomecliente = "";
        $nomecliente = $clientesController->RetornarNomeClientes($codcliente);
        $textocliente = $nomecliente;
      }

      $texto_tipo = "";
      if ($tipo_entrega == 1) {
        $texto_tipo = "VENDA NO BALCÃO(EXPRESSA)";
      } else if ($tipo_entrega == 2) {
        $texto_tipo = "VENDA PARA RETIRADA NO BALCÃO";
      } else if ($tipo_entrega == 3) {
        $texto_tipo = "VENDA PARA ENTREGA";
      }
    }
  }
} else {
  header("location: index.php?msgget=2");
}


//SQL PARA CAPTURAR O VALOR TOTAL DO PEDIDO QUANDO O CARRINHO FOR INICIADO

$sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod DESC");
$valor_total_pedido = 0;
$qtdpedidos = 0;
while ($pedidos = mysqli_fetch_object($sqlPedido)) {
  $qtdpedidos = $qtdpedidos + (float) $pedidos->qtd;
  $valor = (float) $pedidos->valor;
  $valor_total_pedido = $valor_total_pedido + $valor;
  $valor = 0;
}
$valor_total_pedido = number_format($valor_total_pedido, 2, ',', '.');
//FIM DA CONSULTA

//SQL PARA VALIDAR SE A VENDA JÁ FOI REALIZADO O PAGAMENTO
$sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
$paramTestePag = array(
  ":cod" => $codnota
);
$nomecategoria = "Avulso";
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

  $dinheiropag = (float) $infopagamento['dinheiro'];
  $pixpag = (float) $infopagamento['pix'];
  $debitopag = (float) $infopagamento['debito'];
  $creditopag =  (float)$infopagamento['credito'];
  $totalpag =  (float)$infopagamento['total'];
  $subtotalpag =  (float) $infopagamento['subtotal'];
  $descontopag =  (float) $infopagamento['desconto'];
  $gorjetapag = (float) $infopagamento['gorjeta'];


  $tipopagamento = $infopagamento['tipo'];
  $numparcelas = (int) $infopagamento['numparcelas'];
  
  if($tipopagamento == 2 && $numparcelas != 0){
    $valorparcela = $totalpag /$numparcelas;
  }

}

//var_dump($dataTableTestePag);

//FIM DO SQL PARA CONSULTA DO PAGAMENTO




//SUBMIT PARA FINALIZAR PAGAMENTO
if (filter_input(INPUT_POST, "btnFinalizarPagamento", FILTER_SANITIZE_STRING)) {

  $codnota = filter_input(INPUT_POST, "txtTotalReal", FILTER_SANITIZE_NUMBER_INT);
  $tipo = filter_input(INPUT_POST, "txtTipo", FILTER_SANITIZE_NUMBER_INT);
  $tipopag = filter_input(INPUT_POST, "txtTipoPag", FILTER_SANITIZE_NUMBER_INT);
  $dinheiro = filter_input(INPUT_POST, "txtDinheiro", FILTER_SANITIZE_STRING);
  $pix = filter_input(INPUT_POST, "txtPix", FILTER_SANITIZE_STRING);
  $debito = filter_input(INPUT_POST, "txtCardebitoPag", FILTER_SANITIZE_STRING);
  $credito = filter_input(INPUT_POST, "txtCarcreditoPag", FILTER_SANITIZE_STRING);
  $desconto = filter_input(INPUT_POST, "txtDesconto", FILTER_SANITIZE_STRING);

  $numparcelas = 1;
  $troco = 0;
  $juros = 1;
  $juros2 = 1;



  $param1 = $dinheiro; //DINHEIRO 
  $param2 = $pix; //PIX
  $param3 = $debito; //DEBITO
  $param4 = $credito; //CREDITO
  $param6 = $desconto; //DESCONTO
  $msgteste = null;

  $sqlPedido = mysqli_query($conn, "SELECT * FROM pedidos WHERE usuario = " . $codnota . " ORDER BY cod DESC");
  $total = 0;
  $codpedido = 0;
  $qtdpedidos = 0;
  $totalfinal = 0;
  $valor = 0;
  while ($pedidos = mysqli_fetch_object($sqlPedido)) {
    $qtdpedidos = $qtdpedidos + (float) $pedidos->qtd;
    $valor = $pedidos->valor;
    $totalfinal = $totalfinal + $valor;
  }


  //$totalfinal = number_format($totalfinal, 2, ',', '.');

  $param5 = $totalfinal;
  $paramnovo5 = (float) $param5;
  $subtotal = $param5;

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


  $dinheiro = $paramnovo1; //DINHEIRO 
  $pix = $paramnovo2; //PIX
  $debito = $paramnovo3; //DEBITO
  $credito = $paramnovo4; //CREDITO
  $desconto = $paramnovo6; //DESCONTO
  $msgteste = null;



  $soma_pamento_alternativo = $paramnovo2 + $paramnovo3 + $paramnovo4;


  $pagamento_total = $paramnovo1 + $soma_pamento_alternativo;
  $operacao_desconto = $paramnovo5 - $paramnovo6;

  $troco = 0;
  $totalfinal = $operacao_desconto;
  $troco = $pagamento_total - $operacao_desconto;

  $dinheiro = $dinheiro - $troco;


  if ($soma_pamento_alternativo > $operacao_desconto) {
    $msgteste = "<div class='alert alert-danger' style='font-size:7pt;'>O valores inseridos nos pagamentos alternativos ao dinheiro ultrapassam o valor total da compra.</div>";
  }

  if ($troco < 0) {
    $troco = 0;
    $msgteste = "<div class='alert alert-danger' style='font-size:7pt;'>Valor Pagamento Inváldio</div>";
  }

  //$troco = number_format($troco, 2, ',', '.');
  //$totalfinal = number_format($totalfinal, 2, ',', '.');
  //$totalfinal = number_format($totalfinal, 2, ',', '.');



  $saidas = $notasController->RetornarNotas(2, $codnota);



  $total = 0;
  if ($saidas != NULL) {
    foreach ($saidas as $saidasteste) {


      $dia = $saidasteste->getDia();
      $mes = $saidasteste->getMes();
      $ano = $saidasteste->getAno();


      if ($saidasteste->getStatus() == 3) {
        header("Location: index.php?pagina=carinhocompras&cod=$codnota");
      } else {
        if ($notasController->AlterStatusTodos(3, $codnota)) {

          $resultado = " <div class='alert alert-success' role='alert'><span>Saída finalizada com sucesso!</span> </div>";
          $termo = "";
          $tipo = 1;
          $status2 = $codnota;
          $pedidosController->AlterStatusTodos2(3, $codnota);

          $listasaidas3 = $pedidosController->RetornarPedidos($termo, $tipo, $status2);
          // var_dump($listasaidas3);
          if ($listasaidas3 != NULL) {
            foreach ($listasaidas3 as $saidas3) {

              $qtd_saida = $saidas3->getQtd();
              $cod_produto2 = $saidas3->getServico();
              // $total = $total + $saidas3->getValor();



              $tipo_servico = $servicoController->RetornarTipo($cod_produto2);


              if ($tipo_servico == 1) {
                $qtd_final = 0;
                $qtd_produto = $servicoController->RetornarNomeValorProdutos($cod_produto2);
                $qtd_final = $qtd_produto - $qtd_saida;
                $servicoController->AlterQtdEstoque($qtd_final, $cod_produto2);
              }
            }
            //  $totalfinal = $total;
            $tipopag1 = 1;
            $tipopag2 = 1;




            $sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
            $paramTestePag = array(
              ":cod" => $codnota
            );
            $nomecategoria = "Avulso";
            $dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);
            //  var_dump($dataTableTestePag);

            if ($dataTableTestePag == null) {

              $query = mysqli_query($conn, "INSERT INTO `financeiro_clientes` (`cod_orcamento`, `tipo`, `total`, `numparcelas`, `tipopag`, `dia`, `mes`, `ano`, `gorjeta`, `dinheiro`, `debito` , `credito` ,`pix`, `desconto`, `subtotal`) VALUES ('" . $codnota . "', '" . $tipopag1 . " ', '" . $totalfinal . " ', '" . $numparcelas . "', " . $tipopag2 . " , '" . $dia . " ', '" . $mes . " ', '" . $ano . " ', '" . $troco . "', '" . $dinheiro . "', '" . $debito . "', '" . $credito . "', '" . $pix . "', '" . $desconto . "', '" . $subtotal . "')");
              // Se inserido com scesso
              if ($query) {

                header("Location: index.php?pagina=carinhocompras&cod=$codnota&pagamentofinalizado");
              }
            }
          }
        } else {
          $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao trocar status da saída!</span> </div>";
        }
      }
    }
  }
}
//FIM DO SUBMIT PARA FINALIZAR O PAGAMENTO

//SUBMIT PARA FINALIZAR PAGAMENTO
if (filter_input(INPUT_POST, "btnEditarQtdValor", FILTER_SANITIZE_STRING)) {



  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $codpedido = filter_input(INPUT_POST, "txtCodpedido", FILTER_SANITIZE_NUMBER_INT);
  $qtditem = filter_input(INPUT_POST, "txtQtdEd", FILTER_SANITIZE_NUMBER_INT);
  $valortotal = filter_input(INPUT_POST, "txtValorTotalRealEd", FILTER_SANITIZE_STRING);
  $obs = filter_input(INPUT_POST, "txtObsEd", FILTER_SANITIZE_STRING);


  $sqlPedidos = "SELECT * FROM pedidos WHERE cod = :cod ORDER BY cod DESC LIMIT 1";
  $paramPedidos = array(
    ":cod" => $codpedido
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
    $valortotalbd = $qtditem * $valorunt;

    $codpedido = $resultadopedidos['cod'];
  }

  $valor = (float) $valortotalbd;



  if ($pedidosController->AlterQtdValor($qtditem, $valor, $obs, $codpedido)) {


    header("location: index.php?pagina=carinhocompras&cod=$codnota&atualizadocomsucesso=$qtditem");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM DO SUBMIT PARA FINALIZAR O PAGAMENTO

//SUBMIT PARA PEDIDO AVULSO
if (filter_input(INPUT_POST, "btnCadastrarItemAvulso", FILTER_SANITIZE_STRING)) {


  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $qtditem = filter_input(INPUT_POST, "txtQtdAvulso", FILTER_SANITIZE_NUMBER_INT);
  $valorunt = filter_input(INPUT_POST, "txtValoruntAvulso", FILTER_SANITIZE_STRING);
  $descricaoitem = filter_input(INPUT_POST, "txtDescricaoAvulso", FILTER_SANITIZE_STRING);

  $pontos = '.';
  $result = str_replace($pontos, "", $valorunt);
  $result = str_replace(",", ".", $result);
  $valorunt = (float) $result;

  $valortotal = 0;

  $valortotal = $qtditem * $valorunt;

  $sqlPedidos2 = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod DESC";
  $paramPedidos2 = array(
    ":cod" => $codnota
  );

  $dataTablePedidos2 = $banco->ExecuteQuery($sqlPedidos2, $paramPedidos2);
  foreach ($dataTablePedidos2 as $notaslista) {

    $dia = $notaslista['dia'];
    $mes = $notaslista['mes'];
    $ano = $notaslista['ano'];
  }



  $pedidos = new Pedidos();
  $pedidos->setUsuario($codnota);
  $pedidos->setServico(0);
  $pedidos->setQtd($qtditem);
  $pedidos->setValor($valortotal);
  $pedidos->setObs($descricaoitem);
  $pedidos->setStatus(1);
  $pedidos->setTipo(1);
  $pedidos->setDia(1);
  $pedidos->setMes(1);
  $pedidos->setAno(1);
  $pedidos->setCategoria(0);

  if ($pedidosController->Cadastrar($pedidos)) {
    header("location: index.php?pagina=carinhocompras&cod=$codnota&atualizadocomsucesso");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM DO SUBMIT PARA PEDIDO AVULSO

//SUBMIT PARA ATUALIZAR CLIENTE EM NOTAS
if (filter_input(INPUT_POST, "btnSubmitClienteAtualizarNota", FILTER_SANITIZE_STRING)) {


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
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM DO SUBMIT PARA CADASTRO COMPLETO DE CLIENTE

//SUBMIT PARA atualizar dados do CLIENTE
if (filter_input(INPUT_POST, "btnSubmitClienteAlterarDados", FILTER_SANITIZE_STRING)) {


  $codnota = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $codcliente = filter_input(INPUT_POST, "txtCodcliente", FILTER_SANITIZE_NUMBER_INT);
  $endereco = filter_input(INPUT_POST, "txtEnderecoCadCom", FILTER_SANITIZE_STRING);
  $numero = filter_input(INPUT_POST, "txtNCadCom", FILTER_SANITIZE_STRING);
  $bairro = filter_input(INPUT_POST, "txtBairroCadCom", FILTER_SANITIZE_STRING);
  $complemento = filter_input(INPUT_POST, "txtComplementoCadCOm", FILTER_SANITIZE_STRING);

  $sqlClientes = "SELECT * FROM clientes WHERE id = :cod ORDER BY id ASC";
  $paramClientes = array(
    ":cod" => $codcliente
  );

  $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
  foreach ($dataTableClientes as $resultadoclientes) {
    $codcliente = $resultadoclientes['id'];
    $nomecliente = $resultadoclientes['nome'];
    $cpf = $resultadoclientes['cpf'];
    $celular = $resultadoclientes['celular'];
    $datanascimento = $resultadoclientes['nascimento'];
  }

  $clientes = new Clientes();
  $clientes->setId($codcliente);
  $clientes->setNome($nomecliente);
  $clientes->setNascimento($datanascimento);
  $clientes->setCpf($cpf);
  $clientes->setEndereco($endereco);
  $clientes->setNumero($numero);
  $clientes->setBairro($bairro);
  $clientes->setComplemento($complemento);
  $clientes->setCelular($celular);


  if ($clientesController->Alterar($clientes)) {

    header("Location: index.php?pagina=carinhocompras&cod=$codnota&finalizadocomsucesso222");
  } else {
    $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um herro ao cadastrar novo Orçamento!</span> </div>";
  }
}
//FIM DO SUBMIT PARA ATUALIZAR CADASTRO CLIENTE

//SUBMIT PARA PAGAMENTO EM CREDIARIO
if (filter_input(INPUT_POST, "btnCadastrarFinalizarPagamentoCrediario", FILTER_SANITIZE_STRING)) {


  $cod_saida = filter_input(INPUT_POST, "txtCodnota", FILTER_SANITIZE_NUMBER_INT);
  $formapagamento = 3;
  $numparcelas = filter_input(INPUT_POST, "numparcelas", FILTER_SANITIZE_NUMBER_INT);
  $troco = filter_input(INPUT_POST, "txtTroco", FILTER_SANITIZE_STRING);
  $juros = filter_input(INPUT_POST, "juros", FILTER_SANITIZE_STRING);
  $juros2 = filter_input(INPUT_POST, "juros2", FILTER_SANITIZE_STRING);

  $saidas = $notasController->RetornarNotas(2, $cod_saida);

  $total = 0;
  if ($saidas != NULL) {
    foreach ($saidas as $saidasteste) {


      $dia = $saidasteste->getDia();
      $mes = $saidasteste->getMes();
      $ano = $saidasteste->getAno();


      if ($saidasteste->getStatus() == 3) {
        header("Location: index.php?pagina=carinhocompras&cod=$cod_saida");
      } else {
        if ($notasController->AlterStatusTodos(3, $cod_saida)) {
          $resultado = " <div class='alert alert-success' role='alert'><span>Saída finalizada com sucesso!</span> </div>";
          $termo = "";
          $tipo = 1;
          $status2 = $cod_saida;
          $pedidosController->AlterStatusTodos2(3, $cod_saida);
          $listasaidas3 = $pedidosController->RetornarPedidos($termo, $tipo, $status2);
          // var_dump($listasaidas3);
          if ($listasaidas3 != NULL) {
            foreach ($listasaidas3 as $saidas3) {

              $qtd_saida = $saidas3->getQtd();
              $cod_produto2 = $saidas3->getServico();
              $total = $total + $saidas3->getValor();



              $tipo_servico = $servicoController->RetornarTipo($cod_produto2);
              if ($tipo_servico == 1) {
                $qtd_final = 0;
                $qtd_produto = $servicoController->RetornarNomeValorProdutos($cod_produto2);
                $qtd_final = $qtd_produto - $qtd_saida;
                $servicoController->AlterQtdEstoque($qtd_final, $cod_produto2);
              }
            }
            $totalfinal = $total;
            if ($formapagamento == 1) {
              $tipopag1 = 1;
              $tipopag2 = 1;
            } else if ($formapagamento == 2) {
              $tipopag1 = 2;
              $tipopag2 = 1;
            } else if ($formapagamento == 3) {
              $tipopag1 = 2;
              $tipopag2 = 2;

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

              $total = $totalfinal;
              $pontos = '.';
              $total = str_replace($pontos, "", $total);

              $total = str_replace(",", ".", $total);
            } else if ($formapagamento == 4) {
              $tipopag1 = 1;
              $tipopag2 = 2;
            } else if ($formapagamento == 5) {
              $tipopag1 = 1;
              $tipopag2 = 3;
            }

            $sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
            $paramTestePag = array(
              ":cod" => $cod_saida
            );
            $nomecategoria = "Avulso";
            $dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);
            if ($dataTableTestePag == null) {
              $query = mysqli_query($conn, "INSERT INTO `financeiro_clientes` (`cod_orcamento`, `tipo`, `total`, `numparcelas`, `tipopag`, `dia`, `mes`, `ano`, `gorjeta`) VALUES ('" . $cod_saida . "', '" . $tipopag1 . " ', '" . $total . " ', '" . $numparcelas . "', " . $tipopag2 . " , '" . $dia . " ', '" . $mes . " ', '" . $ano . " ', '" . $troco . "')");
              // Se inserido com scesso
              if ($query) {


                header("Location: index.php?pagina=carinhocompras&cod=$cod_saida&pagamentofinalizado");
              }
            }
          }
        } else {
          $resultado = " <div class='alert alert-danger' role='alert'><span>Houve um erro ao trocar status da saída!</span> </div>";
        }
      }
    }
  }
}

//FIM DO SUBMIT PAGAMENTO EM CREDIARIO 

if ($dataTableTestePag == null) {
?>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <svg style="fill: #0000ff;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-box-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
        </svg>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="width:100%;">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="width:100%;">
          <li class="nav-item" style="width:17%;">
            <a id='btnChamarPagamento' onclick="validacao(2, <?= $codnota; ?>, 0, 2)" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalPagamento" style="width:100%;" class="btn btn-outline-primary" aria-current="page" href="#">F2 - PAGAMENTO</a>
          </li>
          <li class="nav-item" style="width:17%;">
            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCliente" style="width:100%;" class="btn btn-outline-primary" href="#">F3 - CLIENTES</a>
          </li>

          <li class="nav-item" style="width:17%;">
            <a style="width:100%;" target="_blank" class="btn btn-outline-primary" href="cupom.php?cod=<?= $codnota ?>">F4 - CUPOM Ñ FISCAL</a>
          </li>


          <li class="nav-item" style="width:17%;">
            <a onclick="validacao(5, <?= $codnota; ?>, 0, 5)" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalEditarItem" style="width:100%;" class="btn btn-outline-primary" href="#">F6 - EDITAR ULT. ITEM</a>
          </li>

          <li class="nav-item" style="width:17%;">
            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModalItemAvulso" style="width:100%;" class="btn btn-outline-primary" href="#">F7 - ITEM AVULSO</a>
          </li>

          <li class="nav-item" style="width:17%;">
            <a onclick="validacao(4, <?= $codnota; ?>, 0, 3)" type="button" data-bs-toggle="modal" data-bs-target="#exampleModalListaItens" style="width:100%;" class="btn btn-outline-primary" href="#">F8 - LISTA DE ITENS</a>
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
        <svg style="fill: #0000ff;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-box-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
        </svg>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent" style="width:100%;">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="width:100%;">

          <li class="nav-item" style="width:33%;">
            <a style="width:100%;" target="_blank" class="btn btn-outline-primary" href="cupom.php?cod=<?= $codnota ?>">F4 - CUPOM Ñ FISCAL</a>
          </li>

          <li class="nav-item" style="width:33%;">
            <a tabindex="5" style="width:100%;" class="btn btn-outline-secondary" href="">ORDEM DE SERVIÇO</a>
          </li>

          <li class="nav-item" style="width:33%;">
            <a tabindex="6" style="width:100%;" class="btn btn-outline-danger" href="index.php?">SAIR DO CARRINHO</a>
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
    <?php if ($dataTableTestePag == null) { ?>
      <input tabindex="1" onkeyup="validacao(1, <?= $codnota; ?>, this.value, 1)" autofocus style="width:97%; margin-left:5%; padding:20px; margin-left:2%; margin-bottom:10px; margin-top:-30px;" class="form-control" type="text" placeholder="Pesquisar item..." aria-label="Search" name='txtPesquisarProd' id='txtPesquisarProd'>
      <div class="row d-flex justify-content-center my-4">

        <div class="col-md-8">
          <div class="card mb-4" id='ResultadoValidacao1'>
            Nenhum item localizado...
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="card-header py-3">
              <h5 class="mb-0">Resumo Pedido</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id='ResultadoValidacaoAddItem'>
                <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                  Total
                  <span id=''>R$ <?= $valor_total_pedido; ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                  Taxa de Entrega
                  <span>Gratis</span>
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
              <p><strong>Informações do Pedido</strong></p>
              <p class="mb-0">Data e Hora de Início: <b><?= $data_hora; ?></b></p>
              <p class="mb-0">Funcionário: <b><?= $nome_func; ?></b></p>
              <p class="mb-0">Cliente: <b><?= $textocliente; ?></b></p>
              <p class="mb-0">Tipo de Venda: <b><?= $texto_tipo; ?></b></p>
              <p class="mb-0" style="text-align: center;"><a href='index.php?' class='btn btn-danger' style='padding:20px; font-size:9pt; margin:5%;'>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                  </svg>
                  <b>Sair do Carrinho</b></a></p>
            </div>
          </div>

        </div>
      </div>

    <?php } else {
    ?>
      <div class="row">
        <div class="card" style="width:33%;">
          <div class="card-body">
            <h5 id="offcanvasTopLabel" class="">Venda Expressa</h5>
            <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
              <input name='txtCodCliente' id='txtCodCliente' value='0' type='hidden' />
              <input name='txtCod_caixa' id='txtCod_caixa' value='0' type='hidden' />
              <input name='txtTipoEntrega' id='txtTipoEntrega' value='1' type='hidden' />

              <input tabindex="1" autofocus style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-success' type='submit' name='btnSubmitPedirVendaExpressa' id='btnSubmitPedirVendaExpressa' style='' value='Inciar Venda Expressa'>
            </form>

          </div>
        </div>
        <div class="card" style="width:33%;">
          <div class="card-body">
            <h5 id="offcanvasTopLabel" class="">Venda Retirada no Balcão</h5>
            <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
              <input name='txtCodCliente' id='txtCodCliente' value='0' type='hidden' />
              <input name='txtCod_caixa' id='txtCod_caixa' value='1' type='hidden' />
              <input name='txtTipoEntrega' id='txtTipoEntrega' value='2' type='hidden' />

              <input tabindex="2" style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-danger' type='submit' name='btnSubmitPedirVendaExpressa' id='btnSubmitPedirVendaExpressa' style='' value='Inciar Venda Retirada'>
            </form>
          </div>
        </div>
        <div class="card" style="width:33%;">
          <div class="card-body">
            <h5 id="offcanvasTopLabel" class="">Venda Online para Entrega</h5>
            <form onsubmit='return ConfirmarIsso();' method='post' name='frmCadastro2' id='frmCadastro2' novalidate enctype='multipart/form-data'>
              <input name='txtCodCliente' id='txtCodCliente' value='0' type='hidden' />
              <input name='txtCod_caixa' id='txtCod_caixa' value='0' type='hidden' />
              <input name='txtTipoEntrega' id='txtTipoEntrega' value='3' type='hidden' />
              <input tabindex="3" style='padding:15px; font-size: 9pt; width: 100%;  font-weight: bold' class='btn btn-outline-primary' type='submit' name='btnSubmitPedirVendaExpressa' id='btnSubmitPedirVendaExpressa' style='' value='Inciar Venda Entrega'>
            </form>
          </div>
        </div>
      </div>

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

            ?>
          </div>
        </div>
        <div class="col-md-4">
          <?php if ($dataTableTestePag == null) { ?>

            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="mb-0">Resumo Pedido</h5>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush" id='ResultadoValidacaoAddItem'>
                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                    Total
                    <span id=''>R$ <?= $valor_total_pedido; ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    Taxa de Entrega
                    <span>Gratis</span>
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

            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="mb-0">Resumo do Pagamento</h5>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush" id=''>
                  <?php
                  if($tipopagamento == 2){
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Crédiário Parcelado em <b><?= $numparcelas;?>     x</b>
                      <span id=''><b>R$ <?= number_format($valorparcela, 2, ',', '.');; ?></b></span>
                    </li>
                    <?php
                  }

                  if ($dinheiropag != 0) {
                    $dinheiropag = number_format($dinheiropag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Dinheiro
                      <span id=''><b>R$ <?= $dinheiropag; ?></b></span>
                    </li>
                  <?php } ?>
                  <?php
                  if ($pixpag != 0) {
                    $pixpag = number_format($pixpag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Pix
                      <span><b>R$ <?= $pixpag; ?></b></span>
                    </li>
                  <?php
                  }
                  ?>
                  <?php
                  if ($debitopag != 0) {
                    $debitopag = number_format($debitopag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Cartao de Débito
                      <span><b>R$ <?= $debitopag; ?></b></span>
                    </li>
                  <?php
                  }
                  ?>
                  <?php
                  if ($creditopag != 0) {
                    $creditopag = number_format($creditopag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Cartão de Crédito
                      <span><b>R$ <?= $creditopag; ?></b></span>
                    </li>
                  <?php
                  }
                  ?>
                  <?php
                  if ($subtotalpag != 0) {
                    $subtotalpag = number_format($subtotalpag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Subtotal
                      <span><b>R$ <?= $subtotalpag; ?></b></span>
                    </li>
                  <?php
                  }
                  ?>
                  <?php
                  if ($descontopag != 0) {
                    $descontopag = number_format($descontopag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                      Desconto
                      <span><b>R$ <?= $descontopag; ?></b></span>
                    </li>
                  <?php
                  }
                  ?>
                  <?php
                  if ($totalpag != 0) {
                    $totalpag = number_format($totalpag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                      <div>
                        <strong>Total Final</strong>
                      </div>
                      <span><strong><b>R$ <?= $totalpag; ?></b></strong></span>
                    </li>
                  <?php
                  }
                  ?>
                  <?php
                  if ($gorjetapag != 0) {
                    $gorjetapag = number_format($gorjetapag, 2, ',', '.');

                  ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                      <div>
                        <strong>Troco</strong>
                      </div>
                      <span><strong>R$ <?= $gorjetapag; ?></strong></span>
                    </li>
                  <?php
                  }
                  ?>
                </ul>
              </div>
            </div>
          <?php } ?>
          <div class="card mb-4">
            <div class="card-body">
              <p><strong>Informações do Pedido</strong></p>
              <p class="mb-0">Data e Hora de Início: <b><?= $data_hora; ?></b></p>
              <p class="mb-0">Funcionário: <b><?= $nome_func; ?></b></p>
              <p class="mb-0">Cliente: <b><?= $textocliente; ?></b></p>
              <p class="mb-0">Tipo de Venda: <b><?= $texto_tipo; ?></b></p>
              <p class="mb-0" style="text-align: center;">


                <?php if ($dataTableTestePag == null) { ?>
                  <a href='index.php?' class='btn btn-danger' style='padding:20px; font-size:9pt; margin:5%;'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                      <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmar o Pagamento!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='ResultadoValidacao10'>
        <?PHP
        $validacaoform = 0;
        if ($tipo_entrega == 1) {
          $validacaoform = 1;
        } else if ($tipo_entrega == 2) {
          if ($codcliente != 0) {
            $validacaoform = 1;
          } else {
            echo "  
            <a type='button' data-bs-toggle='modal' data-bs-target='#exampleModalCliente' style='width:100%;' class='btn btn-outline-primary' href='#'>F3 - CLIENTES</a>
          ";
          }
        } else if ($tipo_entrega == 3) {
          if ($codcliente != 0) {

            $sqlClientes = "SELECT * FROM clientes WHERE id = :cod ORDER BY id ASC";
            $paramClientes = array(
              ":cod" => $codcliente
            );

            $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
            foreach ($dataTableClientes as $resultadoclientes) {
              $codcliente = $resultadoclientes['id'];
              $nomecliente = $resultadoclientes['nome'];
              $cpf = $resultadoclientes['cpf'];
              $celular = $resultadoclientes['celular'];
              $endereco = $resultadoclientes['endereco'];
              $bairro = $resultadoclientes['bairro'];
              $numero = $resultadoclientes['numero'];
            }

            if ($endereco != null && $bairro != null && $numero != null) {
              $validacaoform = 1;
            } else {
              echo "<div class='alert alert-danger'><b>Será necessário atualizar o cadastro do Cliente para finalizar o pagamento!</b></div>";
        ?>
              <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;' class="row g-3 needs-validation" novalidate>

                <div class="col-md-12">
                  <label for="txtEnderecoCadCom" class="form-label">Endereço</label>
                  <input type="text" class="form-control" id="txtEnderecoCadCom" name='txtEnderecoCadCom' required>
                  <div class="invalid-feedback">
                    Digite o endereço do cliente corretamente!
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="txtBairroCadCom" class="form-label">Bairro</label>
                  <select class="form-select" id="txtBairroCadCom" name='txtBairroCadCom' required>
                    <?php

                    $sqlPedidos = "SELECT * FROM bairros ORDER BY cod ASC";
                    $dataTablePedidos = $banco->ExecuteQuery($sqlPedidos);
                    foreach ($dataTablePedidos as $resultadopedidos) {

                      $codbairro = $resultadopedidos['cod'];
                      $nomebairro = $resultadopedidos['descricao_bairro'];

                      echo "<option value='$codbairro'>$nomebairro</option>";
                    }
                    ?>

                  </select>
                  <div class="invalid-feedback">
                    Selecione o bairro do endereço!
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="txtNCadCom" class="form-label">nº</label>
                  <input type="text" class="form-control" id="txtNCadCom" name='txtNCadCom' required>
                  <div class="invalid-feedback">
                    Digite o número do endereço!
                  </div>
                </div>
                <div class="col-md-12">
                  <label for="txtComplementoCadCOm" class="form-label">Complemento</label>
                  <input type="text" class="form-control" id="txtComplementoCadCOm" name='txtComplementoCadCOm'>
                  <div class="invalid-feedback">
                    Digite um complemento do endereço!
                  </div>
                </div>


                <div class="col-12">
                  <input name='txtCodnota' id='txtCodnota' value='<?= $codnota; ?>' type='hidden' />
                  <input name='txtCodcliente' id='txtCodcliente' value='<?= $codcliente; ?>' type='hidden' />
                  <input style='width:100%; padding:20px; font-size:18pt;' class='btn btn-outline-success' type='submit' name='btnSubmitClienteAlterarDados' id='btnSubmitClienteAlterarDados' value='Atualizar Dados do Cliente'>


                </div>
              </form>
        <?php
            }
          } else {
            echo "  
            <a type='button' data-bs-toggle='modal' data-bs-target='#exampleModalCliente' style='width:100%;' class='btn btn-outline-primary' href='#'>F3 - CLIENTES</a>
          ";
          }
        }
        ?>
        <?php
        if ($validacaoform == 1) { ?>
          <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;'>

            <input value='1' name='txtTipoPag' id='txtTipoPag' type="hidden" />
            <input value='1' name='txtTipo' id='txtTipo' type="hidden" />

            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <svg style="fill:#00FF00;" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#fce57e" class="bi bi-cash-coin" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                  <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                  <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                  <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                </svg>
              </span>
              <input onkeyup="" name='txtDinheiro' id='txtDinheiro' type="text" class="form-control" placeholder="Dinheiro" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="30" height="30" baseProfile="basic">
                  <path fill="#37c6d0" d="M19.262,44.037l-8.04-8.04L11,35l-1.777-1.003l-5.26-5.26c-2.617-2.617-2.617-6.859,0-9.475	l5.26-5.26L11,13l0.223-0.997l8.04-8.04c2.617-2.617,6.859-2.617,9.475,0l8.04,8.04L37,13l1.777,1.003l5.26,5.26	c2.617,2.617,2.617,6.859,0,9.475l-5.26,5.26L37,35l-0.223,0.997l-8.04,8.04C26.121,46.653,21.879,46.653,19.262,44.037z" />
                  <path d="M35.79,11.01c-1.76,0.07-3.4,0.79-4.63,2.04l-6.81,6.77c-0.09,0.1-0.22,0.15-0.35,0.15	s-0.25-0.05-0.35-0.15l-6.8-6.76c-1.24-1.26-2.88-1.98-4.64-2.05L8.22,15h3.68c0.8,0,1.55,0.31,2.12,0.88l6.8,6.78	c0.85,0.84,1.98,1.31,3.18,1.31s2.33-0.47,3.18-1.31l6.79-6.78C34.55,15.31,35.3,15,36.1,15h3.68L35.79,11.01z M36.1,33	c-0.8,0-1.55-0.31-2.12-0.88l-6.8-6.78c-0.85-0.84-1.98-1.31-3.18-1.31s-2.33,0.47-3.18,1.31l-6.79,6.78	C13.45,32.69,12.7,33,11.9,33H8.22l3.99,3.99c1.76-0.07,3.4-0.79,4.63-2.04l6.81-6.77c0.09-0.1,0.22-0.15,0.35-0.15	s0.25,0.05,0.35,0.15l6.8,6.76c1.24,1.26,2.88,1.98,4.64,2.05L39.78,33H36.1z" opacity=".05" />
                  <path d="M36.28,11.5H36.1c-1.74,0-3.38,0.68-4.59,1.91l-6.8,6.77c-0.19,0.19-0.45,0.29-0.71,0.29	s-0.52-0.1-0.71-0.29l-6.79-6.77c-1.22-1.23-2.86-1.91-4.6-1.91h-0.18l-3,3h3.18c0.93,0,1.81,0.36,2.48,1.02l6.8,6.78	c0.75,0.76,1.75,1.17,2.82,1.17s2.07-0.41,2.82-1.17l6.8-6.77c0.67-0.67,1.55-1.03,2.48-1.03h3.18L36.28,11.5z M36.1,33.5	c-0.93,0-1.81-0.36-2.48-1.02l-6.8-6.78c-0.75-0.76-1.75-1.17-2.82-1.17s-2.07,0.41-2.82,1.17l-6.8,6.77	c-0.67,0.67-1.55,1.03-2.48,1.03H8.72l3,3h0.18c1.74,0,3.38-0.68,4.59-1.91l6.8-6.77c0.19-0.19,0.45-0.29,0.71-0.29	s0.52,0.1,0.71,0.29l6.79,6.77c1.22,1.23,2.86,1.91,4.6,1.91h0.18l3-3H36.1z" opacity=".07" />
                  <path fill="#fff" d="M38.78,14H36.1c-1.07,0-2.07,0.42-2.83,1.17l-6.8,6.78c-0.68,0.68-1.58,1.02-2.47,1.02	s-1.79-0.34-2.47-1.02l-6.8-6.78C13.97,14.42,12.97,14,11.9,14H9.22l2-2h0.68c1.6,0,3.11,0.62,4.24,1.76l6.8,6.77	c0.59,0.59,1.53,0.59,2.12,0l6.8-6.77C32.99,12.62,34.5,12,36.1,12h0.68L38.78,14z M36.1,34c-1.07,0-2.07-0.42-2.83-1.17l-6.8-6.78	c-1.36-1.36-3.58-1.36-4.94,0l-6.8,6.78C13.97,33.58,12.97,34,11.9,34H9.22l2,2h0.68c1.6,0,3.11-0.62,4.24-1.76l6.8-6.77	c0.59-0.59,1.53-0.59,2.12,0l6.8,6.77C32.99,35.38,34.5,36,36.1,36h0.68l2-2H36.1z" />
                </svg>

              </span>
              <input name='txtPix' id='txtPix' type="text" class="form-control" placeholder="Pix" aria-label="Username" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <svg style="fill:#0000ff;" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                  <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                </svg>

              </span>
              <input name='txtCardebitoPag' id='txtCardebitoPag' type="text" class="form-control" placeholder="Cartão Débito" aria-label="Username" aria-describedby="basic-addon1" />
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <svg style="fill:#FF0000;" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-credit-card" viewBox="0 0 16 16">
                  <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1z" />
                  <path d="M2 10a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                </svg>

              </span>
              <input name='txtCarcreditoPag' id='txtCarcreditoPag' type="text" class="form-control" placeholder="Cartão Crédito" aria-label="Username" aria-describedby="basic-addon1" />
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5" />
                  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                </svg>

              </span>
              <input name='txtDesconto' id='txtDesconto' type="text" class="form-control" placeholder="Desconto" aria-label="Username" aria-describedby="basic-addon1" />
            </div>
            <div id='ResultadoValidacao2'>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                    <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518z" />
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                    <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11m0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12" />
                  </svg>
                </span>
                <input name='txtTotalReal' id='txtTotalReal' type="hidden" value='<?= $codnota; ?>'>
                <input name='txtTotal' id='txtTotal' disabled type="text" class="form-control" placeholder="Valor Total" aria-label="Username" aria-describedby="basic-addon1">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">
                  <svg style='fill:#FF0000;' xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-left-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5" />
                  </svg>

                </span>
                <input name='txtTrocoReal' id='txtTrocoReal' type="hidden">
                <input disabled name='txtTroco' id='txtTroco' type="text" class="form-control" placeholder="Troco" aria-label="Username" aria-describedby="basic-addon1">
              </div>
              <input value='Finalizar Pagamento' style='width:100%; padding:10px;' id='btnFinalizarPagamento' name='btnFinalizarPagamento' type='submit' class='btn btn-outline-success' />

            </div>
          </form>
        <?php }
        if ($codcliente != 0) {
        ?>
          <Hr>
          <button onclick='validacao(10, <?= $codnota; ?>, 0, 10)' style='width:100%;' id='btnCrediario' style="padding:10px; font-size:12pt;" type="button" class="btn btn-outline-danger">Crediário</button>
        <?php } ?>
      </div>
    </div>

  </div>
</div>

<!-- Modal Cliente -->

<div class="modal fade" id="exampleModalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Painel do cliente!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="accordion" id="accordionExample">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button onclick="FuncaoChamarBotao4()" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Pesquisar Cliente
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <div class="col-md-12">
                  <label for="validationCustom01" class="form-label" style="width:100%;">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group" style="width:100%;">

                      <input type="radio" class="btn-check" name="btnradio" id="btnradioNomePesc" autocomplete="off" checked>
                      <label class="btn btn-outline-primary" for="btnradioNomePesc">Por Nome</label>

                      <input type="radio" class="btn-check" name="btnradio" id="btnradioContatoPesc" autocomplete="off">
                      <label class="btn btn-outline-primary" for="btnradioContatoPesc">Por Contato</label>


                      <input type="radio" class="btn-check" name="btnradio" id="btnradioCPFPesc" autocomplete="off">
                      <label class="btn btn-outline-primary" for="btnradioCPFPesc">Por CPF</label>


                    </div>


                  </label>
                  <div id='divpesquisapornome'>
                    <input onkeyup="validacao(9, this.value, <?= $codnota; ?>, 9)" type="text" class="form-control form-control-lg" id="txtPesquisarClientePag" value="" placeholder="Pesquisar Cliente por Nome">

                    <div id='ResultadoValidacao9'></div>

                  </div>
                  <div id='divpesquisaporcontato'>
                    <input onkeyup="" type="text" class="form-control form-control-lg" id="txtPesquisarContato" value="" placeholder="Pesquisar Cliente por Contato" required>
                    <div class="valid-feedback">
                      Verifique o que está sendo digitado!.
                    </div>
                    <div id=''></div>

                  </div>
                  <div id='divpesquisaporcpf'>
                    <input onkeyup="" type="text" class="form-control form-control-lg" id="txtPesquisarCpf" value="" placeholder="Pesquisar Cliente por CPF" required>
                    <div class="valid-feedback">
                      Verifique o que está sendo digitado!.
                    </div>
                    <div id=''></div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button onclick="FuncaoChamarBotao3()" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Cadastro Rápido
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;' class="row g-3 needs-validation" novalidate>
                  <div class="col-md-12">
                    <label for="txtNomeCompletoCadRes" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="txtNomeCompletoCadRes" name='txtNomeCompletoCadRes' value="" required>
                    <div class="valid-feedback">
                      Correto!
                    </div>
                  </div>
                  <div class="col-md-12">
                    <label for="txtContatoCadRes" class="form-label">Contato</label>
                    <input type="text" class="form-control" id="txtContatoCadRes" name="txtContatoCadRes" value="" required>
                    <div class="valid-feedback">
                      Correto!
                    </div>
                  </div>

                  <div class="col-12">
                    <input name='txtCodnota' id='txtCodnota' value='<?= $codnota; ?>' type='hidden' />
                    <input style='width:100%; padding:20px; font-size:18pt;' class='btn btn-outline-success' type='submit' name='btnSubmitClienteCadastroRapido' id='btnSubmitClienteCadastroRapido' value='Finalizar Cadastro Rápido'>

                  </div>
              </div>

              </form>

            </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button onclick="FuncaoChamarBotao5()" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              Cadastro Completo
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;' class="row g-3 needs-validation" novalidate>


                <div class="col-md-12">
                  <label for="txtNomeCadCom" class="form-label">Nome Completo</label>
                  <input type="text" class="form-control" id="txtNomeCadCom" name='txtNomeCadCom' value="" required>
                  <div class="valid-feedback">
                    Correto!
                  </div>
                </div>
                <div class="col-md-12">
                  <label for="txtContatoCadCom" class="form-label">Contato</label>
                  <input type="text" class="form-control" id="txtContatoCadCom" name='txtContatoCadCom' value="" required>
                  <div class="valid-feedback">
                    Correto!
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="txtCpfCadCom" class="form-label">CPF</label>
                  <input type="text" class="form-control" id="txtCpfCadCom" name='txtCpfCadCom' value="" required>
                  <div class="valid-feedback">
                    Correto!
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="validationCustomUsername" class="form-label">Data de Nascimento</label>
                  <div class="input-group has-validation">

                    <input type="text" class="form-control" id="txtDataNascimentoCadCom" name='txtDataNascimentoCadCom' aria-describedby="txtDataNascimentoCadCom" required>
                    <div class="invalid-feedback">
                      Correto.
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <label for="txtEnderecoCadCom" class="form-label">Endereço</label>
                  <input type="text" class="form-control" id="txtEnderecoCadCom" name='txtEnderecoCadCom' required>
                  <div class="invalid-feedback">
                    Digite o endereço do cliente corretamente!
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="txtBairroCadCom" class="form-label">Bairro</label>
                  <select class="form-select" id="txtBairroCadCom" name='txtBairroCadCom' required>
                    <?php

                    $sqlPedidos = "SELECT * FROM bairros ORDER BY cod ASC";
                    $dataTablePedidos = $banco->ExecuteQuery($sqlPedidos);
                    foreach ($dataTablePedidos as $resultadopedidos) {

                      $codbairro = $resultadopedidos['cod'];
                      $nomebairro = $resultadopedidos['descricao_bairro'];

                      echo "<option value='$codbairro'>$nomebairro</option>";
                    }
                    ?>

                  </select>
                  <div class="invalid-feedback">
                    Selecione o bairro do endereço!
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="txtNCadCom" class="form-label">nº</label>
                  <input type="text" class="form-control" id="txtNCadCom" name='txtNCadCom' required>
                  <div class="invalid-feedback">
                    Digite o número do endereço!
                  </div>
                </div>
                <div class="col-md-12">
                  <label for="txtComplementoCadCOm" class="form-label">Complemento</label>
                  <input type="text" class="form-control" id="txtComplementoCadCOm" name='txtComplementoCadCOm'>
                  <div class="invalid-feedback">
                    Digite um complemento do endereço!
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                    <label class="form-check-label" for="invalidCheck">
                      Todas as informações foram cadastadas corretamente?
                    </label>
                    <div class="invalid-feedback">
                      Verifique a opção anterior.
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <input name='txtCodnota' id='txtCodnota' value='<?= $codnota; ?>' type='hidden' />
                  <input style='width:100%; padding:20px; font-size:18pt;' class='btn btn-outline-success' type='submit' name='btnSubmitClienteCadastroCompleto' id='btnSubmitClienteCadastroCompleto' value='Finalizar Cadastro Completo'>


                </div>
              </form>
            </div>
          </div>
        </div>
                  </DIV>
      </div>
    </div>
  </div>
</div>

<!-- Modal Editar Item-->
<div class="modal fade" id="exampleModalEditarItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar último item!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form onsubmit='return ConfirmarIsso();' name='form_cadastrareditaritem' method='post' action='' class="row g-3 needs-validation" novalidate>
          <div class="col-md-12">
            <label for="txtContatoCadRes" class="form-label">Qtd</label>
            <input onkeyup="validacao(6, this.value, <?= $codnota; ?>, 6);" type="text" class="form-control form-control-lg" id="txtQtdEd" name='txtQtdEd' value="1" required>
            <div class="valid-feedback">
              Correto!
            </div>
          </div>
          <div id='ResultadoValidacao5'>
            <div class="col-md-12">
              <label for="txtNomeCompletoCadRes" class="form-label">Descrição do Item</label>
              <input type="text" class="form-control form-control-lg" id="txtDescricaoEd" name='txtDescricaoEd' value="" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div class="col-md-6">
              <label for="txtContatoCadRes" class="form-label">Valor Unt.</label>
              <input type="text" class="form-control form-control-lg" id="txtValorUntEd" name='txtValorUntEd' value="" required>
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
              <button class="btn btn-outline-success" type="submit" style="width:100%; padding:20px;">Atualizar Item</button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
</div>

<!-- Modal Item Avulso -->
<div class="modal fade" id="exampleModalItemAvulso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Itens Avulso!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form onsubmit='return ConfirmarIsso();' name='form_cadastrarmovimento' method='post' action='' style='margin-top:5px; width:100%;'>
          <input type='hidden' value='<?= $codnota; ?>' id='txtCodnota' name='txtCodnota' />

          <div class='row'>
            <div class="col-md-4">
              <label for="txtContatoCadRes" class="form-label">Qtd</label>
              <input onkeyup='validacao(7, this.value, txtValoruntAvulso.value, 7)' type="text" class="form-control form-control-lg" id="txtQtdAvulso" name='txtQtdAvulso' value="1" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>

            <div class="col-md-4">
              <label for="txtValorUntAvulso" class="form-label">Valor Unt.</label>
              <input onkeyup='' type="text" class="form-control form-control-lg" id="txtValoruntAvulso" name='txtValoruntAvulso' value="1,00" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div class="col-md-4" id='ResultadoValidacao7'>
              <label for="txtNomeCompletoCadRes" class="form-label">Valor Total</label>
              <input type="hidden" id="txtValorTotalAvulsoReal" id='txtValorTotalAvulsoReal' value="1,00" required>
              <input type="text" class="form-control form-control-lg" id="txtValorTotalAvulso" name='txtValorTotalAvulso' disabled value="1,00" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>
            <div class="col-md-12">
              <label for="txtNomeCompletoCadRes" class="form-label">Descrição do Item</label>
              <input type="text" class="form-control form-control-lg" id="txtDescricaoAvulso" name='txtDescricaoAvulso' value="ITEM AVULSO" required>
              <div class="valid-feedback">
                Correto!
              </div>
            </div>


            <div class="col-12">
              <input value='Cadastrar Item Avulso' style='width:100%; padding:10px;' id='btnCadastrarItemAvulso' name='btnCadastrarItemAvulso' type='submit' class='btn btn-outline-success' />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Lista de Itens -->
<div class="modal fade" id="exampleModalListaItens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Lista de Itens!</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id='ResultadoValidacao3'>

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
  const myInputCli = document.getElementById('txtPesquisarClientePag')

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



  document.addEventListener('DOMContentLoaded', function() {
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
    moneyInput.addEventListener('input', function() {
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



  document.addEventListener('DOMContentLoaded', function() {
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
    moneyInput.addEventListener('input', function() {
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

  document.addEventListener('DOMContentLoaded', function() {
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
    moneyInput.addEventListener('input', function() {
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
  document.addEventListener('DOMContentLoaded', function() {
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
    moneyInput.addEventListener('input', function() {
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
  document.addEventListener('DOMContentLoaded', function() {
    var moneyInput = document.getElementById('txtDesconto');
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
    moneyInput.addEventListener('input', function() {
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
  

  document.addEventListener('DOMContentLoaded', function() {
    var moneyInput = document.getElementById('txtValorUntEd');


    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: 'R$ ', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function() {
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

  document.addEventListener('DOMContentLoaded', function() {
    var moneyInput = document.getElementById('txtValoruntAvulso');

    VMasker(moneyInput).maskMoney({
      precision: 2, // Número de casas decimais
      separator: ',', // Separador de casas decimais
      delimiter: '.', // Separador de milhares
      unit: '', // Unidade monetária
      zeroCents: false // Se true, vai sempre mostrar os centavos como .00
    });

    // Atualiza a máscara enquanto o usuário digita
    moneyInput.addEventListener('input', function() {
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

  document.addEventListener('keydown', function(event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F2') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalPagamento'));
      myModal.show();
      validacao(2, <?= $codnota; ?>, 0, 2);
    }
  });


  document.addEventListener('keydown', function(event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F3') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalCliente'));
      myModal.show();
      validacao(2, <?= $codnota; ?>, 0, 2);
    }
  });

  document.addEventListener('keydown', function(event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F4') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)

      window.open('cupom.php?cod=<?= $codnota ?>');

    }
  });

  document.addEventListener('keydown', function(event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F6') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalEditarItem'));
      myModal.show();
      validacao(5, <?= $codnota; ?>, 0, 5);
    }
  });

  document.addEventListener('keydown', function(event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F7') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalItemAvulso'));
      myModal.show();

    }
  });


  document.addEventListener('keydown', function(event) {
    // Verificar se Ctrl e S foram pressionados
    if (event.key === 'F8') {
      event.preventDefault(); // Previne a ação padrão do navegador (salvar página)
      var myModal = new bootstrap.Modal(document.getElementById('exampleModalListaItens'));
      myModal.show();

      validacao(4, <?= $codnota; ?>, 0, 3);

    }
  });
  //FIM DAS FUNCOES PARA CHAAMAR O ATALHO

  document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("divpesquisaporcontato").style.display = "none";
    document.getElementById("divpesquisaporcpf").style.display = "none";
  });

  //FUNCAO PARA ALTERNAR DIV NO INPUT
  const radioButtons = document.querySelectorAll('input[name="btnradio"]');

  // Adiciona um manipulador de evento 'change' a cada botão de rádio
  radioButtons.forEach(radio => {
    radio.addEventListener('change', function() {
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