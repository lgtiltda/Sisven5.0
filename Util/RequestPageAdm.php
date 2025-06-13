<?php
$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_STRING);

$arrayPaginas = array(
    "home" => "View/home.php", //Página inicial
    "hometelao" => "View/homeTELAO.php", //Página inicial
    "homefunc" => "View/homeFUNC.php", //Página inicial
    "clientes" => "View/ClientesView/ClientesView.php",
    "financeiroempresa" => "View/FinanceiroEmpresaView.php",
    "financeiro" => "View/FinanceiroClientesView.php",
    "calendario" => "View/CalendarioView.php",
    "confirmarevento" => "View/ConfirmarEventoView.php",
    "dente" => "View/DentesView.php",
    "servico" => "View/ServicosView.php",
    "pesquisarcliente" => "View/PesquisarClienteView.php",
    "notas" => "View/NotasView.php",
    "catserfin" => "View/CategoriaSerFinView.php",
    "confirmacaopedido" =>"View/ConfirmacaoPedidoView.php",
    "status" => "View/AlterarStatusView.php",
    "pagamento" => "View/PagamentoView.php",
    "visualizar" => "View/ClientesView/VisualizarClientes.php", //Página inicial
    "teste" => "View/teste.php",
);

if ($pagina) {
    $encontrou = false;

    foreach ($arrayPaginas as $page => $key) {
        if ($pagina == $page) {
            $encontrou = true;
            require_once($key);
        }
    }

    if (!$encontrou) {
        require_once("View/home.php");
    }
} else {
    require_once("View/home.php");
}
?>