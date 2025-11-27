<?php
require '../app/pdo.php'; // con esto nos conectamos a la base de datos
require '../app/utils.php'; // Esto sirve para evitar XSS
session_start();

$error = '';

// Si envían el formulario (POST) Aqui comprobamos si se ha enviado con el metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Aqui buscamos el usuario en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :u");
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    // Verificamos la contraseña y la compara con el hash de la BD
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // Redirigir a la pagina principal
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="login-container">
        <h1>Entrar al Inventario</h1>
        
        <?php if($error): ?>
            <div class="error-msg"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div style="text-align: left; margin-bottom: 5px; color: #666;">Usuario</div>
            <input type="text" name="username" required>
            
            <div style="text-align: left; margin-bottom: 5px; margin-top: 10px; color: #666;">Clave</div>
            <input type="password" name="password" required>
            
            <br><br>
            <button type="submit">Entrar</button>
        </form>

        <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
            <span style="color: #666;">¿No tienes cuenta?</span><br>
            <a href="register.php" style="font-weight: bold;">
                Crea una aquí
            </a>
        </div>
    </div>

</body>
</html>