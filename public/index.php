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
    
    <style>
        body { font-family: sans-serif; padding: 20px; }
        
        /* Estilos cuando estamos en modo CLARO */
        body.tema-claro { background-color: white; color: black; }
        body.tema-claro a { color: blue; }

        /* Estilos cuando estamos en modo OSCURO */
        body.tema-oscuro { background-color: #222; color: #eee; }
        body.tema-oscuro a { color: #4da6ff; }
        body.tema-oscuro table { border-color: #555; }
    </style>
</head>

<body class="tema-<?= e($tema) ?>">

    <nav>
        Hola, <?= e($_SESSION['username']) ?> |
        <a href="logout.php">Salir</a> |
        <a href="preferencias.php">Cambiar Color</a>
    </nav>

    <h1>Listado de Items</h1>

    <form method="GET">
        <input type="text" name="q" value="<?= e($busqueda) ?>" placeholder="Buscar...">
        <button type="submit">Buscar</button>
    </form>
    <br>

    <a href="items_form.php">Crear Nuevo Item</a>
    <hr>

    <table border="1">
        <tr><th>Nombre</th><th>Stock</th><th>Acciones</th></tr>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e($item['nombre']) ?></td>
            <td><?= e($item['stock']) ?></td>
            <td>
                <a href="items_form.php?id=<?= $item['id'] ?>">Editar</a>

                <form action="items_delete.php" method="POST" style="display:inline">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= $token ?>">
                    <button type="submit" onclick="return confirm('¿Seguro?')">Borrar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div style="margin-top:10px;">
        <?php if($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&q=<?= e($busqueda) ?>">Anterior</a>
        <?php endif; ?>
        
        Página <?= $page ?>
        
        <a href="?page=<?= $page+1 ?>&q=<?= e($busqueda) ?>">Siguiente</a>
    </div>
</body>
</html>