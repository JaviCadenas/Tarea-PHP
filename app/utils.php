<?php
// app/utils.php

// Función para escapar todas las salidas de datos en las vistas para prevenir XSS.
function e($string) {
    // ?? '': Esto es un truquiño de PHP, una fusión de null. Significa: "Si la variable $string no existe o es nula, usa una cadena vacía '' para que no de error"
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Aqui, con esta funcion es con la que verificamos si se está logueado o no.
function require_login() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}