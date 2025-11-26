<?php
// 1. Nos conectmos a la base de datos
require '../app/pdo.php';

// 2. Iniciamos la sesión en la base de datos
session_start();

$mensaje = ""; // Aquí guardaremos el error si falla el login

// 3. con esto sabemos si el formulario fue enviado con POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_form = $_POST['username']; // Aqui se guarda lo que escribio en el input
    $pass_form = $_POST['password']; // Aqui se guarda la contraseña que escribio

    // 4. Esta es la consulta para buscar al usuario en la base de datos
    // Usamos :u para evitar inyecciones SQL
    $sql = "SELECT * FROM usuarios WHERE username = :u";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u' => $user_form]);
    
    $usuario_bd = $stmt->fetch(); // la informacion encontrada en la consulta, lo guardamos

    // 5. Verificamos la contraseña
    // password_verify compara la constraseña escrita con el hash de la base de datos
    if ($usuario_bd && password_verify($pass_form, $usuario_bd['password'])) {
        
        // Aqui guardamos quien es en la sesión
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