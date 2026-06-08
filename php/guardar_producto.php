<?php
// 1. Incluir la conexión (está en su misma carpeta, por eso solo se pone conexion.php)
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recibir y limpiar los datos del formulario HTML
    $codigo = strtoupper(trim($_POST['codigo']));
    $categoria = $_POST['categoria'];
    $nombre = trim($_POST['descripcion']); 
    $marca = trim($_POST['marca']);
    $color = trim($_POST['color']);
    $paquetes = intval($_POST['paquetes']);
    $unidadesPorPaquete = intval($_POST['unidades']);

    // 3. Calcular las unidades totales
    $unidadesTotalesNuevas = $paquetes * $unidadesPorPaquete;

    try {
        // 4. Verificar si el código de producto ya existe en la base de datos IVM
        $buscarSql = "SELECT * FROM productos WHERE codigo = :codigo LIMIT 1";
        $stmtBuscar = $pdo->prepare($buscarSql);
        $stmtBuscar->execute([':codigo' => $codigo]);
        $productoExistente = $stmtBuscar->fetch(PDO::FETCH_ASSOC);

        if ($productoExistente) {
            // SI YA EXISTE: Sumamos el nuevo stock al valor actual
            $nuevaCantidad = $productoExistente['cantidad'] + $unidadesTotalesNuevas;
            
            $updateSql = "UPDATE productos SET cantidad = :cantidad WHERE codigo = :codigo";
            $stmtUpdate = $pdo->prepare($updateSql);
            $stmtUpdate->execute([
                ':cantidad' => $nuevaCantidad,
                ':codigo' => $codigo
            ]);

            // Registrar en la bitácora de historial
            $logSql = "INSERT INTO logs_ivm (categoria, producto, operacion, cantidad) 
                       VALUES (:categoria, :producto, :operacion, :cantidad)";
            $stmtLog = $pdo->prepare($logSql);
            $stmtLog->execute([
                ':categoria' => $productoExistente['categoria'],
                ':producto' => $productoExistente['nombre'],
                ':operacion' => "Entrada Stock ($paquetes paq.)",
                ':cantidad' => $unidadesTotalesNuevas
            ]);

        } else {
            // SI ES NUEVO: Creamos el registro desde cero en la tabla productos
            $insertSql = "INSERT INTO productos (codigo, categoria, nombre, marca, color, cantidad) 
                          VALUES (:codigo, :categoria, :nombre, :marca, :color, :cantidad)";
            $stmtInsert = $pdo->prepare($insertSql);
            $stmtInsert->execute([
                ':codigo' => $codigo,
                ':categoria' => $categoria,
                ':nombre' => $nombre,
                ':marca' => $marca,
                ':color' => $color,
                ':cantidad' => $unidadesTotalesNuevas
            ]);

            // Registrar el movimiento inicial en el historial
            $logSql = "INSERT INTO logs_ivm (categoria, producto, operacion, cantidad) 
                       VALUES (:categoria, :producto, :operacion, :cantidad)";
            $stmtLog = $pdo->prepare($logSql);
            $stmtLog->execute([
                ':categoria' => $categoria,
                ':producto' => $nombre,
                ':operacion' => "Registro Inicial ($codigo)",
                ':cantidad' => $unidadesTotalesNuevas
            ]);
        }

        // 5. Alerta de éxito y regreso a entrada.php usando ../ para salir de la carpeta php
        echo "<script>
                alert('¡Éxito! Se han ingresado $unidadesTotalesNuevas unidades al código $codigo.');
                window.location.href = '../entrada.php';
              </script>";

    } catch (PDOException $e) {
        die("Error al guardar el producto en la base de datos: " . $e->getMessage());
    }
}
?>