const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../api/registrosUso.php";

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
    const campoLaboratorio = document.createElement("td");
    campoLaboratorio.textContent = dato.laboratorio;
    fila.appendChild(campoLaboratorio);

    const campoTurno = document.createElement("td");
    campoTurno.textContent = dato.turno;
    fila.appendChild(campoTurno);

    const campoFechaHora = document.createElement("td");
    campoFechaHora.textContent = dato.fechaHora;
    fila.appendChild(campoFechaHora);

    const campoNombreDocente = document.createElement("td");
    campoNombreDocente.textContent = dato.nombreDocente;
    fila.appendChild(campoNombreDocente);

    const campoGrupo = document.createElement("td");
    campoGrupo.textContent = dato.grupo;
    fila.appendChild(campoGrupo);

    const campoAsignatura = document.createElement("td");
    campoAsignatura.textContent = dato.asignatura;
    fila.appendChild(campoAsignatura);

    const campoUsoMaquinas = document.createElement("td");
    campoUsoMaquinas.textContent = dato.usoMaquinas == 1 ? "Sí" : "No";
    fila.appendChild(campoUsoMaquinas);

    const campoHuboIncidencias = document.createElement("td");
    campoHuboIncidencias.textContent = dato.huboIncidencias == 1 ? "Sí" : "No";
    fila.appendChild(campoHuboIncidencias);

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

const formularioAlta = document.getElementById("formularioRegistroUso");

function obtenerDatosFormulario() {
    return {
        idLaboratorio: formularioAlta.elements["idLaboratorio"].value,
        turno: formularioAlta.elements["turno"].value,
        fechaHora: formularioAlta.elements["fechaHora"].value,
        nombreDocente: formularioAlta.elements["nombreDocente"].value.trim(),
        grupo: formularioAlta.elements["grupo"].value.trim(),
        asignatura: formularioAlta.elements["asignatura"].value.trim(),
        usoMaquinas: formularioAlta.elements["usoMaquinas"].value,
        huboIncidencias: formularioAlta.elements["huboIncidencias"].value
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
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        alert("No se pudo comunicar con el servidor.");
    }
}

formularioAlta.addEventListener("submit", gestionarAlta);
