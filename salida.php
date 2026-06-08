<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salida de Stock - Escuela Mario Salazar Mora</title>
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <h1><i class="fa-solid fa-circle-minus" style="color: #DC3545;"></i> Salida de Stock</h1>
    <h2>Retiro Rápido por Código</h2>
</header>

<main>
    <div class="encabezado-inventario">
        <a href="index.php" class="btn-regresar"><i class="fa-solid fa-arrow-left"></i> Volver al Panel</a>
    </div>

    <section class="form-container-box">
        <h3>Registrar Retiro de Suministros</h3>
        
        <form class="form-operacion-bloque" action="php/procesar_salida.php" method="POST">
            
            <div class="grupo-campo">
                <label for="sal-codigo">Código del Producto:</label>
                <input type="text" id="sal-codigo" name="codigo" placeholder="Escriba el código exacto (Ej. HOJ-001)" required>
            </div>

            <div class="grupo-campo">
                <label for="sal-cantidad">Unidades a Retirar:</label>
                <input type="number" id="sal-cantidad" name="cantidad" min="1" placeholder="Ej. 5" required>
            </div>

            <button type="submit" class="btn-operacion rojo" style="width: 100%; margin-top: 1rem;">
                <i class="fa-solid fa-square-minus"></i> Confirmar Retiro de Unidades
            </button>
        </form>
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