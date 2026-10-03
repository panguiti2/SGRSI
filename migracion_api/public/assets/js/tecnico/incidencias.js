const dialogGestionarIncidencia = document.getElementById("dialogGestionarIncidencia");
const idIncidenciaGestionar = document.getElementById("idIncidenciaGestionar");
const diagnosticoIncidencia = document.getElementById("diagnosticoIncidencia");
const solucionIncidencia = document.getElementById("solucionIncidencia");
const estadoIncidencia = document.getElementById("estadoIncidencia");

const actualizarCamposCierre = () => {
    const esResuelto = estadoIncidencia.value === "RESUELTO";
    diagnosticoIncidencia.required = esResuelto;
    solucionIncidencia.required = esResuelto;
};



estadoIncidencia.addEventListener("change", actualizarCamposCierre);
document.getElementById("btnCerrarGestionIncidencia").addEventListener("click", () => dialogGestionarIncidencia.close());
function configurarOperaciones() {
    document.querySelectorAll(".btnGestionarIncidencia").forEach((boton) => {
        boton.addEventListener("click", async () => {
            if (!await formularioListo) return;
            idIncidenciaGestionar.value = boton.dataset.id;
            estadoIncidencia.value = boton.dataset.estado;
            diagnosticoIncidencia.value = boton.dataset.diagnostico;
            solucionIncidencia.value = boton.dataset.solucion;
            actualizarCamposCierre();
            dialogGestionarIncidencia.showModal();
        });
    });
}

const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../api/tickets.php?tipo=INCIDENCIA";

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
    const campoId = document.createElement("td");
    campoId.textContent = dato.id;
    fila.appendChild(campoId);

    const campoCedulaSolicitante = document.createElement("td");
    campoCedulaSolicitante.textContent = dato.cedulaSolicitante;
    fila.appendChild(campoCedulaSolicitante);

    const campoFechaApertura = document.createElement("td");
    campoFechaApertura.textContent = dato.fechaApertura;
    fila.appendChild(campoFechaApertura);

    const campoFechaGestion = document.createElement("td");
    campoFechaGestion.textContent = dato.fechaGestion ?? "Pendiente";
    fila.appendChild(campoFechaGestion);

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

    const campoReportoAlumno = document.createElement("td");
    campoReportoAlumno.textContent = dato.reportoAlumno == 1 ? "Sí" : "No";
    fila.appendChild(campoReportoAlumno);

    const campoNombreAlumno = document.createElement("td");
    campoNombreAlumno.textContent = dato.nombreAlumno ?? "No corresponde";
    fila.appendChild(campoNombreAlumno);

    const campoDescripcion = document.createElement("td");
    campoDescripcion.textContent = dato.descripcion;
    fila.appendChild(campoDescripcion);

    const campoDiagnostico = document.createElement("td");
    campoDiagnostico.textContent = dato.diagnostico ?? "Pendiente";
    fila.appendChild(campoDiagnostico);

    const campoSolucion = document.createElement("td");
    campoSolucion.textContent = dato.solucion ?? "Pendiente";
    fila.appendChild(campoSolucion);

    const campoEstado = document.createElement("td");
    campoEstado.textContent = dato.estado;
    fila.appendChild(campoEstado);

    const campoOperaciones = document.createElement("td");
    const botonGestionarIncidencia = document.createElement("button");
    botonGestionarIncidencia.setAttribute("type", "button");
    botonGestionarIncidencia.setAttribute("class", "btn btn-sm btn-primary btnGestionarIncidencia");
    botonGestionarIncidencia.setAttribute("data-id", (dato.id));
    botonGestionarIncidencia.setAttribute("data-estado", (dato.estado));
    botonGestionarIncidencia.setAttribute("data-diagnostico", (dato.diagnostico ?? ""));
    botonGestionarIncidencia.setAttribute("data-solucion", (dato.solucion ?? ""));
    campoOperaciones.appendChild(botonGestionarIncidencia);
    botonGestionarIncidencia.textContent = "Gestionar";
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
            mensaje.colSpan = 18;
            mensaje.className = "text-center text-muted py-3";
            mensaje.textContent = "No hay incidencias registradas.";
            fila.appendChild(mensaje);
            cuerpoTabla.appendChild(fila);
        }
        if (typeof configurarOperaciones === "function") configurarOperaciones();
    } catch (error) {
        console.error(error);
        alert("No se pudieron cargar los datos. Compruebe la conexión con el servidor.");
    }
}

actualizarTabla();

async function modificarTicket(ticket) {
    if (!await formularioListo) return false;
    const respuesta = await fetch(API_DATOS, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken
        },
        body: JSON.stringify(ticket)
    });
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return false;
    }
    return true;
}

async function gestionarTicket(eventoFormulario) {
    eventoFormulario.preventDefault();
    const ticket = {
        tipo: "INCIDENCIA",
        id: document.getElementById("idIncidenciaGestionar").value.trim(),
        estado: document.getElementById("estadoIncidencia").value.trim(),
        diagnostico: document.getElementById("diagnosticoIncidencia").value.trim(),
        solucion: document.getElementById("solucionIncidencia").value.trim()
    };
    try {
        const modificado = await modificarTicket(ticket);
        if (!modificado) return;
        document.getElementById("dialogGestionarIncidencia").close();
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        alert("No se pudo comunicar con el servidor.");
    }
}

document.getElementById("formularioGestionarIncidencia").addEventListener("submit", gestionarTicket);
