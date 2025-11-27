<?php
require '../app/pdo.php';
require '../app/utils.php';
require '../app/csrf.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf(); // con esto verificamos la seguridad con csrf

    $id = $_POST['id'];
    $usuario = $_SESSION['user_id'];

    try {
        // Aqui iniciamos la transaccion
        $pdo->beginTransaction();

        // Primero borramos el item
        $stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // y luego guardamos quien lo borro en la tabla de auditoria
        $stmt2 = $pdo->prepare("INSERT INTO auditoria (accion, fecha) VALUES (:acc, NOW())");
        $stmt2->execute([':acc' => "Usuario $usuario borró item ID $id"]);

        // con esto confirmamos los cambios
        $pdo->commit();

    } catch (Exception $e) {
        // si algo falla deshacemos todos los cambios
        $pdo->rollBack();
        die("Error al borrar: " . $e->getMessage());
    }

    // y esto nos devuelve a la pagina principal
    header("Location: index.php");
    exit;
}