<?php
session_start();
session_destroy();
unset($_SESSION['face_access_token']);
header("Location: index.php?pagina=entrar&msg=2");
?>