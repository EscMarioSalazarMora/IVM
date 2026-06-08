<?php
include("php/conexion.php");

try {
    // Traer los logs ordenados por ID descendente (el más reciente arriba)
    $sql = "SELECT * FROM logs_ivm ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar el historial: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Stock</title>
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <h1><i class="fa-solid fa-clock-rotate-left"></i> Historial del IVM</h1>
    <h2>Bitácora de Movimientos de la Escuela</h2>
</header>

<main>
    <div class="encabezado-inventario">
        <a href="index.php" class="btn-regresar"><i class="fa-solid fa-arrow-left"></i> Volver al Panel</a>
    </div>

    <section class="tabla-responsiva">
        <h3>Registro de Entradas y Salidas</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Categoría</th>
                    <th>Producto</th>
                    <th>Operación</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (count($logs) > 0) {
                foreach ($logs as $log) {
                    // Si es entrada o registro inicial pintamos verde, si no, rojo
                    $esEntrada = (strpos($log['operacion'], 'Entrada') !== false || strpos($log['operacion'], 'Registro') !== false);
                    $colorAccion = $esEntrada ? "#28A745" : "#DC3545";
                    
                    echo "
                    <tr>
                        <td>" . htmlspecialchars($log['fecha']) . "</td>
                        <td style='text-transform: capitalize;'>" . htmlspecialchars($log['categoria']) . "</td>
                        <td><strong>" . htmlspecialchars($log['producto']) . "</strong></td>
                        <td><span style='color:$colorAccion; font-weight:bold;'><i class='fa-solid fa-circle-dot'></i> " . htmlspecialchars($log['operacion']) . "</span></td>
                        <td><strong>" . htmlspecialchars($log['cantidad']) . " u.</strong></td>
                    </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; color:gray; padding: 2rem;'>Historial vacío en el IVM.</td></tr>";
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