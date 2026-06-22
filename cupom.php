<?php
require_once("Model/Usuarios.php");
require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Servicos.php");
require_once("Model/CategoriaSerFin.php");

require_once("Controller/NotasController.php");
require_once("Controller/ClientesController.php");
require_once("Controller/UsuariosController.php");
require_once("Controller/NotasController.php");
require_once("Controller/PedidosController.php");
require_once("Controller/ServicoController.php");
require_once("Controller/CategoriaSerFinController.php");

$notasController = new NotasController();
$clientesController = new ClientesController();
$usuarioController = new UsuarioController();
$notasController = new NotasController();
$servicoController = new ServicoController();
$pedidosController = new PedidosController;
$categoriaserfinController = new CategoriaSerFinController();

date_default_timezone_set('America/Manaus');

$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$tipo = 2;

$listaNotas = $notasController->RetornarNotas($tipo, $cod);

$banco = new Banco();

$sqlTestePag2 = "SELECT * FROM notas WHERE cod = :cod ORDER BY cod ASC";
$paramTestePag2 = array(
    ":cod" => $cod
);
$dataTableTestePag2 = $banco->ExecuteQuery($sqlTestePag2, $paramTestePag2);

foreach ($dataTableTestePag2 as $resultadonomecli) {
    $ordem = $resultadonomecli['ordem'];
    $tipo_pedido = $resultadonomecli['tipo_pedido'];
    $cod_pedido = $resultadonomecli['cod'];
    if ($tipo_pedido == 1) {
        $ordem = $ordem;
    } else {
        $ordem = 'E' . $cod_pedido;
    }
}

$tipo_crediario = 0;

$texto_assinatura = "";
$textoparapagamentoavista = "";

$sqlTestePag = "SELECT * FROM financeiro_clientes WHERE cod_orcamento = :cod ORDER BY cod ASC";
$paramTestePag = array(
    ":cod" => $cod
);
$nomecategoria = "Avulso";
$dataTableTestePag = $banco->ExecuteQuery($sqlTestePag, $paramTestePag);
if ($dataTableTestePag != null) {


    foreach ($dataTableTestePag as $resultadonomecli) {
        $nomecli = $resultadonomecli['tipo'];
        $tipopag = $resultadonomecli['tipopag'];


        $dinheiropag = $resultadonomecli['dinheiro'];
        $debitopag = $resultadonomecli['debito'];
        $creditopag = $resultadonomecli['credito'];
        $pixpag = $resultadonomecli['pix'];
        $descontopag = $resultadonomecli['desconto'];

 $pagamentototal2 = (float) $resultadonomecli['total'];
          $finaltermo2 = "
            
                   
            <tr>
                <td style='width:10%;'></td>
                <th scope='row'></th>
                <td>Total</br> Final:</td>
                <th scope='row'>
                R$ " . number_format($pagamentototal2, 2, ',', '.') . "      
                </th>
            </tr>
             <tr>
                    <td colspan='4'>
          ----------------------------------------
                       </td>
                   </tr>";




        if ($nomecli == 1) {
            $textopagamento = "PAGAMENTO À VISTA";
            $troco = $resultadonomecli['gorjeta'];
            if ($troco > 0) {
                $trocovalor = "Troco: R$ " . $troco;
            } else {
                $trocovalor = "";
            }
            if($dinheiropag!=0){
                    $textoparapagamentoavista = $textoparapagamentoavista . "Dinheiro: R$ ". number_format($dinheiropag, 2, ',', '.') ;
                }
                  if($pixpag!=0){
                    $textoparapagamentoavista = $textoparapagamentoavista . "</br>Pix: R$ ". number_format($pixpag, 2, ',', '.') ;
                }
                  if($debitopag!=0){
                    $textoparapagamentoavista = $textoparapagamentoavista . "</br>Débito: R$ ". number_format($debitopag, 2, ',', '.') ;
                }
                  if($creditopag!=0){
                    $textoparapagamentoavista = $textoparapagamentoavista . "</br>Crédito: R$ ". number_format($creditopag, 2, ',', '.') ;
                }
                  if($descontopag!=0){
                    $textoparapagamentoavista = $textoparapagamentoavista . "</br>Desconto: R$ ". number_format($descontopag, 2, ',', '.') ;
                }



                $textopagamento = $textopagamento ."</br>". $textoparapagamentoavista;

            $textotipocrediario = "";
            $valorentrada = 0;
        } else if ($nomecli == 2) {
            $Tipopag = $resultadonomecli['tipopag'];
            $tipo_crediario = $resultadonomecli['tipo_crediario'];

            if ($tipo_crediario == 1) {
                $textotipocrediario = "DA LOJA";
            } else {
                $textotipocrediario = "DA AVANCARD";
            }
            if ($Tipopag == 1) {
             //   $textopagamento = "PAGAMENTO À VISTA";

                
            } else if ($Tipopag == 2) {

                $texto_assinatura = "<H1>________________________________________</br>
                    Assinatura do Cliente</H1>";
                $pagamentototal = (float) $resultadonomecli['total'];
                $valorentrada = (float) $resultadonomecli['entrada'];
                $pagamentototal2 = $pagamentototal;
                $numparcelas = (float) $resultadonomecli['numparcelas'];
                $valorparcela = $pagamentototal / $numparcelas;
                $valorparcela = number_format($valorparcela, 2, ',', '.');
                $valorentrada = number_format($valorentrada, 2, ',', '.');
                if ($tipo_crediario == 1) {
                    $textopagamento = "PAGAMENTO NO CREDIÁRIO $textotipocrediario EM $numparcelas" . "x de $valorparcela" . ".</BR> + Entrada de R$ $valorentrada";

                } else {
                    $textopagamento = "PAGAMENTO NO CREDIÁRIO $textotipocrediario EM $numparcelas" . "x" . ".</BR> + Entrada de R$ $valorentrada";

                }

               
            }
            
        }
       
    }

} else {

    $textopagamento = "";
}

if ($listaNotas != null) {
    foreach ($listaNotas as $user4) {
        $o = $user4->getCod();
        $codusuario = $user4->getUsuario();
        $termo = "Nós";
        $tipo = 1;
        $status = $o;
        $listaPedidos = $pedidosController->RetornarPedidos($termo, $tipo, $status);

        $cont = 0;
        $qtdfinal = 0;
        $valorTotal = 0;

        $meiotermo = "";
        $finaltermo = "";

        if ($listaPedidos != null) {
            foreach ($listaPedidos as $user4) {
                if ($user4->getStatus() != 0) {
                    $cont++;
                    $cod_dente = $user4->getDente();
                    $cod_servico = $user4->getServico();
                    $cod_proc = $user4->getCod();
                    $statuspedido = $user4->getStatus();
                    $categoriaproduto = $user4->getCategoria();
                    $obs = $user4->getObs();

                    $qtd = $user4->getQtd();
                    $valor_procedimento = $user4->getValor();
                    $string = $valor_procedimento;
                    $stringCorrigida = str_replace(',', '', $string);
                    if ($statuspedido != 0) {
                        $valorTotal = $valorTotal + (float) $stringCorrigida;

                        $qtdfinal = $qtdfinal + (float) $qtd;
                    }
                    $valor_procedimento = number_format((float) $stringCorrigida, 2, ',', '.');

                    $obs = $user4->getObs();
                    if ($cod_servico != 0) {
                        ?>
                        <?php
                        $listaServicos = $servicoController->RetornarServicos2($cod_servico);

                        if ($listaServicos != null) {
                            foreach ($listaServicos as $user1) {

                                $nome = $user1->getNome();
                                $descricao = $user1->getDescricao();
                                $valor_padrao = $user1->getValor();
                            }
                        }
                        $nomeCategoria = $categoriaserfinController->RetornarNomeCat($categoriaproduto);
                        $meiotermo = $meiotermo . "<tr style='color: #000; font-size:10pt;'>
                                    <td style='width:10%;'>
                                     $qtd
                                    </td>
                                    <td style='width:70%; font-size:12pt;' colspan='2'>
                                         $nome 
                                    </td>
                                    <td style='width:20%;'>
                                        R$  $valor_procedimento
                                    </td>
                                    </tr> 
                                    
                                    <tr>
                                    <Td>Obs:$obs</td>
                                    </tr>
                                    
                   <tr>
                   
                   </tr>
                                    ";
                    } else {
                        $meiotermo = $meiotermo . "
                            <tr style='color: #000; font-size:9pt;'>
                            <td style='width:10%;'>
                                 $qtd
                            </td>
                           <td style='width:70%; font-size:9pt;' colspan='2'>
                                         $obs 
                                    </td>
                            <td style='width:20%;'> 
                                R$ $valor_procedimento
                            </td>
                        </tr>
                        
                   <tr>
                  
                   </tr>
                                    ";
                    }
                }
            }
        }
        $string = $valorTotal;
        $stringCorrigida = str_replace(',', '', $string);
        $valorTotal = number_format($stringCorrigida, 2, ',', '.');

        $finaltermo = "
            
                   
            <tr>
                <td style='width:10%;'>Qtd</br> Total:</td>
                <th scope='row'> $qtdfinal </th>
                <td>Subtotal</br> Final:</td>
                <th scope='row'>
                R$ $valorTotal     
                </th>
            </tr>
               ";
    }
}
if ($listaNotas != null) {
    foreach ($listaNotas as $user4) {
        $cod_paciente2 = $user4->getUsuario();
        $o = $user4->getCod();
        $cod_func = $user4->getFunc();
        //$nomePaciente = $user4->getNomeCli();
        if ($cod_paciente2 == 0) {
            $nomePaciente = $user4->getNomeCli();
        } else {
            $nomePaciente = $nomepaciente3 = $clientesController->RetornarNomeClientes($cod_paciente2);
        }


        if ($cod_paciente2 == 0) {
            $nomePaciente = $user4->getNomeCli();
            if ($nomePaciente == null) {
                $textocliente = "
     <div class='corpo'>  
                  VENDA DIRETA
                </div>";
            } else {
                $textocliente = "
     <div class='corpo'>  
                   $nomePaciente
                </div>";
            }
        } else {
            $nomePaciente = $nomepaciente3 = $clientesController->RetornarNomeClientes($cod_paciente2);
            $nomeFuncionario = $usuarioController->RetornarNomeUsuarios($cod_func);
            $nomeFuncionario2 = $usuarioController->RetornarNomeUsuarios($cod_func);

            $termo = "";
            $status = $cod_paciente2;
            $tipo = 2;
            $listaClientesBusca = $clientesController->RetornarClientes($termo, $tipo, $status);
            if ($listaClientesBusca != null) {
                foreach ($listaClientesBusca as $user) {
                    $endereco = $user->getEndereco();
                    $cpfcliente = $user->getCpf();
                    $bairroCLI = $user->getBairro();
                    $numerocasa = $user->getNumero();
                    $complemento = $user->getComplemento();
                    $whatsapp = $user->getResidencial();
                    $celularcliente = $user->getCelular();
                }
            }

            $textocliente = "
     <div class='corpo'>  
                    Cliente:$nomePaciente
                </div>
                <div class='corpo'>  
                    CPF:$cpfcliente
                </div>
                <div class='corpo'>  
                    Endereço:$endereco - Nº: $numerocasa </br>
                        Bairro: $bairroCLI
                </div>
                
                </div>
                <div class='corpo'>  
                    Complemento: $complemento
                </div>
                
                <div class='corpo'>  
                    Celular: $celularcliente
                </div>
    ";
        }
    }
}
$listaUsuariosBusca = [];

$termo = "";
$tipo = 2;
$status = 1;

$listaUsuariosBusca = $usuarioController->RetornarUsuarios($termo, $tipo, $status);

if ($listaUsuariosBusca != null) {
    foreach ($listaUsuariosBusca as $user) {
        $nomeempresa = $user->getNome();
        $email = $user->getEmail();
        $foto = $user->getFoto();
        $cpfempresa = $user->getCpf();
        $rua = $user->getRua();
        $bairro = $user->getBairro();
        $numero = $user->getNumero();
        $celular = $user->getCelular();
    }
}
$nomeFuncionario2 = $usuarioController->RetornarNomeUsuarios($cod_func);

//require_once("mpdf60/mpdf.php");
//$dompdf = new mPDF();
$pdf = "
            <div id='' style='width:100%; margin-left:2px;'>
                
                <div class='cabecalho' style='font-size:14pt;'>                  
                    $nomeempresa 
                </div>
             
                <div class='subcabecalho'>                  
                 CNPJ: $cpfempresa 
                </div>
                
                    $textocliente
                
                <div style='font-size:12pt;'>
                        " . date('d/m/Y H:i') . "
                    </div>
                
                <div class='SUBcorpo' style='font-size:10pt; margin-top:5px;   text-align: justify;'>  
                   <b>$textopagamento </b> 
                </div>   
                
                 
                <div class='SUBcorpo' style='font-size:14pt; text-align:center;s'>  
                    VENDA AO CONSUMIDOR 
                </div>
                <div class='corpo'>  
                    ==============================
                </div>
                <table  style='font-size:10pt; '>
                <tr>
                    <td style='width:10%;'>Qtd</td>
                     <td colspan='2'>Descrição</td>
                      <td>Valor Total</td>
                   </tr>
                   <tr>
                    <td colspan='4'>
            ----------------------------------------
                       </td>
                   </tr>
                 $meiotermo
                $finaltermo
                $finaltermo2
                
                </table>
                </br>
                $texto_assinatura
                
                 <div id='d' style='font-size:10pt; text-align:center;';><B>REGRA DE DEVOLUÇÃO: Sua satisfação é nossa prioridade . Se precisar trocar, bastar trazer a peça intacta e a nota fiscal em até 3 dias úteis. *OBS: NÃO REALIZAMOS TROCA DE PEÇA EM PROMOÇÃO</B></div>		
                 <div id='d' style='font-size:10pt; text-align:center;';><B>Deus Seja Louvado</B></div>		
            </div>";

echo $pdf;
//$mpdf = new mPDF();
//$mpdf->SetDisplayMode('fullpage');
//$css = file_get_contents("Interface/estiloPDF.css");
//$mpdf->WriteHTML($css, 1);
//$mpdf->WriteHTML($pdf);
//$mpdf->Output();
//exit;
?>

<script>
    window.print();
    $(document).ready(function () {

    });
</script>