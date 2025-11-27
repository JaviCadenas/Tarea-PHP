<?php
// Esto por si piden cambiar de tema, blanco o negro
if (isset($_GET['tema'])) {
    $tema = $_GET['tema'];
    // Aqui le indicamos cuando va a durar la opción elegida, que es 1 año
    setcookie('tema', $tema, time() + 31536000, '/');
    
    header("Location: index.php"); // Volver
    exit;
}
?>
<h1>Elige tu color de fondo</h1>
<a href="?tema=white">Modo Claro (Blanco)</a> | 
<a href="?tema=#333; color:white;">Modo Oscuro</a> | 
<a href="index.php">Volver</a>