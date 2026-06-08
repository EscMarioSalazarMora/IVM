<?php
// Incluir la conexión PDO que apunta a la base de datos IVM
include("php/conexion.php");

try {
    // Usamos la sintaxis correcta de PDO para consultar la categoría exacta
    $sql = "SELECT * FROM productos WHERE categoria = 'utiles'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Recuperamos todos los registros encontrados
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al consultar los productos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario de Utiles</title>
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <h1>
        <i class="fa-solid fa-folder"></i>
        Terminal de Gestión
    </h1>
    <h2>Categoría: Utiles</h2>
</header>

<main>
    <div class="encabezado-inventario">
        <a href="index.php" class="btn-regresar">
            <i class="fa-solid fa-arrow-left"></i>
            Volver al Panel Principal
        </a>
    </div>

    <section class="tabla-responsiva">
        <h3>Existencias en Bodega</h3>

        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción / Producto</th>
                    <th>Marca</th>
                    <th>Color</th>
                    <th>Existencia Total</th>
                </tr>
            </thead>
            <tbody>

            <?php
            // Validamos si la consulta trajo algún producto
            if (count($productos) > 0) {
                foreach ($productos as $fila) {
                    // Usamos directamente la columna 'cantidad' y 'nombre' de tu tabla de MySQL
                    echo "
                    <tr>
                        <td>
                            <span class='badge-codigo'>
                                " . htmlspecialchars($fila['codigo']) . "
                            </span>
                        </td>
                        <td><strong>" . htmlspecialchars($fila['nombre']) . "</strong></td>
                        <td>" . htmlspecialchars($fila['marca']) . "</td>
                        <td>" . htmlspecialchars($fila['color']) . "</td>
                        <td><span style='font-size: 1.1rem; font-weight: bold; color: #0A2540;'>" . htmlspecialchars($fila['cantidad']) . "</span> u.</td>
                    </tr>
                    ";
                }
            } else {
                echo "
                <tr>
                    <td colspan='5' style='text-align:center; padding:40px; color:gray;'>
                        No hay artículos registrados en esta terminal.
                    </td>
                </tr>
                ";
            }
            ?>

            </tbody>
        </table>
    </section>
</main>

<footer>
    <h3>Sistema de Inventariado</h3>
    <p>Escuela Mario Salazar Mora</p>
    <div class="linea"></div>
    <p>© 2026 Todos los Derechos Reservados</p>
    <p>Desarrollado por <strong>Leonardo García Segura</strong></p>
    <p><i class="fa-solid fa-phone"></i> +506 8499 9099</p>
</footer>

</body>
</html>