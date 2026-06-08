<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrada de Stock - Escuela Mario Salazar Mora</title>
    <link rel="stylesheet" href="css/css.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header>
    <h1>
        <i class="fa-solid fa-circle-plus" style="color:#28A745;"></i>
        Entrada de Stock
    </h1>
    <h2>Formulario de Inventariado</h2>
</header>

<main>
    <div class="encabezado-inventario">
        <a href="index.php" class="btn-regresar">
            <i class="fa-solid fa-arrow-left"></i>
            Volver al Panel
        </a>
    </div>

    <section class="form-container-box">
        <h3>Registrar Ingreso de Suministros</h3>

        <form class="form-operacion-bloque" action="php/guardar_producto.php" method="POST">

            <div class="grupo-campo">
                <label for="reg-codigo">Código Único del Producto:</label>
                <input type="text" id="reg-codigo" name="codigo" placeholder="Ej. HOJ-001, FOLD-10" required>
            </div>

            <div class="grupo-campo">
                <label for="reg-categoria">Categoría:</label>
                <select id="reg-categoria" name="categoria" required>
                    <option value="" disabled selected>-- Elija una opción --</option>
                    <option value="Hojas">Hojas</option>
                    <option value="Cuadernos">Cuadernos</option>
                    <option value="Cartulinas">Cartulinas</option>
                    <option value="Folders">Folders</option>
                    <option value="Tintas">Tintas</option>
                    <option value="Variado">Variado</option>
                    <option value="Electronica">Electrónica</option> 
                </select>
            </div>

            <div class="grupo-campo">
                <label for="reg-nombre">Descripción del Producto:</label>
                <input type="text" id="reg-nombre" name="descripcion" placeholder="Ej. Folder Manila Tamaño Carta" required>
            </div>

            <div class="grid-2-columnas">
                <div class="grupo-campo">
                    <label for="reg-marca">Marca:</label>
                    <input type="text" id="reg-marca" name="marca" placeholder="Ej. Facia, Scribe" required>
                </div>

                <div class="grupo-campo">
                    <label for="reg-color">Color / Detalle:</label>
                    <input type="text" id="reg-color" name="color" placeholder="Ej. Azul, Blanco" required>
                </div>
            </div>

            <div class="grid-2-columnas">
                <div class="grupo-campo">
                    <label for="reg-paquetes">Cantidad de Paquetes:</label>
                    <input type="number" id="reg-paquetes" name="paquetes" min="1" value="1" required>
                </div>

                <div class="grupo-campo">
                    <label for="reg-unidades">Unidades por Paquete:</label>
                    <input type="number" id="reg-unidades" name="unidades" min="1" placeholder="Ej. 100" required>
                </div>
            </div>

            <button type="submit" class="btn-operacion" style="width:100%; margin-top:1rem; background-color:#28A745; color:white;">
                <i class="fa-solid fa-square-plus"></i> Registrar e Ingresar Stock
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