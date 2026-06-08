<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoBuscar = strtoupper(trim($_POST['codigo']));
    $unidadesARetirar = intval($_POST['cantidad']);

    try {
        // 1. Buscar si el artículo existe en la base de datos
        $sql = "SELECT * FROM productos WHERE codigo = :codigo LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':codigo' => $codigoBuscar]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            echo "<script>
                    alert('Error: El código \"$codigoBuscar\" no corresponde a ningún artículo registrado en el IVM.');
                    window.location.href = '../salida.php';
                  </script>";
            exit;
        }

        // 2. Validar si hay suficiente stock disponible
        if ($producto['cantidad'] < $unidadesARetirar) {
            $disponible = $producto['cantidad'];
            $nombreItem = $producto['nombre'];
            echo "<script>
                    alert('Operación Denegada: Stock insuficiente.\\nEl artículo \"$nombreItem\" solo tiene $disponible unidades disponibles.');
                    window.location.href = '../salida.php';
                  </script>";
            exit;
        }

        // 3. Restar las unidades en la base de datos
        $nuevaCantidad = $producto['cantidad'] - $unidadesARetirar;
        $updateSql = "UPDATE productos SET cantidad = :cantidad WHERE codigo = :codigo";
        $stmtUpdate = $pdo->prepare($updateSql);
        $stmtUpdate->execute([
            ':cantidad' => $nuevaCantidad,
            ':codigo' => $codigoBuscar
        ]);

        // 4. Guardar el movimiento en la bitácora logs_ivm
        $logSql = "INSERT INTO logs_ivm (categoria, producto, operacion, cantidad) 
                   VALUES (:categoria, :producto, :operacion, :cantidad)";
        $stmtLog = $pdo->prepare($logSql);
        $stmtLog->execute([
            ':categoria' => $producto['categoria'],
            ':producto' => $producto['nombre'],
            ':operacion' => "Salida (Código: $codigoBuscar)",
            ':cantidad' => $unidadesARetirar
        ]);

        echo "<script>
                alert('Retiro Exitoso:\\nSe descontaron $unidadesARetirar unidades de \"{$producto['nombre']}\". Stock restante: $nuevaCantidad.');
                window.location.href = '../salida.php';
              </script>";

    } catch (PDOException $e) {
        die("Error al procesar la salida: " . $e->getMessage());
    }
}
?>