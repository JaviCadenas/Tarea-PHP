<?php
// public/preferencias.php
require '../app/utils.php'; // Necesario para la funcion e()
require_login(); // Protegemos

// Leemos el tema actual para pintarlo
$tema = $_COOKIE['tema'] ?? 'claro';

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
<head>
    <title>Preferencias</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="tema-<?= e($tema) ?>">

    <div class="login-container">
        <h1>Elige el aspecto</h1>
        <p>Personaliza tu experiencia:</p>
        
        <br>
        
        <a href="?modo=claro" class="btn" style="background-color: #eee; color: black; border: 1px solid #ccc; width: 100%; box-sizing: border-box;">
            Modo Claro ☀️
        </a>

        <a href="?modo=oscuro" class="btn" style="background-color: #333; color: white; width: 100%; box-sizing: border-box; margin-top: 10px;">
            Modo Oscuro 🌙
        </a>

        <br><br><br>
        <a href="index.php">Volver sin cambiar nada</a>
    </div>
</body>
</html>