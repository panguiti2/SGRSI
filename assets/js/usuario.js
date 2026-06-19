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
        urgencia: "Sin asignar",
        tecnico: "Sin asignar"
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

const formularioSolicitud = document.getElementById("formularioSolicitud");

const campoLaboratorioSolicitud = document.getElementById("laboratorio");
const campoTallerSolicitud = document.getElementById("taller");
const campoTurnoSolicitud = document.getElementById("turno");
const campoDocenteSolicitud = document.getElementById("docente");
const campoAsignaturaSolicitud = document.getElementById("asignatura");
const campoEmailSolicitud = document.getElementById("email");
const campoFechaHoraSolicitud = document.getElementById("fechaHora");
const campoTipoServicioSolicitud = document.getElementById("tipoServicio");
const campoSoftwareSolicitud = document.getElementById("software");
const campoPrioridadSolicitud = document.getElementById("prioridad");
const campoDescripcionSolicitud = document.getElementById("descripcion");

function cargarSolicitudesLocal() {
    const solicitudesGuardadas = localStorage.getItem("solicitudes");
    if (solicitudesGuardadas === null) return [];
    return JSON.parse(solicitudesGuardadas);
}

function actualizarSolicitudesLocal(solicitudes) {
    localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
}

function obtenerDatosFormularioSolicitud() {
    const opcionTodasMaquinas = document.querySelector('input[name="todasMaquinas"]:checked');

    return {
        laboratorio: campoLaboratorioSolicitud.value,
        taller: campoTallerSolicitud.value,
        turno: campoTurnoSolicitud.value,
        docente: campoDocenteSolicitud.value.trim(),
        asignatura: campoAsignaturaSolicitud.value.trim(),
        email: campoEmailSolicitud.value.trim(),
        fechaHora: campoFechaHoraSolicitud.value,
        tipoServicio: campoTipoServicioSolicitud.value,
        software: campoSoftwareSolicitud.value.trim(),
        todasMaquinas: opcionTodasMaquinas.value,
        prioridad: campoPrioridadSolicitud.value,
        descripcion: campoDescripcionSolicitud.value.trim(),
        estado: "Sin asignar"
    };
}

function guardarSolicitudLocal(solicitud) {
    const solicitudes = cargarSolicitudesLocal();
    solicitud.id = solicitudes.length + 1;
    solicitudes.push(solicitud);
    actualizarSolicitudesLocal(solicitudes);
}

function gestionarSolicitud(eventoFormulario) {
    eventoFormulario.preventDefault();
    const solicitud = obtenerDatosFormularioSolicitud();

    guardarSolicitudLocal(solicitud);
    formularioSolicitud.reset();
    alert("Solicitud registrada correctamente");
}

if (formularioSolicitud) {
    formularioSolicitud.addEventListener("submit", gestionarSolicitud);
}

const formularioRegistroUso = document.getElementById("formularioRegistroUso");

const campoLaboratorioRegistro = document.getElementById("laboratorio");
const campoTallerRegistro = document.getElementById("taller");
const campoTurnoRegistro = document.getElementById("turno");
const campoFechaHoraRegistro = document.getElementById("fechaHora");
const campoDocenteRegistro = document.getElementById("docente");
const campoGrupoRegistro = document.getElementById("grupo");
const campoAsignaturaRegistro = document.getElementById("asignatura");

function cargarRegistrosUsoLocal() {
    const registrosGuardados = localStorage.getItem("registrosUso");
    if (registrosGuardados === null) return [];
    return JSON.parse(registrosGuardados);
}

function actualizarRegistrosUsoLocal(registros) {
    localStorage.setItem("registrosUso", JSON.stringify(registros));
}

function obtenerDatosFormularioRegistroUso() {
    const opcionUsoMaquinas = document.querySelector('input[name="usoMaquinas"]:checked');
    const opcionIncidencias = document.querySelector('input[name="incidencias"]:checked');

    return {
        laboratorio: campoLaboratorioRegistro.value,
        taller: campoTallerRegistro.value,
        turno: campoTurnoRegistro.value,
        fechaHora: campoFechaHoraRegistro.value,
        docente: campoDocenteRegistro.value.trim(),
        grupo: campoGrupoRegistro.value.trim(),
        asignatura: campoAsignaturaRegistro.value.trim(),
        usoMaquinas: opcionUsoMaquinas.value,
        incidencias: opcionIncidencias.value
    };
}

function guardarRegistroUsoLocal(registro) {
    const registros = cargarRegistrosUsoLocal();
    registro.id = registros.length + 1;
    registros.push(registro);
    actualizarRegistrosUsoLocal(registros);
}

function gestionarRegistroUso(eventoFormulario) {
    eventoFormulario.preventDefault();
    const registro = obtenerDatosFormularioRegistroUso();

    guardarRegistroUsoLocal(registro);
    formularioRegistroUso.reset();
    alert("Registro de uso guardado correctamente");
}

if (formularioRegistroUso) {
    formularioRegistroUso.addEventListener("submit", gestionarRegistroUso);
}
