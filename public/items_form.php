<?php
require '../app/pdo.php';
require '../app/utils.php';
require '../app/csrf.php';
require_login();

$id = $_GET['id'] ?? null;
$item = ['nombre' => '', 'stock' => 0, 'id' => '']; // Por defecto no van a haber datos

// Si vamos a añadir un item, comprobamos que exista en la base de datos
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $item = $stmt->fetch();
    if (!$item) die("Ítem no encontrado");
}

// Con esto guardamos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf(); // muy importante la seguridad
    
    $nombre = $_POST['nombre'];
    $stock  = $_POST['stock'];
    $idPost = $_POST['id']; // Como buena practica se nos ha enseñado, el id lo vamos a ocultar en un type hidden

    if (empty($nombre)) {
        $error = "El nombre es obligatorio";
    } else {
        if ($idPost) {
            // Aqui si el id es uno existente, lo modificamos con update
            $sql = "UPDATE items SET nombre = :n, stock = :s WHERE id = :id";
            $params = [':n' => $nombre, ':s' => $stock, ':id' => $idPost];
        } else {
            // Si no existe el id, lo creamos con insert
            $sql = "INSERT INTO items (nombre, stock) VALUES (:n, :s)";
            $params = [':n' => $nombre, ':s' => $stock];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        header("Location: index.php"); // Luego volvemos a la pagina principal
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Formulario Item</title></head>
<body>
    <h1><?= $id ? 'Editar' : 'Crear Nuevo' ?> Ítem</h1>
    
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="id" value="<?= e($item['id']) ?>">

        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= e($item['nombre']) ?>" required>
        <br><br>
        
        <label>Stock:</label><br>
        <input type="number" name="stock" value="<?= e($item['stock']) ?>">
        <br><br>

        <button type="submit">Guardar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>