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
<html>
<head><title>Login</title></head>
<body>
    <h1>Entrar al Inventario</h1>
    <?php if($error): ?><p style="color:red"><?= e($error) ?></p><?php endif; ?>
    
    <form method="POST">
        Usuario: <input type="text" name="username" required><br>
        Clave: <input type="password" name="password" required><br>
        <button type="submit">Entrar</button>
    </form>
    <div style="margin-top: 20px;">
        ¿No tienes cuenta? 
        <a href="register.php" style="color: dodgerblue; text-decoration: none; font-weight: bold;">
            Crea una aquí
        </a>
    </div>
</body>
</html>