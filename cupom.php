<?php
require_once("Model/Usuarios.php");
require_once("Model/Clientes.php");
require_once("Model/Notas.php");
require_once("Model/Pedidos.php");
require_once("Model/Servicos.php");

require_once("Controller/NotasController.php");
require_once("Controller/ClientesController.php");
require_once("Controller/UsuariosController.php");
require_once("Controller/NotasController.php");
require_once("Controller/PedidosController.php");
require_once("Controller/ServicoController.php");

$notasController = new NotasController();
$clientesController = new ClientesController();
$usuarioController = new UsuarioController();
$notasController = new NotasController();
$servicoController = new ServicoController();
$pedidosController = new PedidosController;

date_default_timezone_set('America/Manaus');

$cod = filter_input(INPUT_GET, "cod", FILTER_SANITIZE_NUMBER_INT);
$tipo = 2;

$textopedido = "";
$data_hora = "";

$textocliente = "";

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
    
    $dia = $resultadonomecli['dia'];
    $mes = $resultadonomecli['mes'];
    $ano = $resultadonomecli['ano'];
    $hora = $resultadonomecli['hora'];

    $data_hora = $dia.'/'.$mes.'/'.$ano.' '.$hora;

    if ($tipo_pedido == 1) {
        $ordem = 'B' . $cod_pedido;
        $textopedido = 'VENDA EXPRESSA';
    } else if ($tipo_pedido == 2) {
        $ordem = 'R' . $cod_pedido;        
        $textopedido = 'VENDA PARA RETIRADA';
    }else if ($tipo_pedido == 3) {
        $ordem = 'E' . $cod_pedido;
        $textopedido = 'VENDA PARA ENTREGA';
    }
}



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
        if ($nomecli == 1) {
            $troco = $resultadonomecli['gorjeta'];
            if ($troco > 0) {
                $trocovalor = "Troco: R$ " . $troco;
            } else {
                $trocovalor = "";
            }
            if ($tipopag == 1) {
                $textopagamento = "PAGAMENTO EM DINHEIRO. </BR> $trocovalor </BR>";
            } else if ($tipopag == 2) {
                $textopagamento = "PAGAMENTO EM DÉBITO.</BR>";
            } else if ($tipopag == 3) {
                $textopagamento = "PAGAMENTO EM PIX.</BR>";
            }
        } else if ($nomecli == 2) {
            $Tipopag = $resultadonomecli['tipopag'];
            if ($Tipopag == 1) {
                $textopagamento = "PAGAMENTO NO CRÉDITO";
            } else if ($Tipopag == 2) {
                $pagamentototal = (float) $resultadonomecli['total'];
                $pagamentototal2 = $pagamentototal;
                $numparcelas = (float) $resultadonomecli['numparcelas'];
                $valorparcela = $pagamentototal / $numparcelas;
                $valorparcela = number_format($valorparcela, 2, ',', '.');
                $textopagamento = "PAGAMENTO NO CREDIÁRIO EM $numparcelas" . "x" . ".</BR>  Valor da Parcela R$ $valorparcela";
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
        $termo = "";
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
                        //$nomeCategoria = $categoriaserfinController->RetornarNomeCat($categoriaproduto);
                        $sqlClientes = "SELECT * FROM categoriaserfin WHERE cod = :cod ORDER BY cod ASC";
                        $paramClientes = array(
                            ":cod" => $categoriaproduto
                        );

                        $dataTableClientes = $banco->ExecuteQuery($sqlClientes, $paramClientes);
                        foreach ($dataTableClientes as $resultadoclientes) {

                            $codcliente = $resultadoclientes['cod'];
                            $nomeCategoria = $resultadoclientes['nome'];
                        }


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
                            <tr style='color: #000; font-size:10pt;'>
                            <td style='width:10%;'>
                                 $qtd
                            </td>
                           <td style='width:70%; font-size:12pt;' colspan='2'>
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
                <td>Valor</br> Final:</td>
                <th scope='row'>
                R$ $valorTotal     
                </th>
            </tr>
                <tr>
                    <td colspan='4'>
          ----------------------------------------
                       </td>
                   </tr>";
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

        $textipohora = "
        <div class='corpo'>  
                     $textopedido</br>
                     $data_hora
                   </div>";

        if ($cod_paciente2 == 0) {
            $nomePaciente = $user4->getNomeCli();
            
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
                    $textipohora
                    $textocliente
                
                
                <div class='SUBcorpo' style='font-size:10pt; margin-top:5px;   text-align: justify;'>  
                   <b>$textopagamento </b> 
                </div>   
                <div class='corpo'>                 
                                   <div style='font-size:30pt; text-align:center; margin-top:10px; margin-bottom:12px;'>
                       <b style='border:10px solid #000;width:100%;'>$ordem</b>  </br>                   </div> 
                           
                   </div>
                
                 
                <div class='SUBcorpo' style='font-size:14pt; text-align:center;s'>  
                    VENDA AO CONSUMIDOR 
                </div>
                <div class='corpo'>  
                    ==============================
                </div>
                <table  style='font-size:14pt; '>
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
                
                </table>
                 <div id='d' style='font-size:20pt; text-align:center;';><B>Deus Seja Louvado</B></div>		
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
    $(document).ready(function() {

    });
</script>