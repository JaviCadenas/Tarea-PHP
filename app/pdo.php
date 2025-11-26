<?php
// app/pdo.php
$host = '127.0.0.1';
$db   = 'inventario_db';
$user = 'root'; // CAMBIA ESTO <--------------
$pass = 'Bohio20242025'; // CAMBIA ESTO <--------------
$charset = 'utf8mb4'; // Esto es para poder añadir ñ y las tildes


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";


$options = [
   PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // En caso de fallo nos lanza error.
   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Devuelve arrays asociativos cuando hacemos consultas SELECt
];
// Este try/catch nos va a servir para que en caso de que fallara la conexión con PDO a la base de datos la página nos muestre un error.
try {
   $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
   die("Error de conexión: " . $e->getMessage());
}