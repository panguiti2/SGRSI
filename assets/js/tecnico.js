/**
 * Gestiona préstamos y las consultas y asignaciones realizadas por el técnico.
 * @file
 */

/**
 * @typedef {Object} Prestamo
 * @property {string} cedulaSolicitante Cédula del solicitante.
 * @property {string} turno Turno del préstamo.
 * @property {string} nombreSolicitante Nombre del solicitante.
 * @property {string} maquina Número de máquina.
 * @property {string} retiro Fecha y hora de retiro.
 * @property {string} devolucion Fecha y hora de devolución.
 */

/**
 * Botón para registrar préstamos.
 * @type {HTMLButtonElement|null}
 */

const btnRegistrarPrestamo = document.getElementById("btnRegistrarPrestamo");
/**
 * Botón para cerrar el formulario de préstamos.
 * @type {HTMLButtonElement|null}
 */

const btnCerrarRegistarPrestamo = document.getElementById("btnCerrarRegistarPrestamo");
/**
 * Diálogo de gestión de préstamos.
 * @type {HTMLDialogElement|null}
 */

const dialogPrestamos = document.querySelector(".dialogPrestamos");
/**
 * Formulario de préstamos.
 * @type {HTMLFormElement|null}
 */

const formularioPrestamos = document.getElementById("formularioPrestamos");
/**
 * Cuerpo de la tabla de préstamos.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaPrestamos = document.getElementById("cuerpoTablaPrestamos");

/**
 * Campo cédula del solicitante.
 * @type {HTMLInputElement|null}
 */

const campoCedula = document.getElementById("cedulaSolicitante");
/**
 * Campo turno del préstamo.
 * @type {HTMLSelectElement|null}
 */

const campoTurno = document.getElementById("turno");
/**
 * Campo nombre del solicitante.
 * @type {HTMLInputElement|null}
 */

const campoNombre = document.getElementById("nombreSolicitante");
/**
 * Campo número de máquina.
 * @type {HTMLInputElement|null}
 */

const campoMaquina = document.getElementById("maquina");
/**
 * Campo fecha de retiro.
 * @type {HTMLInputElement|null}
 */

const campoRetiro = document.getElementById("retiro");
/**
 * Campo fecha de devolución.
 * @type {HTMLInputElement|null}
 */

const campoDevolucion = document.getElementById("devolucion");

/**
 * Indica si se está modificando un préstamo.
 * @type {boolean}
 */

let prestamoEnEdicion = false;

/**
 * Abre el diálogo de préstamos.
 * @returns {void}
 */

function abrirAltaPrestamo() {
    dialogPrestamos.showModal();
}

/**
 * Restablece el formulario de préstamos.
 * @returns {void}
 */

function limpiarFormularioPrestamo() {
    prestamoEnEdicion = false;
    campoCedula.readOnly = false;
    formularioPrestamos.reset();
}

/**
 * Cierra y limpia el diálogo de préstamos.
 * @returns {void}
 */

function cerrarAltaPrestamo() {
    limpiarFormularioPrestamo();
    dialogPrestamos.close();
}

/**
 * Carga un préstamo en el formulario para modificarlo.
 * @param {string} cedulaSolicitante Cédula asociada al préstamo.
 * @returns {void}
 */

function abrirModificarPrestamo(cedulaSolicitante) {
    prestamoEnEdicion = true;
    const prestamos = cargarPrestamosGuardadosLocal();
    const prestamo = prestamos.find(p => p.cedulaSolicitante === cedulaSolicitante);

    if (prestamo === undefined) {
        alert("No se encontró el préstamo a modificar");
        return;
    }

    campoCedula.value = prestamo.cedulaSolicitante;
    campoTurno.value = prestamo.turno;
    campoNombre.value = prestamo.nombreSolicitante;
    campoMaquina.value = prestamo.maquina;
    campoRetiro.value = prestamo.retiro;
    campoDevolucion.value = prestamo.devolucion;

    campoCedula.readOnly = true;
    dialogPrestamos.showModal();
}

/**
 * Obtiene los préstamos almacenados localmente.
 * @returns {Prestamo[]} Lista de préstamos.
 */

function cargarPrestamosGuardadosLocal() {
    const data = localStorage.getItem("prestamos");
    if (data === null) return [];
    return JSON.parse(data);
}

/**
 * Guarda la lista completa de préstamos.
 * @param {Prestamo[]} prestamos Lista que se almacenará.
 * @returns {void}
 */

function actualizarPrestamosLocal(prestamos) {
    localStorage.setItem("prestamos", JSON.stringify(prestamos));
}

/**
 * Guarda un préstamo nuevo si la cédula no existe.
 * @param {Prestamo} prestamo Préstamo que se guardará.
 * @returns {void}
 */

function guardarPrestamoLocal(prestamo) {
    const prestamos = cargarPrestamosGuardadosLocal();
    const existe = prestamos.some(p => p.cedulaSolicitante === prestamo.cedulaSolicitante);

    if (existe) {
        alert("Ya existe un préstamo con esa cédula");
        return;
    }

    prestamos.push(prestamo);
    actualizarPrestamosLocal(prestamos);
}

/**
 * Elimina un préstamo según la cédula del solicitante.
 * @param {string} cedulaSolicitante Cédula asociada al préstamo.
 * @returns {void}
 */

function eliminarPrestamoLocal(cedulaSolicitante) {
    const prestamos = cargarPrestamosGuardadosLocal();

    if (confirm("¿Estás seguro de que deseas eliminar este archivo?")) {
        const nuevos = prestamos.filter(p => p.cedulaSolicitante !== cedulaSolicitante);
        actualizarPrestamosLocal(nuevos);
        actualizarTablaPrestamos();
        console.log("Archivo eliminado");
    } else {
        console.log("Acción cancelada");
    }
}

/**
 * Actualiza un préstamo existente.
 * @param {Prestamo} prestamoFormulario Datos del formulario.
 * @returns {void}
 */

function modificarPrestamoLocal(prestamoFormulario) {
    const prestamos = cargarPrestamosGuardadosLocal();
    const prestamo = prestamos.find(p => p.cedulaSolicitante === prestamoFormulario.cedulaSolicitante);

    if (prestamo === undefined) {
        alert("No se encontró el préstamo a modificar");
        return;
    }

    prestamo.turno = prestamoFormulario.turno;
    prestamo.nombreSolicitante = prestamoFormulario.nombreSolicitante;
    prestamo.maquina = prestamoFormulario.maquina;
    prestamo.retiro = prestamoFormulario.retiro;
    prestamo.devolucion = prestamoFormulario.devolucion;

    actualizarPrestamosLocal(prestamos);
}

/**
 * Obtiene los datos actuales del formulario de préstamos.
 * @returns {Prestamo} Préstamo ingresado.
 */

function obtenerDatosPrestamo() {
    return {
        cedulaSolicitante: campoCedula.value.trim(),
        turno: campoTurno.value,
        nombreSolicitante: campoNombre.value.trim(),
        maquina: campoMaquina.value,
        retiro: campoRetiro.value,
        devolucion: campoDevolucion.value
    };
}

/**
 * Procesa el alta o modificación de un préstamo.
 * @param {SubmitEvent} e Evento de envío.
 * @returns {void}
 */

function gestionarPrestamo(e) {
    e.preventDefault();
    const prestamo = obtenerDatosPrestamo();

    if (!prestamoEnEdicion) {
        guardarPrestamoLocal(prestamo);
    } else {
        modificarPrestamoLocal(prestamo);
    }

    cerrarAltaPrestamo();
    actualizarTablaPrestamos();
}

/**
 * Crea los botones para modificar y eliminar un préstamo.
 * @param {Prestamo} prestamo Préstamo de la fila.
 * @returns {HTMLTableCellElement} Celda de operaciones.
 */

function crearCampoOperacionesPrestamo(prestamo) {
    const td = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.textContent = "Modificar";
    btnModificar.classList.add("botonOperacion");
    btnModificar.addEventListener("click", () => abrirModificarPrestamo(prestamo.cedulaSolicitante));

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("botonOperacion");
    btnEliminar.addEventListener("click", () => eliminarPrestamoLocal(prestamo.cedulaSolicitante));

    td.appendChild(btnModificar);
    td.appendChild(btnEliminar);

    return td;
}

/**
 * Agrega un préstamo a la tabla.
 * @param {Prestamo} prestamo Préstamo que se mostrará.
 * @returns {void}
 */

function agregarFilaPrestamo(prestamo) {
    const fila = document.createElement("tr");

    const tdCedula = document.createElement("td");
    tdCedula.textContent = prestamo.cedulaSolicitante;

    const tdTurno = document.createElement("td");
    tdTurno.textContent = prestamo.turno;

    const tdNombre = document.createElement("td");
    tdNombre.textContent = prestamo.nombreSolicitante;

    const tdMaquina = document.createElement("td");
    tdMaquina.textContent = prestamo.maquina;

    const tdRetiro = document.createElement("td");
    tdRetiro.textContent = prestamo.retiro;

    const tdDevolucion = document.createElement("td");
    tdDevolucion.textContent = prestamo.devolucion;

    const tdOperaciones = crearCampoOperacionesPrestamo(prestamo);

    fila.appendChild(tdCedula);
    fila.appendChild(tdTurno);
    fila.appendChild(tdNombre);
    fila.appendChild(tdMaquina);
    fila.appendChild(tdRetiro);
    fila.appendChild(tdDevolucion);
    fila.appendChild(tdOperaciones);

    cuerpoTablaPrestamos.appendChild(fila);
}

/**
 * Actualiza la tabla con los préstamos guardados.
 * @returns {void}
 */

function actualizarTablaPrestamos() {
    cuerpoTablaPrestamos.replaceChildren();
    const prestamos = cargarPrestamosGuardadosLocal();

    for (const p of prestamos) {
        agregarFilaPrestamo(p);
    }
}

if (formularioPrestamos) {
    formularioPrestamos.addEventListener("submit", gestionarPrestamo);
}

if (btnRegistrarPrestamo) {
    btnRegistrarPrestamo.addEventListener("click", abrirAltaPrestamo);
}

if (btnCerrarRegistarPrestamo) {
    btnCerrarRegistarPrestamo.addEventListener("click", cerrarAltaPrestamo);
}

if (dialogPrestamos) {
    dialogPrestamos.addEventListener("cancel", limpiarFormularioPrestamo);
}

if (cuerpoTablaPrestamos) {
    actualizarTablaPrestamos();
}

/**
 * Cuerpo de la tabla técnica de incidencias.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaIncidenciasTecnico = document.getElementById("cuerpoTablaIncidencias");
/**
 * Diálogo para asignar una incidencia.
 * @type {HTMLDialogElement|null}
 */

const dialogAsignarIncidencia = document.querySelector(".dialogAsignarIncidencia");
/**
 * Formulario de asignación de incidencias.
 * @type {HTMLFormElement|null}
 */

const formularioAsignarIncidencia = document.getElementById("formularioAsignarIncidencia");
/**
 * Botón para cerrar la asignación de incidencias.
 * @type {HTMLButtonElement|null}
 */

const btnCerrarAsignarIncidencia = document.getElementById("btnCerrarAsignarIncidencia");
/**
 * Campo vencimiento de la incidencia.
 * @type {HTMLInputElement|null}
 */

const campoVencimientoAsignacion = document.getElementById("vencimientoIncidencia");
/**
 * Campo urgencia de la incidencia.
 * @type {HTMLSelectElement|null}
 */

const campoUrgenciaAsignacion = document.getElementById("urgenciaIncidencia");
/**
 * Campo técnico asignado.
 * @type {HTMLInputElement|null}
 */

const campoTecnicoAsignacion = document.getElementById("tecnicoIncidencia");

/**
 * Identificador de la incidencia que se está asignando.
 * @type {number|null}
 */

let incidenciaEnAsignacion = null;

/**
 * Obtiene las incidencias almacenadas localmente.
 * @returns {Incidencia[]} Lista de incidencias.
 */

function cargarIncidenciasTecnicoLocal() {
    const incidenciasGuardadas = localStorage.getItem("incidencias");
    if (incidenciasGuardadas === null) return [];
    return JSON.parse(incidenciasGuardadas);
}

/**
 * Guarda la lista completa de incidencias.
 * @param {Incidencia[]} incidencias Lista que se almacenará.
 * @returns {void}
 */

function actualizarIncidenciasTecnicoLocal(incidencias) {
    localStorage.setItem("incidencias", JSON.stringify(incidencias));
}

/**
 * Abre el formulario de asignación para una incidencia.
 * @param {number} id Identificador de la incidencia.
 * @returns {void}
 */

function abrirAsignarIncidencia(id) {
    const incidencias = cargarIncidenciasTecnicoLocal();
    const incidencia = incidencias.find(incidenciaGuardada => incidenciaGuardada.id === id);

    if (incidencia === undefined) {
        alert("No se encontró la incidencia");
        return;
    }

    incidenciaEnAsignacion = id;
    campoVencimientoAsignacion.value = "";
    campoUrgenciaAsignacion.value = "";
    campoTecnicoAsignacion.value = "";

    if (incidencia.vencimiento !== "Sin asignar") {
        campoVencimientoAsignacion.value = incidencia.vencimiento;
    }

    if (incidencia.urgencia !== "Sin asignar") {
        campoUrgenciaAsignacion.value = incidencia.urgencia;
    }

    if (incidencia.tecnico !== undefined && incidencia.tecnico !== "Sin asignar") {
        campoTecnicoAsignacion.value = incidencia.tecnico;
    }
    dialogAsignarIncidencia.showModal();
}

/**
 * Cierra y limpia el diálogo de asignación de incidencias.
 * @returns {void}
 */

function cerrarAsignarIncidencia() {
    incidenciaEnAsignacion = null;
    formularioAsignarIncidencia.reset();
    dialogAsignarIncidencia.close();
}

/**
 * Actualiza vencimiento, urgencia y técnico de una incidencia.
 * @param {SubmitEvent} eventoFormulario Evento de envío.
 * @returns {void}
 */

function modificarIncidenciaTecnicoLocal(eventoFormulario) {
    eventoFormulario.preventDefault();
    const incidencias = cargarIncidenciasTecnicoLocal();
    const incidencia = incidencias.find(incidenciaGuardada => {
        return incidenciaGuardada.id === incidenciaEnAsignacion;
    });

    if (incidencia === undefined) {
        alert("No se encontró la incidencia");
        return;
    }

    incidencia.vencimiento = campoVencimientoAsignacion.value;
    incidencia.urgencia = campoUrgenciaAsignacion.value;
    incidencia.tecnico = campoTecnicoAsignacion.value.trim();

    actualizarIncidenciasTecnicoLocal(incidencias);
    cerrarAsignarIncidencia();
    actualizarTablaIncidenciasTecnico();
}

/**
 * Elimina una incidencia según su identificador.
 * @param {number} id Identificador de la incidencia.
 * @returns {void}
 */

function eliminarIncidenciaTecnicoLocal(id) {
    const incidencias = cargarIncidenciasTecnicoLocal();

    if (confirm("¿Estás seguro de que deseas eliminar esta incidencia?")) {
        const nuevasIncidencias = incidencias.filter(incidencia => incidencia.id !== id);
        actualizarIncidenciasTecnicoLocal(nuevasIncidencias);
        actualizarTablaIncidenciasTecnico();
    }
}

/**
 * Crea los botones para modificar y eliminar una incidencia.
 * @param {Incidencia} incidencia Incidencia de la fila.
 * @returns {HTMLTableCellElement} Celda de operaciones.
 */

function crearCampoOperacionesIncidenciaTecnico(incidencia) {
    const campoOperaciones = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.textContent = "Modificar";
    btnModificar.classList.add("botonOperacion");
    btnModificar.addEventListener("click", () => abrirAsignarIncidencia(incidencia.id));

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("botonOperacion");
    btnEliminar.addEventListener("click", () => eliminarIncidenciaTecnicoLocal(incidencia.id));

    campoOperaciones.appendChild(btnModificar);
    campoOperaciones.appendChild(btnEliminar);

    return campoOperaciones;
}

/**
 * Agrega una incidencia a la tabla técnica.
 * @param {Incidencia} incidencia Incidencia que se mostrará.
 * @returns {void}
 */

function agregarFilaIncidenciaTecnico(incidencia) {
    const fila = document.createElement("tr");

    const campoIdIncidencia = document.createElement("td");
    campoIdIncidencia.textContent = incidencia.id;

    const campoLaboratorioIncidencia = document.createElement("td");
    campoLaboratorioIncidencia.textContent = incidencia.laboratorio;

    const campoTallerIncidencia = document.createElement("td");
    campoTallerIncidencia.textContent = incidencia.taller;

    const campoTurnoIncidencia = document.createElement("td");
    campoTurnoIncidencia.textContent = incidencia.turno;

    const campoFechaHoraIncidencia = document.createElement("td");
    campoFechaHoraIncidencia.textContent = incidencia.fechaHora;

    const campoDocenteIncidencia = document.createElement("td");
    campoDocenteIncidencia.textContent = incidencia.docente;

    const campoGrupoIncidencia = document.createElement("td");
    campoGrupoIncidencia.textContent = incidencia.grupo;

    const campoAsignaturaIncidencia = document.createElement("td");
    campoAsignaturaIncidencia.textContent = incidencia.asignatura;

    const campoReportaAlumnoIncidencia = document.createElement("td");
    campoReportaAlumnoIncidencia.textContent = incidencia.reportaAlumno;

    const campoAlumnoIncidencia = document.createElement("td");
    campoAlumnoIncidencia.textContent = incidencia.alumno;

    const campoMaquinaIncidencia = document.createElement("td");
    campoMaquinaIncidencia.textContent = incidencia.maquina;

    const campoRecursoIncidencia = document.createElement("td");
    campoRecursoIncidencia.textContent = incidencia.recurso;

    const campoTipoIncidencia = document.createElement("td");
    campoTipoIncidencia.textContent = incidencia.tipo;

    const campoDescripcionIncidencia = document.createElement("td");
    campoDescripcionIncidencia.textContent = incidencia.descripcion;

    const campoVencimientoIncidencia = document.createElement("td");
    campoVencimientoIncidencia.textContent = incidencia.vencimiento;

    const campoEstadoIncidencia = document.createElement("td");
    campoEstadoIncidencia.textContent = incidencia.estado;

    const campoUrgenciaIncidencia = document.createElement("td");
    campoUrgenciaIncidencia.textContent = incidencia.urgencia;

    const campoTecnicoIncidencia = document.createElement("td");
    campoTecnicoIncidencia.textContent = "Sin asignar";
    if (incidencia.tecnico !== undefined) {
        campoTecnicoIncidencia.textContent = incidencia.tecnico;
    }

    const campoOperacionesIncidencia = crearCampoOperacionesIncidenciaTecnico(incidencia);

    fila.appendChild(campoIdIncidencia);
    fila.appendChild(campoLaboratorioIncidencia);
    fila.appendChild(campoTallerIncidencia);
    fila.appendChild(campoTurnoIncidencia);
    fila.appendChild(campoFechaHoraIncidencia);
    fila.appendChild(campoDocenteIncidencia);
    fila.appendChild(campoGrupoIncidencia);
    fila.appendChild(campoAsignaturaIncidencia);
    fila.appendChild(campoReportaAlumnoIncidencia);
    fila.appendChild(campoAlumnoIncidencia);
    fila.appendChild(campoMaquinaIncidencia);
    fila.appendChild(campoRecursoIncidencia);
    fila.appendChild(campoTipoIncidencia);
    fila.appendChild(campoDescripcionIncidencia);
    fila.appendChild(campoVencimientoIncidencia);
    fila.appendChild(campoEstadoIncidencia);
    fila.appendChild(campoUrgenciaIncidencia);
    fila.appendChild(campoTecnicoIncidencia);
    fila.appendChild(campoOperacionesIncidencia);

    cuerpoTablaIncidenciasTecnico.appendChild(fila);
}

/**
 * Actualiza la tabla técnica de incidencias.
 * @returns {void}
 */

function actualizarTablaIncidenciasTecnico() {
    cuerpoTablaIncidenciasTecnico.replaceChildren();
    const incidencias = cargarIncidenciasTecnicoLocal();

    for (const incidencia of incidencias) {
        agregarFilaIncidenciaTecnico(incidencia);
    }
}

if (cuerpoTablaIncidenciasTecnico) {
    actualizarTablaIncidenciasTecnico();
}

if (formularioAsignarIncidencia) {
    formularioAsignarIncidencia.addEventListener("submit", modificarIncidenciaTecnicoLocal);
}

if (btnCerrarAsignarIncidencia) {
    btnCerrarAsignarIncidencia.addEventListener("click", cerrarAsignarIncidencia);
}

if (dialogAsignarIncidencia) {
    dialogAsignarIncidencia.addEventListener("cancel", cerrarAsignarIncidencia);
}

/**
 * Cuerpo de la tabla técnica de solicitudes.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaSolicitudesTecnico = document.getElementById("cuerpoTablaSolicitudes");
/**
 * Diálogo para asignar una solicitud.
 * @type {HTMLDialogElement|null}
 */

const dialogAsignarSolicitud = document.querySelector(".dialogAsignarSolicitud");
/**
 * Formulario de asignación de solicitudes.
 * @type {HTMLFormElement|null}
 */

const formularioAsignarSolicitud = document.getElementById("formularioAsignarSolicitud");
/**
 * Botón para cerrar la asignación de solicitudes.
 * @type {HTMLButtonElement|null}
 */

const btnCerrarAsignarSolicitud = document.getElementById("btnCerrarAsignarSolicitud");
/**
 * Campo estado de la solicitud.
 * @type {HTMLSelectElement|null}
 */

const campoEstadoAsignacionSolicitud = document.getElementById("estadoSolicitud");

/**
 * Identificador de la solicitud que se está asignando.
 * @type {number|null}
 */

let solicitudEnAsignacion = null;

/**
 * Obtiene las solicitudes almacenadas localmente.
 * @returns {Solicitud[]} Lista de solicitudes.
 */

function cargarSolicitudesTecnicoLocal() {
    const solicitudesGuardadas = localStorage.getItem("solicitudes");
    if (solicitudesGuardadas === null) return [];
    return JSON.parse(solicitudesGuardadas);
}

/**
 * Guarda la lista completa de solicitudes.
 * @param {Solicitud[]} solicitudes Lista que se almacenará.
 * @returns {void}
 */

function actualizarSolicitudesTecnicoLocal(solicitudes) {
    localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
}

/**
 * Abre el formulario de asignación para una solicitud.
 * @param {number} id Identificador de la solicitud.
 * @returns {void}
 */

function abrirAsignarSolicitud(id) {
    const solicitudes = cargarSolicitudesTecnicoLocal();
    const solicitud = solicitudes.find(solicitudGuardada => solicitudGuardada.id === id);

    if (solicitud === undefined) {
        alert("No se encontró la solicitud");
        return;
    }

    solicitudEnAsignacion = id;
    campoEstadoAsignacionSolicitud.value = "";
    if (solicitud.estado !== "Sin asignar") {
        campoEstadoAsignacionSolicitud.value = solicitud.estado;
    }
    dialogAsignarSolicitud.showModal();
}

/**
 * Cierra y limpia el diálogo de asignación de solicitudes.
 * @returns {void}
 */

function cerrarAsignarSolicitud() {
    solicitudEnAsignacion = null;
    formularioAsignarSolicitud.reset();
    dialogAsignarSolicitud.close();
}

/**
 * Actualiza el estado de una solicitud.
 * @param {SubmitEvent} eventoFormulario Evento de envío.
 * @returns {void}
 */

function modificarSolicitudTecnicoLocal(eventoFormulario) {
    eventoFormulario.preventDefault();
    const solicitudes = cargarSolicitudesTecnicoLocal();
    const solicitud = solicitudes.find(solicitudGuardada => {
        return solicitudGuardada.id === solicitudEnAsignacion;
    });

    if (solicitud === undefined) {
        alert("No se encontró la solicitud");
        return;
    }

    solicitud.estado = campoEstadoAsignacionSolicitud.value;

    actualizarSolicitudesTecnicoLocal(solicitudes);
    cerrarAsignarSolicitud();
    actualizarTablaSolicitudesTecnico();
}

/**
 * Elimina una solicitud según su identificador.
 * @param {number} id Identificador de la solicitud.
 * @returns {void}
 */

function eliminarSolicitudTecnicoLocal(id) {
    const solicitudes = cargarSolicitudesTecnicoLocal();

    if (confirm("¿Estás seguro de que deseas eliminar esta solicitud?")) {
        const nuevasSolicitudes = solicitudes.filter(solicitud => solicitud.id !== id);
        actualizarSolicitudesTecnicoLocal(nuevasSolicitudes);
        actualizarTablaSolicitudesTecnico();
    }
}

/**
 * Crea los botones para modificar y eliminar una solicitud.
 * @param {Solicitud} solicitud Solicitud de la fila.
 * @returns {HTMLTableCellElement} Celda de operaciones.
 */

function crearCampoOperacionesSolicitudTecnico(solicitud) {
    const campoOperaciones = document.createElement("td");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.textContent = "Modificar";
    btnModificar.classList.add("botonOperacion");
    btnModificar.addEventListener("click", () => abrirAsignarSolicitud(solicitud.id));

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("botonOperacion");
    btnEliminar.addEventListener("click", () => eliminarSolicitudTecnicoLocal(solicitud.id));

    campoOperaciones.appendChild(btnModificar);
    campoOperaciones.appendChild(btnEliminar);

    return campoOperaciones;
}

/**
 * Agrega una solicitud a la tabla técnica.
 * @param {Solicitud} solicitud Solicitud que se mostrará.
 * @returns {void}
 */

function agregarFilaSolicitudTecnico(solicitud) {
    const fila = document.createElement("tr");

    const campoIdSolicitud = document.createElement("td");
    campoIdSolicitud.textContent = solicitud.id;

    const campoLaboratorioSolicitud = document.createElement("td");
    campoLaboratorioSolicitud.textContent = solicitud.laboratorio;

    const campoTurnoSolicitud = document.createElement("td");
    campoTurnoSolicitud.textContent = solicitud.turno;

    const campoDocenteSolicitud = document.createElement("td");
    campoDocenteSolicitud.textContent = solicitud.docente;

    const campoEmailSolicitud = document.createElement("td");
    campoEmailSolicitud.textContent = solicitud.email;

    const campoFechaHoraSolicitud = document.createElement("td");
    campoFechaHoraSolicitud.textContent = solicitud.fechaHora;

    const campoSoftwareSolicitud = document.createElement("td");
    campoSoftwareSolicitud.textContent = solicitud.software;

    const campoTodasMaquinasSolicitud = document.createElement("td");
    campoTodasMaquinasSolicitud.textContent = solicitud.todasMaquinas;

    const campoTipoServicioSolicitud = document.createElement("td");
    campoTipoServicioSolicitud.textContent = solicitud.tipoServicio;

    const campoDescripcionSolicitud = document.createElement("td");
    campoDescripcionSolicitud.textContent = solicitud.descripcion;

    const campoEstadoSolicitud = document.createElement("td");
    campoEstadoSolicitud.textContent = solicitud.estado;

    const campoOperacionesSolicitud = crearCampoOperacionesSolicitudTecnico(solicitud);

    fila.appendChild(campoIdSolicitud);
    fila.appendChild(campoLaboratorioSolicitud);
    fila.appendChild(campoTurnoSolicitud);
    fila.appendChild(campoDocenteSolicitud);
    fila.appendChild(campoEmailSolicitud);
    fila.appendChild(campoFechaHoraSolicitud);
    fila.appendChild(campoSoftwareSolicitud);
    fila.appendChild(campoTodasMaquinasSolicitud);
    fila.appendChild(campoTipoServicioSolicitud);
    fila.appendChild(campoDescripcionSolicitud);
    fila.appendChild(campoEstadoSolicitud);
    fila.appendChild(campoOperacionesSolicitud);

    cuerpoTablaSolicitudesTecnico.appendChild(fila);
}

/**
 * Actualiza la tabla técnica de solicitudes.
 * @returns {void}
 */

function actualizarTablaSolicitudesTecnico() {
    cuerpoTablaSolicitudesTecnico.replaceChildren();
    const solicitudes = cargarSolicitudesTecnicoLocal();

    for (const solicitud of solicitudes) {
        agregarFilaSolicitudTecnico(solicitud);
    }
}

if (cuerpoTablaSolicitudesTecnico) {
    actualizarTablaSolicitudesTecnico();
}

if (formularioAsignarSolicitud) {
    formularioAsignarSolicitud.addEventListener("submit", modificarSolicitudTecnicoLocal);
}

if (btnCerrarAsignarSolicitud) {
    btnCerrarAsignarSolicitud.addEventListener("click", cerrarAsignarSolicitud);
}

if (dialogAsignarSolicitud) {
    dialogAsignarSolicitud.addEventListener("cancel", cerrarAsignarSolicitud);
}

/**
 * Cuerpo de la tabla de registros de uso.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaRegistrosUso = document.getElementById("cuerpoTablaRegistrosUso");

/**
 * Obtiene los registros de uso almacenados localmente.
 * @returns {RegistroUso[]} Lista de registros de uso.
 */

function cargarRegistrosUsoTecnicoLocal() {
    const registrosGuardados = localStorage.getItem("registrosUso");
    if (registrosGuardados === null) return [];
    return JSON.parse(registrosGuardados);
}

/**
 * Agrega un registro de uso a la tabla técnica.
 * @param {RegistroUso} registro Registro que se mostrará.
 * @returns {void}
 */

function agregarFilaRegistroUsoTecnico(registro) {
    const fila = document.createElement("tr");

    const campoIdRegistro = document.createElement("td");
    campoIdRegistro.textContent = registro.id;

    const campoLaboratorioRegistro = document.createElement("td");
    campoLaboratorioRegistro.textContent = registro.laboratorio;

    const campoTallerRegistro = document.createElement("td");
    campoTallerRegistro.textContent = registro.taller;

    const campoTurnoRegistro = document.createElement("td");
    campoTurnoRegistro.textContent = registro.turno;

    const campoFechaHoraRegistro = document.createElement("td");
    campoFechaHoraRegistro.textContent = registro.fechaHora;

    const campoDocenteRegistro = document.createElement("td");
    campoDocenteRegistro.textContent = registro.docente;

    const campoGrupoRegistro = document.createElement("td");
    campoGrupoRegistro.textContent = registro.grupo;

    const campoAsignaturaRegistro = document.createElement("td");
    campoAsignaturaRegistro.textContent = registro.asignatura;

    const campoUsoMaquinasRegistro = document.createElement("td");
    campoUsoMaquinasRegistro.textContent = registro.usoMaquinas;

    const campoIncidenciasRegistro = document.createElement("td");
    campoIncidenciasRegistro.textContent = registro.incidencias;

    fila.appendChild(campoIdRegistro);
    fila.appendChild(campoLaboratorioRegistro);
    fila.appendChild(campoTallerRegistro);
    fila.appendChild(campoTurnoRegistro);
    fila.appendChild(campoFechaHoraRegistro);
    fila.appendChild(campoDocenteRegistro);
    fila.appendChild(campoGrupoRegistro);
    fila.appendChild(campoAsignaturaRegistro);
    fila.appendChild(campoUsoMaquinasRegistro);
    fila.appendChild(campoIncidenciasRegistro);

    cuerpoTablaRegistrosUso.appendChild(fila);
}

/**
 * Actualiza la tabla técnica de registros de uso.
 * @returns {void}
 */

function actualizarTablaRegistrosUsoTecnico() {
    cuerpoTablaRegistrosUso.replaceChildren();
    const registros = cargarRegistrosUsoTecnicoLocal();

    for (const registro of registros) {
        agregarFilaRegistroUsoTecnico(registro);
    }
}

if (cuerpoTablaRegistrosUso) {
    actualizarTablaRegistrosUsoTecnico();
}

/**
 * Cuerpo de la tabla técnica de inventario.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaInventarioTecnico = document.getElementById("cuerpoTablaInventarioTecnico");

/**
 * Obtiene los dispositivos registrados por el administrador.
 * @returns {Dispositivo[]} Lista de dispositivos almacenados.
 */

function cargarInventarioTecnicoLocal() {
    const dispositivosGuardados = localStorage.getItem("dispositivos");
    if (dispositivosGuardados === null) return [];
    return JSON.parse(dispositivosGuardados);
}

/**
 * Agrega un dispositivo a la tabla técnica de inventario.
 * @param {Dispositivo} dispositivo Dispositivo que se mostrará.
 * @returns {void}
 */

function agregarFilaInventarioTecnico(dispositivo) {
    const fila = document.createElement("tr");

    const campoIdDispositivo = document.createElement("td");
    campoIdDispositivo.textContent = dispositivo.id;

    const campoMarcaDispositivo = document.createElement("td");
    campoMarcaDispositivo.textContent = dispositivo.marca;

    const campoNumeroDispositivo = document.createElement("td");
    campoNumeroDispositivo.textContent = dispositivo.numeroDispositivo;

    const campoLaboratorioDispositivo = document.createElement("td");
    campoLaboratorioDispositivo.textContent = dispositivo.laboratorio;

    const campoTallerDispositivo = document.createElement("td");
    campoTallerDispositivo.textContent = dispositivo.taller;

    const campoRecursoDispositivo = document.createElement("td");
    campoRecursoDispositivo.textContent = dispositivo.recurso;

    const campoModificacionesDispositivo = document.createElement("td");
    campoModificacionesDispositivo.textContent = dispositivo.modificaciones;

    const campoEstadoDispositivo = document.createElement("td");
    campoEstadoDispositivo.textContent = dispositivo.estado;

    const campoUltimoCambioDispositivo = document.createElement("td");
    campoUltimoCambioDispositivo.textContent = dispositivo.ultimoCambio;

    fila.appendChild(campoIdDispositivo);
    fila.appendChild(campoMarcaDispositivo);
    fila.appendChild(campoNumeroDispositivo);
    fila.appendChild(campoLaboratorioDispositivo);
    fila.appendChild(campoTallerDispositivo);
    fila.appendChild(campoRecursoDispositivo);
    fila.appendChild(campoModificacionesDispositivo);
    fila.appendChild(campoEstadoDispositivo);
    fila.appendChild(campoUltimoCambioDispositivo);

    cuerpoTablaInventarioTecnico.appendChild(fila);
}

/**
 * Actualiza la tabla técnica con los dispositivos guardados.
 * @returns {void}
 */

function actualizarTablaInventarioTecnico() {
    cuerpoTablaInventarioTecnico.replaceChildren();
    const dispositivos = cargarInventarioTecnicoLocal();

    for (const dispositivo of dispositivos) {
        agregarFilaInventarioTecnico(dispositivo);
    }
}

if (cuerpoTablaInventarioTecnico) {
    actualizarTablaInventarioTecnico();
}
