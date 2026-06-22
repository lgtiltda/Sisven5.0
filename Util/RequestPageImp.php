 <?php
$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_STRING);

$arrayPaginas = array(
    "1" => "Impressoes/View/DadosCadastrais.php", //Página inicial
    "2" => "Impressoes/View/OrdemServico.php",
    "3" => "Impressoes/View/FaturamentoDiario.php",
    "4" => "Impressoes/View/FaturamentoMensal.php",
    "5" => "Impressoes/View/FaturamentoAnual.php",
    "6" => "Impressoes/View/FaturamentoDiarioDesp.php",
    "7" => "Impressoes/View/FaturamentoMensalDesp.php",
    "8" => "Impressoes/View/FaturamentoAnualDesp.php",
    "9" => "Impressoes/View/NotaFiscal.php",
    "10" => "Impressoes/View/ProdutosMaisVendidos.php",
    "11" => "Impressoes/View/EstoqueCritico.php",
    "12" => "Impressoes/View/SaldoDeProdutosPorCat.php",
    "13" => "Impressoes/View/SimuladorDeDemanda.php",
    "14" => "Impressoes/View/RGA.php",
    "15" => "Impressoes/View/OrdemServico2.php",
    "16" => "Impressoes/View/PedidoAuto.php",
    "17" => "Impressoes/View/OrdemServico3.php",
    "18" => "Impressoes/View/OrdemServico4.php",
    "19" => "Impressoes/View/RelatorioEntradasAnual.php",
    "20" => "Impressoes/View/RelatorioEntradasMensal.php",
    "21" => "Impressoes/View/RelatorioEntradasDiario.php",
    "22" => "Impressoes/View/FecharCaixa.php",
    "23" => "Impressoes/View/ReciboParcela.php",
    "24" => "Impressoes/View/ImprimirEtiqueta.php",
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
        require_once("View/Entradas.php");
    }
} else {
    require_once("View/Entradas.php");
}
?>