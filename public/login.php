<?php
require '../app/pdo.php';
require '../app/utils.php'; // Para usar e()
session_start();

$error = '';

// Si envían el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar usuario en BD
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :u");
    $stmt->execute([':u' => $username]);
    $user = $stmt->fetch();

    // Verificar contraseña (password_verify compara con el hash)
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // Redirigir al panel
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
</body>
</html>