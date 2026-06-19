const btnRegistrarPrestamo = document.getElementById("btnRegistrarPrestamo");
const btnCerrarRegistarPrestamo = document.getElementById("btnCerrarRegistarPrestamo");
const dialogPrestamos = document.querySelector(".dialogPrestamos");
const formularioPrestamos = document.getElementById("formularioPrestamos");
const cuerpoTablaPrestamos = document.getElementById("cuerpoTablaPrestamos");

const campoCedula = document.getElementById("cedulaSolicitante");
const campoTurno = document.getElementById("turno");
const campoNombre = document.getElementById("nombreSolicitante");
const campoMaquina = document.getElementById("maquina");
const campoRetiro = document.getElementById("retiro");
const campoDevolucion = document.getElementById("devolucion");

let prestamoEnEdicion = false;

function abrirAltaPrestamo() {
    dialogPrestamos.showModal();
}

function limpiarFormularioPrestamo() {
    prestamoEnEdicion = false;
    campoCedula.readOnly = false;
    formularioPrestamos.reset();
}

function cerrarAltaPrestamo() {
    limpiarFormularioPrestamo();
    dialogPrestamos.close();
}

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

function cargarPrestamosGuardadosLocal() {
    const data = localStorage.getItem("prestamos");
    if (data === null) return [];
    return JSON.parse(data);
}

function actualizarPrestamosLocal(prestamos) {
    localStorage.setItem("prestamos", JSON.stringify(prestamos));
}

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

const cuerpoTablaIncidenciasTecnico = document.getElementById("cuerpoTablaIncidencias");
const dialogAsignarIncidencia = document.querySelector(".dialogAsignarIncidencia");
const formularioAsignarIncidencia = document.getElementById("formularioAsignarIncidencia");
const btnCerrarAsignarIncidencia = document.getElementById("btnCerrarAsignarIncidencia");
const campoVencimientoAsignacion = document.getElementById("vencimientoIncidencia");
const campoUrgenciaAsignacion = document.getElementById("urgenciaIncidencia");
const campoTecnicoAsignacion = document.getElementById("tecnicoIncidencia");

let incidenciaEnAsignacion = null;

function cargarIncidenciasTecnicoLocal() {
    const incidenciasGuardadas = localStorage.getItem("incidencias");
    if (incidenciasGuardadas === null) return [];
    return JSON.parse(incidenciasGuardadas);
}

function actualizarIncidenciasTecnicoLocal(incidencias) {
    localStorage.setItem("incidencias", JSON.stringify(incidencias));
}

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

function cerrarAsignarIncidencia() {
    incidenciaEnAsignacion = null;
    formularioAsignarIncidencia.reset();
    dialogAsignarIncidencia.close();
}

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

function eliminarIncidenciaTecnicoLocal(id) {
    const incidencias = cargarIncidenciasTecnicoLocal();

    if (confirm("¿Estás seguro de que deseas eliminar esta incidencia?")) {
        const nuevasIncidencias = incidencias.filter(incidencia => incidencia.id !== id);
        actualizarIncidenciasTecnicoLocal(nuevasIncidencias);
        actualizarTablaIncidenciasTecnico();
    }
}

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

const cuerpoTablaSolicitudesTecnico = document.getElementById("cuerpoTablaSolicitudes");
const dialogAsignarSolicitud = document.querySelector(".dialogAsignarSolicitud");
const formularioAsignarSolicitud = document.getElementById("formularioAsignarSolicitud");
const btnCerrarAsignarSolicitud = document.getElementById("btnCerrarAsignarSolicitud");
const campoEstadoAsignacionSolicitud = document.getElementById("estadoSolicitud");

let solicitudEnAsignacion = null;

function cargarSolicitudesTecnicoLocal() {
    const solicitudesGuardadas = localStorage.getItem("solicitudes");
    if (solicitudesGuardadas === null) return [];
    return JSON.parse(solicitudesGuardadas);
}

function actualizarSolicitudesTecnicoLocal(solicitudes) {
    localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
}

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

function cerrarAsignarSolicitud() {
    solicitudEnAsignacion = null;
    formularioAsignarSolicitud.reset();
    dialogAsignarSolicitud.close();
}

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

function eliminarSolicitudTecnicoLocal(id) {
    const solicitudes = cargarSolicitudesTecnicoLocal();

    if (confirm("¿Estás seguro de que deseas eliminar esta solicitud?")) {
        const nuevasSolicitudes = solicitudes.filter(solicitud => solicitud.id !== id);
        actualizarSolicitudesTecnicoLocal(nuevasSolicitudes);
        actualizarTablaSolicitudesTecnico();
    }
}

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

const cuerpoTablaRegistrosUso = document.getElementById("cuerpoTablaRegistrosUso");

function cargarRegistrosUsoTecnicoLocal() {
    const registrosGuardados = localStorage.getItem("registrosUso");
    if (registrosGuardados === null) return [];
    return JSON.parse(registrosGuardados);
}

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
