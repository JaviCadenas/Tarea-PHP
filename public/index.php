<?php
require '../app/pdo.php';
require '../app/utils.php';
require '../app/csrf.php';
require_login(); // Protegemos la página, con esto nos aseguramos que solo usuarios logueados accedan

// Aquí dependiendo de la preferencia de la cookie dejamos el fondo blanco o en negro
$tema = $_COOKIE['tema'] ?? 'claro'; // Si no hay cookies, se quedan el blanco por defecto

// Aqui esta la barra de busqueda y paginacion
$busqueda = $_GET['q'] ?? '';
$page     = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$limit    = 5; // Este es el numero de elementos que pueden aparecer por pagina
$offset   = ($page - 1) * $limit;

// Busca en la base de datos los items que coincidan con la busqueda
$sql = "SELECT * FROM items WHERE nombre LIKE :q LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':q', "%$busqueda%"); // El % es comodín en SQL
$stmt->execute();
$items = $stmt->fetchAll();

// Aqui recibe el token de la clase csrf para el boton borrar
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="tema-<?= e($tema) ?>">

    <div class="dashboard-container">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <div>
                Hola, <b><?= e($_SESSION['username']) ?></b>
            </div>
            <div>
                <a href="preferencias.php" style="margin-right: 10px;">Cambiar Color</a> |
                <a href="logout.php" style="color: #e74c3c;">Salir</a>
            </div>
        </div>

        <h1>Listado de Items</h1>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <form method="GET" style="display: flex; gap: 5px; margin: 0;">
                <input type="text" name="q" value="<?= e($busqueda) ?>" placeholder="Buscar..." style="margin: 0;">
                <button type="submit">Buscar</button>
            </form>

            <a href="items_form.php" class="btn">Crear Nuevo Item</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th style="width: 180px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= e($item['nombre']) ?></td>
                    <td><?= e($item['stock']) ?></td>
                    <td>
                        <a href="items_form.php?id=<?= $item['id'] ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.9em;">Editar</a>

                        <form action="items_delete.php" method="POST" style="display:inline">
                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $token ?>">
                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.9em;" onclick="return confirm('¿Seguro?')">Borrar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top:20px; text-align: center;">
            <?php if($page > 1): ?>
                <a href="?page=<?= $page-1 ?>&q=<?= e($busqueda) ?>">Anterior</a>
            <?php endif; ?>
            
            <span style="margin: 0 10px;">Página <?= $page ?></span>
            
            <a href="?page=<?= $page+1 ?>&q=<?= e($busqueda) ?>">Siguiente</a>
        </div>
    </div>
</body>
</html>