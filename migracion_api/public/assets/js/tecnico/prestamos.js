const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../api/prestamos.php";

async function obtenerDatos() {
    const respuesta = await fetch(API_DATOS);
    if (!respuesta.ok) {
        console.error("No se pudieron cargar los datos");
        return [];
    }
    const contenido = await respuesta.json();
    return contenido.datos;
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
        alert("No se pudieron cargar los datos. Compruebe la conexión con el servidor.");
    }
}

actualizarTabla();

async function guardarPrestamo(prestamo) {
    if (!await formularioListo) return false;
    const respuesta = await fetch(API_DATOS, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken
        },
        body: JSON.stringify(prestamo)
    });
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return false;
    }
    return true;
}

async function registrarDevolucion(devolucion) {
    if (!await formularioListo) return false;
    const respuesta = await fetch(API_DATOS, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken
        },
        body: JSON.stringify(devolucion)
    });
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return false;
    }
    return true;
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
        alert("No se pudo comunicar con el servidor.");
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
        alert("No se pudo comunicar con el servidor.");
    }
}

formularioPrestamos.addEventListener("submit", gestionarPrestamo);
formularioDevolucion.addEventListener("submit", gestionarDevolucion);
