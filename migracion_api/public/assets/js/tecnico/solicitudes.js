const dialogGestionSolicitud = document.getElementById("dialogGestionSolicitud");
const formularioGestionSolicitud = document.getElementById("formularioGestionSolicitud");
const idSolicitudGestion = document.getElementById("idSolicitudGestion");
const estadoSolicitud = document.getElementById("estadoSolicitud");



document.getElementById("btnCerrarGestionSolicitud").addEventListener("click", () => {
    dialogGestionSolicitud.close();
});
function configurarOperaciones() {
    document.querySelectorAll(".btnGestionarSolicitud").forEach((boton) => {
        boton.addEventListener("click", async () => {
            if (!await formularioListo) return;
            formularioGestionSolicitud.reset();
            idSolicitudGestion.value = boton.dataset.id;
            estadoSolicitud.value = boton.dataset.estado;
            dialogGestionSolicitud.showModal();
        });
    });
}

const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../index.php?ruta=tickets&tipo=SOLICITUD";

async function obtenerDatos() {
    return await obtenerDatosApi(API_DATOS);
}

function agregarFila(dato) {
    const fila = document.createElement("tr");
    const campoId = document.createElement("td");
    campoId.textContent = dato.id;
    fila.appendChild(campoId);

    const campoCedulaSolicitante = document.createElement("td");
    campoCedulaSolicitante.textContent = dato.cedulaSolicitante;
    fila.appendChild(campoCedulaSolicitante);

    const campoFechaApertura = document.createElement("td");
    campoFechaApertura.textContent = dato.fechaApertura;
    fila.appendChild(campoFechaApertura);

    const campoFechaEsperada = document.createElement("td");
    campoFechaEsperada.textContent = dato.fechaEsperada ?? "Sin fecha";
    fila.appendChild(campoFechaEsperada);

    const campoFechaCierre = document.createElement("td");
    campoFechaCierre.textContent = dato.fechaCierre ?? "Pendiente";
    fila.appendChild(campoFechaCierre);

    const campoTurno = document.createElement("td");
    campoTurno.textContent = dato.turno;
    fila.appendChild(campoTurno);

    const campoNombreDocente = document.createElement("td");
    campoNombreDocente.textContent = dato.nombreDocente;
    fila.appendChild(campoNombreDocente);

    const campoGrupo = document.createElement("td");
    campoGrupo.textContent = dato.grupo;
    fila.appendChild(campoGrupo);

    const campoAsignatura = document.createElement("td");
    campoAsignatura.textContent = dato.asignatura;
    fila.appendChild(campoAsignatura);

    const campoIdLaboratorio = document.createElement("td");
    campoIdLaboratorio.textContent = dato.idLaboratorio ?? "No especificado";
    fila.appendChild(campoIdLaboratorio);

    const campoNumeroDispositivo = document.createElement("td");
    campoNumeroDispositivo.textContent = dato.numeroDispositivo ?? "No especificado";
    fila.appendChild(campoNumeroDispositivo);

    const campoTipoServicio = document.createElement("td");
    campoTipoServicio.textContent = dato.tipoServicio;
    fila.appendChild(campoTipoServicio);

    const campoDescripcion = document.createElement("td");
    campoDescripcion.textContent = dato.descripcion;
    fila.appendChild(campoDescripcion);

    const campoEstado = document.createElement("td");
    campoEstado.textContent = dato.estado;
    fila.appendChild(campoEstado);

    const campoOperaciones = document.createElement("td");
    const botonGestionarSolicitud = document.createElement("button");
    botonGestionarSolicitud.setAttribute("type", "button");
    botonGestionarSolicitud.setAttribute("class", "btn btn-sm btn-primary btnGestionarSolicitud");
    botonGestionarSolicitud.setAttribute("data-id", (dato.id));
    botonGestionarSolicitud.setAttribute("data-estado", (dato.estado));
    campoOperaciones.appendChild(botonGestionarSolicitud);
    botonGestionarSolicitud.textContent = "Gestionar";
    botonGestionarSolicitud.disabled = dato.estado === 'RESUELTO';
    if (botonGestionarSolicitud.disabled) botonGestionarSolicitud.title = 'El ticket resuelto conserva su historial';
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
        if (datos.length === 0) {
            const fila = document.createElement("tr");
            const mensaje = document.createElement("td");
            mensaje.colSpan = 15;
            mensaje.className = "text-center text-muted py-3";
            mensaje.textContent = "No hay solicitudes registradas.";
            fila.appendChild(mensaje);
            cuerpoTabla.appendChild(fila);
        }
        if (typeof configurarOperaciones === "function") configurarOperaciones();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

actualizarTabla();

async function modificarTicket(ticket) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DATOS, "PUT", ticket);
}

async function gestionarTicket(eventoFormulario) {
    eventoFormulario.preventDefault();
    const ticket = {
        tipo: "SOLICITUD",
        id: document.getElementById("idSolicitudGestion").value.trim(),
        estado: document.getElementById("estadoSolicitud").value.trim()
    };
    try {
        const modificado = await modificarTicket(ticket);
        if (!modificado) return;
        document.getElementById("dialogGestionSolicitud").close();
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

document.getElementById("formularioGestionSolicitud").addEventListener("submit", gestionarTicket);
