let categoriaActiva = "";

function inicializarCategoria(categoria) {
    categoriaActiva = categoria;
    renderizarTablaProductos();
}

// Busca los datos en la memoria del navegador bajo el nombre IVM
function buscarDatos() {
    let bd = localStorage.getItem("IVM");
    return bd ? JSON.parse(bd) : {};
}

// Guarda los datos en la memoria del navegador bajo el nombre IVM
function guardarDatos(datos) {
    localStorage.setItem("IVM", JSON.stringify(datos));
}

// -------------------------------------------------------------------------
// PROCESAMIENTO: ENTRADA DE STOCK (PROPIEDADES NUEVAS)
// -------------------------------------------------------------------------
function procesarEntradaFlujo(event) {
    event.preventDefault();

    const codigo = document.getElementById("reg-codigo").value.trim().toUpperCase();
    const categoria = document.getElementById("reg-categoria").value;
    const nombre = document.getElementById("reg-nombre").value.trim();
    const marca = document.getElementById("reg-marca").value.trim();
    const color = document.getElementById("reg-color").value.trim();
    const paquetes = parseInt(document.getElementById("reg-paquetes").value);
    const unidadesPorPaquete = parseInt(document.getElementById("reg-unidades").value);

    // Calcular unidades totales
    const unidadesTotalesNuevas = paquetes * unidadesPorPaquete;

    let bd = buscarDatos();
    let productoExistente = null;
    let catEncontrada = categoria;

    // Buscar si el código ya existe en el IVM
    for (let c in bd) {
        let dejavu = bd[c].find(i => i.codigo === codigo);
        if (dejavu) {
            productoExistente = dejavu;
            catEncontrada = c;
            break;
        }
    }

    if (productoExistente) {
        // Si ya existe, se suma el stock
        productoExistente.cantidad += unidadesTotalesNuevas;
        registrarEnHistorial(catEncontrada, productoExistente.nombre, `Entrada Stock (${paquetes} paq.)`, unidadesTotalesNuevas);
    } else {
        // Si es nuevo, se crea en la categoría correspondiente
        if (!bd[categoria]) bd[categoria] = [];
        bd[categoria].push({
            codigo: codigo,
            nombre: nombre,
            marca: marca,
            color: color,
            cantidad: unidadesTotalesNuevas
        });
        registrarEnHistorial(categoria, nombre, `Registro Inicial (${codigo})`, unidadesTotalesNuevas);
    }

    guardarDatos(bd);
    alert(`¡Éxito! Se han ingresado ${unidadesTotalesNuevas} unidades al código ${codigo}.`);
    event.target.reset();
}

// -------------------------------------------------------------------------
// PROCESAMIENTO: SALIDA DE STOCK (RETIRO POR CÓDIGO)
// -------------------------------------------------------------------------
function procesarSalidaFlujo(event) {
    event.preventDefault();

    const codigoBuscar = document.getElementById("sal-codigo").value.trim().toUpperCase();
    const unidadesARetirar = parseInt(document.getElementById("sal-cantidad").value);

    let bd = buscarDatos();
    let productoEncontrado = null;
    let categoriaOrigen = "";

    // Buscar el artículo en el IVM
    for (let cat in bd) {
        let item = bd[cat].find(i => i.codigo === codigoBuscar);
        if (item) {
            productoEncontrado = item;
            categoriaOrigen = cat;
            break;
        }
    }

    // Validar si existe
    if (!productoEncontrado) {
        alert(`Error: El código "${codigoBuscar}" no corresponde a ningún artículo registrado en el IVM.`);
        return;
    }

    // Validar si hay suficiente stock
    if (productoEncontrado.cantidad < unidadesARetirar) {
        alert(`Operación Denegada: Stock insuficiente.\nEl artículo "${productoEncontrado.nombre}" solo tiene ${productoEncontrado.cantidad} unidades disponibles.`);
        return;
    }

    // Restar las unidades
    productoEncontrado.cantidad -= unidadesARetirar;
    guardarDatos(bd);

    // Guardar el movimiento en la bitácora LOGS_IVM
    registrarEnHistorial(categoriaOrigen, productoEncontrado.nombre, `Salida (Código: ${codigoBuscar})`, unidadesARetirar);
    
    alert(`Retiro Exitoso:\nSe descontaron ${unidadesARetirar} unidades de "${productoEncontrado.nombre}". Stock restante: ${productoEncontrado.cantidad}.`);
    event.target.reset();
}

// -------------------------------------------------------------------------
// RENDERIZADOR DE PÁGINAS DE CATEGORÍA
// -------------------------------------------------------------------------
function renderizarTablaProductos() {
    const tabla = document.getElementById("tabla-cuerpo-productos");
    if (!tabla) return;
    tabla.innerHTML = "";

    let bd = buscarDatos();
    let lista = bd[categoriaActiva] || [];

    if (lista.length === 0) {
        tabla.innerHTML = `<tr><td colspan="5" style="color: gray; text-align: center; padding: 2rem;">No hay artículos registrados en esta terminal.</td></tr>`;
        return;
    }

    lista.forEach(item => {
        let fila = document.createElement("tr");
        fila.innerHTML = `
            <td><span class="badge-codigo">${item.codigo}</span></td>
            <td><strong>${item.nombre}</strong></td>
            <td>${item.marca}</td>
            <td>${item.color}</td>
            <td><span style="font-size: 1.1rem; font-weight: bold; color: #0A2540;">${item.cantidad}</span> u.</td>
        `;
        tabla.appendChild(fila);
    });
}

// -------------------------------------------------------------------------
// COMPONENTE DE HISTORIAL / LOGS
// -------------------------------------------------------------------------
function registrarEnHistorial(categoria, producto, operacion, cantidad) {
    let logs = localStorage.getItem("LOGS_IVM");
    let listaLogs = logs ? JSON.parse(logs) : [];

    listaLogs.unshift({
        fecha: new Date().toLocaleString(),
        categoria: categoria,
        producto: producto,
        operacion: operacion,
        cantidad: Math.abs(cantidad)
    });
    localStorage.setItem("LOGS_IVM", JSON.stringify(listaLogs));
}

function cargarHistorialPantalla() {
    const tabla = document.getElementById("tabla-historial");
    if (!tabla) return; // Si no está en esta página, no hace nada
    tabla.innerHTML = "";

    let logs = localStorage.getItem("LOGS_IVM");
    let listaLogs = logs ? JSON.parse(logs) : [];

    if (listaLogs.length === 0) {
        tabla.innerHTML = `<tr><td colspan="5" style="text-align:center; color:gray; padding: 2rem;">Historial vacío en el IVM.</td></tr>`;
        return;
    }

    listaLogs.forEach(log => {
        let fila = document.createElement("tr");
        let colorAccion = log.operacion.includes("Entrada") || log.operacion.includes("Registro") ? "#28A745" : "#DC3545";
        
        fila.innerHTML = `
            <td>${log.fecha}</td>
            <td style="text-transform: capitalize;">${log.categoria}</td>
            <td><strong>${log.producto}</strong></td>
            <td><span style="color:${colorAccion}; font-weight:bold;"><i class="fa-solid fa-circle-dot"></i> ${log.operacion}</span></td>
            <td><strong>${log.cantidad} u.</strong></td>
        `;
        tabla.appendChild(fila);
    });
}

function borrarHistorial() {
    if (confirm("¿Deseas vaciar por completo el historial de movimientos de la escuela?")) {
        localStorage.removeItem("LOGS_IVM");
        cargarHistorialPantalla();
    }
}

// Ejecución automática al cargar la página actual
document.addEventListener("DOMContentLoaded", () => {
    // Si estamos en la página del historial, cargarlo automáticamente
    if (document.getElementById("tabla-historial")) {
        cargarHistorialPantalla();
    }
    // Si estamos en la página principal de categorías
    if (document.getElementById("tabla-cuerpo-productos")) {
        renderizarTablaProductos();
    }
});