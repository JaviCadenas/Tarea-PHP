<?php
// public/preferencias.php

if (isset($_GET['modo'])) {
    $modo = $_GET['modo'];
    // Guardamos la cookie por 30 días
    setcookie('tema', $modo, time() + (86400 * 30), "/");
    
    // Nos volvemos al índice inmediatamente
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Preferencias</title></head>
<body>
    <h1>Elige el aspecto</h1>
    
    <a href="?modo=claro" style="padding:10px; background:#eee; color:black; text-decoration:none;">
        Modo Claro ☀️
    </a>

    <a href="?modo=oscuro" style="padding:10px; background:#333; color:white; text-decoration:none;">
        Modo Oscuro 🌙
    </a>

    <br><br>
    <a href="index.php">Volver sin cambiar nada</a>
</body>
</html>