<?php
session_start();
session_destroy();

// Eventueel doorsturen naar homepage of login-pagina
header('Location: index.php');
exit;
?>
