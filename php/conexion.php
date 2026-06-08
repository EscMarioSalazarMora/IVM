<?php
$host = "localhost";
$user = "root";       // Usuario por defecto de XAMPP
$password = "";       // Contraseña por defecto de XAMPP (vacía)
$database = "IVM";    // Nombre exacto de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error crítico de conexión: " . $e->getMessage());
}
?>