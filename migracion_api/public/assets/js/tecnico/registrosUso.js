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
    const campoSolicitante = document.createElement("td");
    campoSolicitante.textContent = dato.solicitante;
    fila.appendChild(campoSolicitante);

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
