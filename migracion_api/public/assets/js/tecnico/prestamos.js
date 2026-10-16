const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../index.php?ruta=prestamos";
async function obtenerDatos() {
    return await obtenerDatosApi(API_DATOS);
}

function agregarFila(dato) {
    const fila = document.createElement("tr");
    const campoCedulaSolicitante = document.createElement("td");
    campoCedulaSolicitante.textContent = dato.cedulaSolicitante;
    fila.appendChild(campoCedulaSolicitante);

    const campoTurno = document.createElement("td");
    campoTurno.textContent = dato.turno;
    fila.appendChild(campoTurno);

    const campoNombreSolicitante = document.createElement("td");
    campoNombreSolicitante.textContent = dato.nombreSolicitante;
    fila.appendChild(campoNombreSolicitante);

    const campoNumeroLaptop = document.createElement("td");
    campoNumeroLaptop.textContent = dato.numeroLaptop;
    fila.appendChild(campoNumeroLaptop);

    const campoFechaRetiro = document.createElement("td");
    campoFechaRetiro.textContent = dato.fechaRetiro;
    fila.appendChild(campoFechaRetiro);

    const campoFechaDevolucion = document.createElement("td");
    campoFechaDevolucion.textContent = dato.fechaDevolucion;
    fila.appendChild(campoFechaDevolucion);

    const campoFechaDevolucionReal = document.createElement("td");
    campoFechaDevolucionReal.textContent = dato.fechaDevolucionReal ?? "Pendiente";
    fila.appendChild(campoFechaDevolucionReal);

    const campoEstado = document.createElement("td");
    campoEstado.textContent = dato.estado;
    fila.appendChild(campoEstado);

    const campoOperaciones = document.createElement("td");
    if (dato.estado === "ACTIVO") {
        const botonDevolucion = document.createElement("button");
        botonDevolucion.setAttribute("type", "button");
        botonDevolucion.setAttribute("class", "btnOperacion btnRegistrarDevolucion");
        botonDevolucion.setAttribute("data-id", (dato.idPrestamo));
        campoOperaciones.appendChild(botonDevolucion);
        botonDevolucion.textContent = "Registrar devolución";
    }
    fila.appendChild(campoOperaciones);

    cuerpoTabla.appendChild(fila);
}

async function actualizarTabla() {
    try {
        const datos = await obtenerDatos();
        cuerpoTabla.replaceChildren();
        for (const dato of datos) {
            agregarFila(dato);
        }
        if (typeof configurarOperaciones === "function") configurarOperaciones();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

actualizarTabla();

async function guardarPrestamo(prestamo) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DATOS, "POST", prestamo);
}

async function registrarDevolucion(devolucion) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DATOS, "PUT", devolucion);
}

function obtenerDatosFormularioPrestamo() {
    return {
        cedulaSolicitante: document.getElementById("cedulaSolicitante").value.trim(),
        turno: document.getElementById("turno").value.trim(),
        nombreSolicitante: document.getElementById("nombreSolicitante").value.trim(),
        numeroLaptop: document.getElementById("numeroLaptop").value.trim(),
        retiro: document.getElementById("retiro").value.trim(),
        devolucion: document.getElementById("devolucion").value.trim()
    };
}

async function gestionarPrestamo(eventoFormulario) {
    eventoFormulario.preventDefault();
    const prestamo = obtenerDatosFormularioPrestamo();
    try {
        const guardado = await guardarPrestamo(prestamo);
        if (!guardado) return;
        cerrarDialogoPrestamo();
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

async function gestionarDevolucion(eventoFormulario) {
    eventoFormulario.preventDefault();
    const devolucion = {
        idPrestamo: document.getElementById("idPrestamoDevolucion").value,
        fechaDevolucion: document.getElementById("fechaDevolucion").value
    };
    try {
        const registrada = await registrarDevolucion(devolucion);
        if (!registrada) return;
        cerrarDialogoDevolucion();
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

formularioPrestamos.addEventListener("submit", gestionarPrestamo);
formularioDevolucion.addEventListener("submit", gestionarDevolucion);
