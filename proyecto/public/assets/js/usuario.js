/**
 * Gestiona incidencias, solicitudes de servicio y registros de uso del solicitante.
 * @file
 */

/**
 * @typedef {Object} Incidencia
 * @property {number} id Identificador del reporte.
 * @property {string} laboratorio Laboratorio relacionado.
 * @property {string} taller Taller relacionado.
 * @property {string} turno Turno del reporte.
 * @property {string} fechaHora Fecha y hora informadas.
 * @property {string} docente Nombre del docente.
 * @property {string} grupo Grupo asociado.
 * @property {string} asignatura Asignatura asociada.
 * @property {string} reportaAlumno Indica si reportó un alumno.
 * @property {string} alumno Nombre del alumno.
 * @property {string} maquina Número de máquina.
 * @property {string} recurso Recurso afectado.
 * @property {string} tipo Tipo de incidencia.
 * @property {string} descripcion Descripción del problema.
 * @property {string} vencimiento Fecha límite asignada.
 * @property {string} estado Estado de la incidencia.
 * @property {string} urgencia Nivel de urgencia.
 * @property {string} tecnico Técnico asignado.
 */

/**
 * @typedef {Object} Solicitud
 * @property {number} id Identificador de la solicitud.
 * @property {string} laboratorio Laboratorio solicitado.
 * @property {string} taller Taller solicitado.
 * @property {string} turno Turno solicitado.
 * @property {string} docente Nombre del docente.
 * @property {string} asignatura Asignatura relacionada.
 * @property {string} email Correo del solicitante.
 * @property {string} fechaHora Fecha y hora solicitadas.
 * @property {string} tipoServicio Tipo de servicio.
 * @property {string} software Programa o aplicación.
 * @property {string} todasMaquinas Indica si aplica a todas las máquinas.
 * @property {string} prioridad Prioridad solicitada.
 * @property {string} descripcion Descripción de la solicitud.
 * @property {string} estado Estado asignado por el técnico.
 */

/**
 * @typedef {Object} RegistroUso
 * @property {number} id Identificador del registro.
 * @property {string} laboratorio Laboratorio utilizado.
 * @property {string} taller Taller utilizado.
 * @property {string} turno Turno del uso.
 * @property {string} fechaHora Fecha y hora del uso.
 * @property {string} docente Nombre del docente.
 * @property {string} grupo Grupo que utilizó el recurso.
 * @property {string} asignatura Asignatura relacionada.
 * @property {string} usoMaquinas Indica si se usaron máquinas.
 * @property {string} incidencias Indica si ocurrieron incidencias.
 */

/**
 * Obtiene un identificador mayor a todos los registros existentes.
 * @param {Array<{id: number}>} registros Lista de registros guardados.
 * @returns {number} Siguiente identificador disponible.
 */

function obtenerSiguienteId(registros) {
    let ultimoId = 0;

    for (const registro of registros) {
        if (registro.id > ultimoId) {
            ultimoId = registro.id;
        }
    }

    return ultimoId + 1;
}

/**
 * Formulario de reporte de incidencias.
 * @type {HTMLFormElement|null}
 */

const formularioIncidencia = document.getElementById("formularioIncidencia");

/**
 * Campo laboratorio de la incidencia.
 * @type {HTMLSelectElement|null}
 */

const campoLaboratorioIncidencia = document.getElementById("laboratorio");
/**
 * Campo taller de la incidencia.
 * @type {HTMLSelectElement|null}
 */

const campoTallerIncidencia = document.getElementById("taller");
/**
 * Campo turno de la incidencia.
 * @type {HTMLSelectElement|null}
 */

const campoTurnoIncidencia = document.getElementById("turno");
/**
 * Campo fecha y hora de la incidencia.
 * @type {HTMLInputElement|null}
 */

const campoFechaHoraIncidencia = document.getElementById("fechaHora");
/**
 * Campo docente de la incidencia.
 * @type {HTMLInputElement|null}
 */

const campoDocenteIncidencia = document.getElementById("docente");
/**
 * Campo grupo de la incidencia.
 * @type {HTMLInputElement|null}
 */

const campoGrupoIncidencia = document.getElementById("grupo");
/**
 * Campo asignatura de la incidencia.
 * @type {HTMLInputElement|null}
 */

const campoAsignaturaIncidencia = document.getElementById("asignatura");
/**
 * Campo nombre del alumno.
 * @type {HTMLInputElement|null}
 */

const campoNombreAlumnoIncidencia = document.getElementById("nombreAlumno");
/**
 * Campo número de máquina.
 * @type {HTMLInputElement|null}
 */

const campoMaquinaIncidencia = document.getElementById("maquina");
/**
 * Campo recurso afectado.
 * @type {HTMLSelectElement|null}
 */

const campoRecursoIncidencia = document.getElementById("recurso");
/**
 * Campo tipo de incidencia.
 * @type {HTMLSelectElement|null}
 */

const campoTipoIncidencia = document.getElementById("tipoIncidencia");
/**
 * Campo descripción de la incidencia.
 * @type {HTMLTextAreaElement|null}
 */

const campoDescripcionIncidencia = document.getElementById("descripcion");

/**
 * Obtiene las incidencias almacenadas localmente.
 * @returns {Incidencia[]} Lista de incidencias guardadas.
 */

function cargarIncidenciasLocal() {
    const incidenciasGuardadas = localStorage.getItem("incidencias");
    if (incidenciasGuardadas === null) return [];
    return JSON.parse(incidenciasGuardadas);
}

/**
 * Guarda la lista completa de incidencias.
 * @param {Incidencia[]} incidencias Lista que se almacenará.
 * @returns {void}
 */

function actualizarIncidenciasLocal(incidencias) {
    localStorage.setItem("incidencias", JSON.stringify(incidencias));
}

/**
 * Construye una incidencia con los datos del formulario.
 * @returns {Omit<Incidencia, "id">} Datos de la nueva incidencia.
 */

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

/**
 * Agrega una incidencia al almacenamiento local.
 * @param {Incidencia} incidencia Incidencia que se guardará.
 * @returns {void}
 */

function guardarIncidenciaLocal(incidencia) {
    const incidencias = cargarIncidenciasLocal();
    incidencia.id = obtenerSiguienteId(incidencias);
    incidencias.push(incidencia);
    actualizarIncidenciasLocal(incidencias);
}

/**
 * Procesa el envío del formulario de incidencias.
 * @param {SubmitEvent} eventoFormulario Evento de envío.
 * @returns {void}
 */

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

/**
 * Formulario de solicitudes de servicio.
 * @type {HTMLFormElement|null}
 */

const formularioSolicitud = document.getElementById("formularioSolicitud");

/**
 * Campo laboratorio de la solicitud.
 * @type {HTMLSelectElement|null}
 */

const campoLaboratorioSolicitud = document.getElementById("laboratorio");

/**
 * Campo taller de la solicitud.
 * @type {HTMLSelectElement|null}
 */

const campoTallerSolicitud = document.getElementById("taller");

/**
 * Campo turno de la solicitud.
 * @type {HTMLSelectElement|null}
 */

const campoTurnoSolicitud = document.getElementById("turno");

/**
 * Campo docente de la solicitud.
 * @type {HTMLInputElement|null}
 */

const campoDocenteSolicitud = document.getElementById("docente");

/**
 * Campo asignatura de la solicitud.
 * @type {HTMLInputElement|null}
 */

const campoAsignaturaSolicitud = document.getElementById("asignatura");

/**
 * Campo correo de la solicitud.
 * @type {HTMLInputElement|null}
 */

const campoEmailSolicitud = document.getElementById("email");

/**
 * Campo fecha y hora solicitadas.
 * @type {HTMLInputElement|null}
 */

const campoFechaHoraSolicitud = document.getElementById("fechaHora");

/**
 * Campo tipo de servicio.
 * @type {HTMLSelectElement|null}
 */

const campoTipoServicioSolicitud = document.getElementById("tipoServicio");

/**
 * Campo programa o aplicación.
 * @type {HTMLInputElement|null}
 */

const campoSoftwareSolicitud = document.getElementById("software");

/**
 * Campo prioridad de la solicitud.
 * @type {HTMLSelectElement|null}
 */

const campoPrioridadSolicitud = document.getElementById("prioridad");

/**
 * Campo descripción de la solicitud.
 * @type {HTMLTextAreaElement|null}
 */

const campoDescripcionSolicitud = document.getElementById("descripcion");

/**
 * Obtiene las solicitudes almacenadas localmente.
 * @returns {Solicitud[]} Lista de solicitudes guardadas.
 */

function cargarSolicitudesLocal() {
    const solicitudesGuardadas = localStorage.getItem("solicitudes");
    if (solicitudesGuardadas === null) return [];
    return JSON.parse(solicitudesGuardadas);
}

/**
 * Guarda la lista completa de solicitudes.
 * @param {Solicitud[]} solicitudes Lista que se almacenará.
 * @returns {void}
 */

function actualizarSolicitudesLocal(solicitudes) {
    localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
}

/**
 * Construye una solicitud con los datos del formulario.
 * @returns {Omit<Solicitud, "id">} Datos de la nueva solicitud.
 */

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

/**
 * Agrega una solicitud al almacenamiento local.
 * @param {Solicitud} solicitud Solicitud que se guardará.
 * @returns {void}
 */

function guardarSolicitudLocal(solicitud) {
    const solicitudes = cargarSolicitudesLocal();
    solicitud.id = obtenerSiguienteId(solicitudes);
    solicitudes.push(solicitud);
    actualizarSolicitudesLocal(solicitudes);
}

/**
 * Procesa el envío del formulario de solicitudes.
 * @param {SubmitEvent} eventoFormulario Evento de envío.
 * @returns {void}
 */

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

/**
 * Formulario de registros de uso.
 * @type {HTMLFormElement|null}
 */

const formularioRegistroUso = document.getElementById("formularioRegistroUso");

/**
 * Campo laboratorio del registro.
 * @type {HTMLSelectElement|null}
 */

const campoLaboratorioRegistro = document.getElementById("laboratorio");
/**
 * Campo taller del registro.
 * @type {HTMLSelectElement|null}
 */

const campoTallerRegistro = document.getElementById("taller");
/**
 * Campo turno del registro.
 * @type {HTMLSelectElement|null}
 */

const campoTurnoRegistro = document.getElementById("turno");
/**
 * Campo fecha y hora del registro.
 * @type {HTMLInputElement|null}
 */

const campoFechaHoraRegistro = document.getElementById("fechaHora");
/**
 * Campo docente del registro.
 * @type {HTMLInputElement|null}
 */

const campoDocenteRegistro = document.getElementById("docente");
/**
 * Campo grupo del registro.
 * @type {HTMLInputElement|null}
 */

const campoGrupoRegistro = document.getElementById("grupo");
/**
 * Campo asignatura del registro.
 * @type {HTMLInputElement|null}
 */

const campoAsignaturaRegistro = document.getElementById("asignatura");

/**
 * Obtiene los registros de uso almacenados localmente.
 * @returns {RegistroUso[]} Lista de registros guardados.
 */

function cargarRegistrosUsoLocal() {
    const registrosGuardados = localStorage.getItem("registrosUso");
    if (registrosGuardados === null) return [];
    return JSON.parse(registrosGuardados);
}

/**
 * Guarda la lista completa de registros de uso.
 * @param {RegistroUso[]} registros Lista que se almacenará.
 * @returns {void}
 */

function actualizarRegistrosUsoLocal(registros) {
    localStorage.setItem("registrosUso", JSON.stringify(registros));
}

/**
 * Construye un registro de uso con los datos del formulario.
 * @returns {Omit<RegistroUso, "id">} Datos del nuevo registro.
 */

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

/**
 * Agrega un registro de uso al almacenamiento local.
 * @param {RegistroUso} registro Registro que se guardará.
 * @returns {void}
 */

function guardarRegistroUsoLocal(registro) {
    const registros = cargarRegistrosUsoLocal();
    registro.id = obtenerSiguienteId(registros);
    registros.push(registro);
    actualizarRegistrosUsoLocal(registros);
}

/**
 * Procesa el envío del formulario de registros de uso.
 * @param {SubmitEvent} eventoFormulario Evento de envío.
 * @returns {void}
 */

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
