<?php
require '../app/pdo.php';
require '../app/utils.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Primero vemos si los campos estan vacios o no, si lo estan mostramos un error
    if (empty($username) || empty($password)) {
        $error = "Por favor, rellena todos los campos.";
    } else {
        // Luego buscamos si el usuario ya existe, para evitar duplicados
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :u");
        $stmt->execute([':u' => $username]);
        
        if ($stmt->fetch()) {
            $error = "Ese nombre de usuario ya está cogido.";
        } else {
            // Luego encriptamos la contraseña con un hash para evitar hackeos
            $passHash = password_hash($password, PASSWORD_DEFAULT);

            // Luego insertamos el nuevo usuario en la base de datos
            $stmt = $pdo->prepare("INSERT INTO usuarios (username, password) VALUES (:u, :p)");
            
            if ($stmt->execute([':u' => $username, ':p' => $passHash])) {
                // Y por ultimo lo mandamos a la pagina de login para que inicie sesion
                header("Location: login.php");
                exit;
            } else {
                $error = "Hubo un error al crear la cuenta.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <h1>Crear Nueva Cuenta</h1>

        <?php if($error): ?>
            <div class="error-msg"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div style="text-align: left; color: #666; font-size: 0.9em;">Elige un Usuario:</div>
            <input type="text" name="username" required>

            <div style="text-align: left; color: #666; font-size: 0.9em; margin-top: 10px;">Elige una Contraseña:</div>
            <input type="password" name="password" required>

            <br><br>
            <button type="submit" style="background-color: #3498db;">Registrarse</button>
        </form>
        
        <div style="margin-top: 20px;">
            <a href="login.php">← Volver al Login</a>
        </div>
    </div>

</body>
</html>