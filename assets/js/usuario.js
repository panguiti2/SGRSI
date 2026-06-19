const formularioIncidencia = document.getElementById("formularioIncidencia");

const campoLaboratorioIncidencia = document.getElementById("laboratorio");
const campoTallerIncidencia = document.getElementById("taller");
const campoTurnoIncidencia = document.getElementById("turno");
const campoFechaHoraIncidencia = document.getElementById("fechaHora");
const campoDocenteIncidencia = document.getElementById("docente");
const campoGrupoIncidencia = document.getElementById("grupo");
const campoAsignaturaIncidencia = document.getElementById("asignatura");
const campoNombreAlumnoIncidencia = document.getElementById("nombreAlumno");
const campoMaquinaIncidencia = document.getElementById("maquina");
const campoRecursoIncidencia = document.getElementById("recurso");
const campoTipoIncidencia = document.getElementById("tipoIncidencia");
const campoDescripcionIncidencia = document.getElementById("descripcion");

function cargarIncidenciasLocal() {
    const incidenciasGuardadas = localStorage.getItem("incidencias");
    if (incidenciasGuardadas === null) return [];
    return JSON.parse(incidenciasGuardadas);
}

function actualizarIncidenciasLocal(incidencias) {
    localStorage.setItem("incidencias", JSON.stringify(incidencias));
}

function obtenerDatosFormularioIncidencia() {
    const opcionReportaAlumno = document.querySelector('input[name="incidencias"]:checked');

    return {
        laboratorio: campoLaboratorioIncidencia.value,
        taller: campoTallerIncidencia.value,
        turno: campoTurnoIncidencia.value,
        fechaHora: campoFechaHoraIncidencia.value,
        docente: campoDocenteIncidencia.value.trim(),
        grupo: campoGrupoIncidencia.value.trim(),
        asignatura: campoAsignaturaIncidencia.value.trim(),
        reportaAlumno: opcionReportaAlumno.value,
        alumno: campoNombreAlumnoIncidencia.value.trim(),
        maquina: campoMaquinaIncidencia.value,
        recurso: campoRecursoIncidencia.value,
        tipo: campoTipoIncidencia.value,
        descripcion: campoDescripcionIncidencia.value.trim(),
        vencimiento: "Sin asignar",
        estado: "Pendiente",
        urgencia: "Sin asignar"
    };
}

function guardarIncidenciaLocal(incidencia) {
    const incidencias = cargarIncidenciasLocal();
    incidencia.id = incidencias.length + 1;
    incidencias.push(incidencia);
    actualizarIncidenciasLocal(incidencias);
}

function gestionarIncidencia(eventoFormulario) {
    eventoFormulario.preventDefault();
    const incidencia = obtenerDatosFormularioIncidencia();

    guardarIncidenciaLocal(incidencia);
    formularioIncidencia.reset();
    alert("Incidencia registrada correctamente");
}

if (formularioIncidencia) {
    formularioIncidencia.addEventListener("submit", gestionarIncidencia);
}
