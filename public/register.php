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
    <title>Crear Cuenta</title>
</head>
<body>
    <h1>Crear Nueva Cuenta</h1>

    <?php if($error): ?>
        <p style="color: red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Elige un Usuario:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Elige una Contraseña:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">¡Registrarme!</button>
    </form>
    
    <p>
        <a href="login.php">Volver al Login</a>
    </p>
</body>
</html>