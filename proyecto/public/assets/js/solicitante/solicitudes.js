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
            mensaje.colSpan = 13;
            mensaje.className = "text-center text-muted py-3";
            mensaje.textContent = "Aún no registraste solicitudes.";
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

const formularioAlta = document.getElementById("formularioSolicitud");

function obtenerDatosFormulario() {
    return {
        tipo: "SOLICITUD",
        turno: formularioAlta.elements["turno"].value,
        nombreDocente: formularioAlta.elements["nombreDocente"].value.trim(),
        grupo: formularioAlta.elements["grupo"].value.trim(),
        asignatura: formularioAlta.elements["asignatura"].value.trim(),
        idLaboratorio: formularioAlta.elements["idLaboratorio"].value,
        numeroDispositivo: formularioAlta.elements["numeroDispositivo"].value,
        descripcion: formularioAlta.elements["descripcion"].value.trim(),
        tipoServicio: formularioAlta.elements["tipoServicio"].value,
        fechaEsperada: formularioAlta.elements["fechaEsperada"].value
    };
}

async function guardarDatos(datos) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DATOS, "POST", datos);
}

async function gestionarAlta(eventoFormulario) {
    eventoFormulario.preventDefault();
    const datos = obtenerDatosFormulario();
    try {
        const guardado = await guardarDatos(datos);
        if (!guardado) return;
        formularioAlta.reset();
        document.getElementById("idLaboratorio").dispatchEvent(new Event("change"));
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

formularioAlta.addEventListener("submit", gestionarAlta);
