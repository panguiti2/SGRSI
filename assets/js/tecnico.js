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

function cargarIncidenciasTecnicoLocal() {
    const incidenciasGuardadas = localStorage.getItem("incidencias");
    if (incidenciasGuardadas === null) return [];
    return JSON.parse(incidenciasGuardadas);
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
