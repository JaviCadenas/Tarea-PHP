<?php
// 1. Traemos la conexión a la Base de Datos
require '../app/pdo.php';

// 2. Iniciamos la sesión (la memoria del navegador)
session_start();

$mensaje = ""; // Aquí guardaremos el error si falla el login

// 3. Detectamos si el usuario pulsó el botón "Entrar" (Método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_form = $_POST['username']; // Lo que escribió en el input
    $pass_form = $_POST['password']; // La contraseña que escribió

    // 4. Preparamos la consulta para buscar ese usuario
    // Usamos :u para evitar hackeos (SQL Injection)
    $sql = "SELECT * FROM usuarios WHERE username = :u";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u' => $user_form]);
    
    $usuario_bd = $stmt->fetch(); // Trae los datos del usuario (o false si no existe)

    // 5. Verificamos la contraseña
    // password_verify compara la clave escrita 'hola123' con el hash '$2y$10...' de la BD
    if ($usuario_bd && password_verify($pass_form, $usuario_bd['password'])) {
        
        // ¡Login Éxitoso! Guardamos quién es en la sesión
        $_SESSION['user_id']  = $usuario_bd['id'];
        $_SESSION['username'] = $usuario_bd['username'];
        
        // Lo mandamos a la página principal
        header('Location: index.php');
        exit;
    } else {
        // Fallo
        $mensaje = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Acceso</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>