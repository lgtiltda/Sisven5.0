<?php
$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_STRING);

$arrayPaginas = array(
    "home" => "View/home.php", //Página inicial
    "carinhocompras" => "View/CarinhoDeComprasView.php", //Página De Montar Pedido 
    "produtos" => "View/ProdutosView.php", //Página De Montar Produtos 
    "usuarios" => "View/UsuariosView.php", //Página De Montar Produtos 
    "entradas" => "View/EntradasView.php", //Página De Montar Produtos 
    "relatorios" => "View/RelatoriosView.php", //Página De Montar Produtos 
    "fornecedores" => "View/FornecedoresView.php", //Página De Montar Produtos 
     "hometelao" => "View/homeTELAO.php", //Página inicial
    "homefunc" => "View/homeFUNC.php", //Página inicial
    
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