document.querySelectorAll('input[name="reportoAlumno"]').forEach((opcion) => {
    opcion.addEventListener('change', () => {
        const campo = document.getElementById('nombreAlumno');
        const reportoAlumno = opcion.value === 'SI';
        campo.disabled = !reportoAlumno;
        campo.required = reportoAlumno;
        if (!reportoAlumno) campo.value = '';
    });
});

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

    const campoFechaApertura = document.createElement("td");
    campoFechaApertura.textContent = dato.fechaApertura;
    fila.appendChild(campoFechaApertura);

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
            mensaje.textContent = "Aún no registraste incidencias.";
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

const formularioAlta = document.getElementById("formularioIncidencia");

function obtenerDatosFormulario() {
    return {
        tipo: "INCIDENCIA",
        turno: formularioAlta.elements["turno"].value,
        nombreDocente: formularioAlta.elements["nombreDocente"].value.trim(),
        grupo: formularioAlta.elements["grupo"].value.trim(),
        asignatura: formularioAlta.elements["asignatura"].value.trim(),
        idLaboratorio: formularioAlta.elements["idLaboratorio"].value,
        numeroDispositivo: formularioAlta.elements["numeroDispositivo"].value,
        descripcion: formularioAlta.elements["descripcion"].value.trim(),
        reportoAlumno: formularioAlta.elements["reportoAlumno"].value,
        nombreAlumno: formularioAlta.elements["nombreAlumno"].value.trim()
    };
}

async function guardarDatos(datos) {
    if (!await formularioListo) return false;
    const respuesta = await fetch(API_DATOS, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken
        },
        body: JSON.stringify(datos)
    });
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return false;
    }
    return true;
}

async function gestionarAlta(eventoFormulario) {
    eventoFormulario.preventDefault();
    const datos = obtenerDatosFormulario();
    try {
        const guardado = await guardarDatos(datos);
        if (!guardado) return;
        formularioAlta.reset();
        document.getElementById("idLaboratorio").dispatchEvent(new Event("change"));
        const campoAlumno = document.getElementById("nombreAlumno");
        campoAlumno.disabled = true;
        campoAlumno.required = false;
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        alert("No se pudo comunicar con el servidor.");
    }
}

formularioAlta.addEventListener("submit", gestionarAlta);
