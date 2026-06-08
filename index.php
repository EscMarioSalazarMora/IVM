<?php
// 1. Incluimos la conexión a la base de datos IVM por si se realiza una búsqueda
include("php/conexion.php");

$productoEncontrado = null;
$busquedaRealizada = false;
$errorBusqueda = "";

// 2. Si el usuario envió un código en el buscador
if (isset($_GET['codigo_buscar'])) {
    $busquedaRealizada = true;
    $codigo = strtoupper(trim($_GET['codigo_buscar']));

    if (!empty($codigo)) {
        try {
            $sql = "SELECT * FROM productos WHERE codigo = :codigo LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':codigo' => $codigo]);
            $productoEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$productoEncontrado) {
                $errorBusqueda = "El código '$codigo' no está registrado en el IVM.";
            }
        } catch (PDOException $e) {
            $errorBusqueda = "Error en la búsqueda: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Escuela Mario Salazar Mora</title>
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header>
        <h1><i class="fa-solid fa-boxes-stacked"></i> Control de Inventario</h1>
        <h2>Escuela Mario Salazar Mora</h2>
    </header>

    <main>
        <section class="seccion-operaciones" style="margin-bottom: 2rem;">
            <h3><i class="fa-solid fa-magnifying-glass"></i> Consultar Producto por Código</h3>
            <form action="index.php" method="GET" style="display: flex; gap: 10px; margin-top: 1rem;">
                <input type="text" name="codigo_buscar" placeholder="Ej: HOJ-001, CUA-020..." 
                       value="<?php echo isset($_GET['codigo_buscar']) ? htmlspecialchars($_GET['codigo_buscar']) : ''; ?>" 
                       style="flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; text-transform: uppercase;" required>
                <button type="submit" class="btn-operacion azul" style="background-color: #0A2540; margin: 0; padding: 12px 25px;">
                    <i class="fa-solid fa-search"></i> Buscar
                </button>
                <?php if ($busquedaRealizada): ?>
                    <a href="index.php" class="btn-operacion rojo" style="background-color: #6c757d; margin: 0; padding: 12px 25px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">
                        Limpiar
                    </a>
                <?php endif; ?>
            </form>

            <?php if ($busquedaRealizada): ?>
                <div style="margin-top: 1.5rem; padding: 15px; border-radius: 8px; background-color: #f8f9fa; border-left: 5px solid <?php echo $productoEncontrado ? '#28A745' : '#DC3545'; ?>;">
                    <?php if ($productoEncontrado): ?>
                        <h4 style="margin-top: 0; color: #0A2540; font-size: 1.2rem;">
                            <i class="fa-solid fa-circle-check" style="color: #28A745;"></i> Producto Encontrado
                        </h4>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; background: white; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <thead>
                                <tr style="background-color: #0A2540; color: white; text-align: left;">
                                    <th style="padding: 10px;">Código</th>
                                    <th style="padding: 10px;">Categoría</th>
                                    <th style="padding: 10px;">Descripción</th>
                                    <th style="padding: 10px;">Marca</th>
                                    <th style="padding: 10px;">Color</th>
                                    <th style="padding: 10px;">Existencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 12px;"><span class="badge-codigo"><?php echo htmlspecialchars($productoEncontrado['codigo']); ?></span></td>
                                    <td style="padding: 12px; font-weight: bold; color: #555;"><?php echo htmlspecialchars($productoEncontrado['categoria']); ?></td>
                                    <td style="padding: 12px;"><strong><?php echo htmlspecialchars($productoEncontrado['nombre']); ?></strong></td>
                                    <td style="padding: 12px;"><?php echo htmlspecialchars($productoEncontrado['marca']); ?></td>
                                    <td style="padding: 12px;"><?php echo htmlspecialchars($productoEncontrado['color']); ?></td>
                                    <td style="padding: 12px;"><span style="font-size: 1.1rem; font-weight: bold; color: #0A2540;"><?php echo htmlspecialchars($productoEncontrado['cantidad']); ?></span> u.</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="margin: 0; color: #DC3545; font-weight: bold;">
                            <i class="fa-solid fa-circle-exclamation"></i> <?php echo $errorBusqueda; ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
        <section class="seccion-operaciones">
            <h3>Operaciones Rápidas</h3>
            <div class="grid-operaciones">
                <a href="entrada.php" class="btn-operacion azul" style="background-color: #28A745;">
                    <i class="fa-solid fa-circle-plus"></i> Entrada de Stock
                </a>
                <a href="salida.php" class="btn-operacion rojo" style="background-color: #DC3545;">
                    <i class="fa-solid fa-circle-minus"></i> Salida de Stock
                </a>
                <a href="historial.php" class="btn-operacion dorado-btn">
                    <i class="fa-solid fa-clock-rotate-left"></i> Ver Historial
                </a>
            </div>
        </section>

        <section class="categorias-container">
            <h3>Categorías de Inventario</h3>
            <div class="grid-categorias">
                <a href="hojas.php" class="btn-categoria"><i class="fa-solid fa-file-lines"></i> Hojas</a>
                <a href="cuadernos.php" class="btn-categoria"><i class="fa-solid fa-book"></i> Cuadernos</a>
                <a href="cartulinas.php" class="btn-categoria"><i class="fa-solid fa-map"></i> Cartulinas</a>
                <a href="folders.php" class="btn-categoria"><i class="fa-solid fa-folder-open"></i> Folders</a>
                <a href="tintas.php" class="btn-categoria"><i class="fa-solid fa-droplet"></i> Tintas</a>
                <a href="variado.php" class="btn-categoria"><i class="fa-solid fa-cubes"></i> Variado</a>
                <a href="electronica.php" class="btn-categoria"><i class="fa-solid fa-plug"></i> Electronica</a>
            </div>
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