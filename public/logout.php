<?php
session_start();
session_destroy(); // Esto como su nombre indica, destruye la sesión
setcookie(session_name(), '', time() - 3600); // Y esto borra las cookies del navegador
header("Location: login.php");
exit;