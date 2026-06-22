<?php
$codF = 0;
$nomeF = "";
$funcaoF = 0;
$cod_orgaoF = 0;
$fotoF = "";
$permissaoF = 0;
session_start();
if (!isset($_SESSION["permissaoF"])) {
    header("Location: index.php");
} else {
    $codF = (int) $_SESSION['codF'];
    $nomeF = $_SESSION['nomeF'];
    $permissaoF = (int) $_SESSION['permissaoF'];
}
date_default_timezone_set('America/Manaus');
?><!DOCTYPE html>

<html lang="pt"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="Interface/img/logo.png">
        <title>Relatórios</title>
        <script src="Interface/js/script.js" type="text/javascript"></script>
        <script src="Interface/js/jquery-3.1.1.min.js" type="text/javascript"></script>
        <script src="Interface/js/funcs.js" type="text/javascript"></script>
        <script src="Interface/js/funcscarrinhocompras.js" type="text/javascript"></script>
        <script src="Interface/js/funcshome.js" type="text/javascript"></script>
        <script src="Interface/js/funcsprodutos.js" type="text/javascript"></script>
        <link href="Interface/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="Interface/css/unsemantic-grid-responsive.css" rel="stylesheet" media="all" />
        <link href="Interface/css/style.css" rel="stylesheet" type="text/css"/>

    </head>

    <body class="bg-white" style="background-color: #fff;">

        <main role="main" class="container">
            <?php
            require_once("Util/RequestPageImp.php");
            ?>   
        </main>


    </body>

    <script type="text/javascript">
        $().ready(function () {
            // the body of this function is in assets/material-kit.js
            materialKit.initSliders();
            window_width = $(window).width();

            if (window_width >= 992) {
                big_image = $('.wrapper > .header');

                $(window).on('scroll', materialKitDemo.checkScrollForParallax);
            }
        });

    </script>
</body>

</html>
</html>