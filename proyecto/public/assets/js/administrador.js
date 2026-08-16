/**
 * Gestiona usuarios, dispositivos e incidencias en las pantallas del administrador.
 * @file
 */

/**
 * @typedef {Object} Usuario
 * @property {string} cedula Documento del usuario.
 * @property {string} nombre Nombre del usuario.
 * @property {string} apellido Apellido del usuario.
 * @property {string} cargo Rol asignado.
 */

/**
 * @typedef {Object} Dispositivo
 * @property {string} numeroDispositivo Número del dispositivo.
 * @property {string} laboratorio Laboratorio asignado.
 * @property {string} recurso Tipo de recurso.
 * @property {string} modificaciones Modificación realizada.
 * @property {string} estado Estado actual.
 * @property {string} ultimoCambio Fecha del último cambio.
 */

/**
 * Botón para abrir el alta de usuarios.
 * @type {HTMLButtonElement|null}
 */

const btnAltaUsuario = document.getElementById("btnAltaUsuario");
/**
 * Botón para cerrar el alta de usuarios.
 * @type {HTMLButtonElement|null}
 */

const btnCerrarAltaUsuario = document.getElementById("btnCerrarAltaUsuario");
/**
 * Diálogo de gestión de usuarios.
 * @type {HTMLDialogElement|null}
 */

const dialogAltaUsuario = document.querySelector(".dialogAltaUsuario");
/**
 * Formulario de usuarios.
 * @type {HTMLFormElement|null}
 */

const formularioAltaUsuario = document.getElementById("formularioAltaUsuario");
/**
 * Cuerpo de la tabla de usuarios.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaUsuarios = document.getElementById("cuerpoTablaUsuarios");

/**
 * Campo cédula del usuario.
 * @type {HTMLInputElement|null}
 */

const campoCedula = document.getElementById("cedula");
/**
 * Campo nombre del usuario.
 * @type {HTMLInputElement|null}
 */

const campoNombre = document.getElementById("nombre");
/**
 * Campo apellido del usuario.
 * @type {HTMLInputElement|null}
 */

const campoApellido = document.getElementById("apellido");
/**
 * Campo cargo del usuario.
 * @type {HTMLSelectElement|null}
 */

const campoCargo = document.getElementById("cargo");

/**
 * Indica si se está modificando un usuario.
 * @type {boolean}
 */

let usuarioEnEdicion = false;

/**
 * Abre el diálogo de usuarios.
 * @returns {void}
 */

function abrirAltaUsuario() {
    dialogAltaUsuario.showModal();
}

/**
 * Restablece el formulario de usuarios.
 * @returns {void}
 */

function limpiarFormularioAltaUsuario() {
    usuarioEnEdicion = false;
    campoCedula.readOnly = false;
    formularioAltaUsuario.reset();
}

/**
 * Cierra y limpia el diálogo de usuarios.
 * @returns {void}
 */

function cerrarAltaUsuario() {
    limpiarFormularioAltaUsuario();
    dialogAltaUsuario.close();
}

// La tabla de usuarios se carga desde PHP y MySQL, no desde localStorage.
if (false) {
/**
 * Carga un usuario en el formulario para modificarlo.
 * @param {string} cedula Cédula del usuario.
 * @returns {void}
 */
function abrirModificarUsuario(cedula) {
    usuarioEnEdicion = true;
    const usuarios = cargarUsuariosGuardadosLocal();

    const usuarioAModificar = usuarios.find(usuario => {
        return usuario.cedula === cedula;
    });

    if (usuarioAModificar === undefined) {
        alert("No se encontró un usuario con esa cédula");
        return;
    }

    campoCedula.value = usuarioAModificar.cedula;
    campoNombre.value = usuarioAModificar.nombre;
    campoApellido.value = usuarioAModificar.apellido;
    campoCargo.value = usuarioAModificar.cargo;

    campoCedula.readOnly = true;
    dialogAltaUsuario.showModal();
}

/**
 * Obtiene los usuarios almacenados localmente.
 * @returns {Usuario[]} Lista de usuarios.
 */

function cargarUsuariosGuardadosLocal() {
    const usuariosGuardados = localStorage.getItem("usuarios");
    if (usuariosGuardados === null) return [];
    return JSON.parse(usuariosGuardados);
}

/**
 * Obtiene los datos actuales del formulario de usuarios.
 * @returns {Usuario} Usuario ingresado.
 */

function obtenerDatosFormularioUsuario() {
    const cedula = campoCedula.value.trim();
    const nombre = campoNombre.value.trim();
    const apellido = campoApellido.value.trim();
    const cargo = campoCargo.value;

    return { cedula, nombre, apellido, cargo };
}

/**
 * Crea los botones para modificar y eliminar un usuario.
 * @param {Usuario} usuario Usuario de la fila.
 * @returns {HTMLDivElement} Contenedor de operaciones.
 */

function crearCampoOperacionesUsuario(usuario) {
    const campoOperaciones = document.createElement("div");
    campoOperaciones.classList.add("cajaOperaciones");

    const btnModificar = document.createElement("button");
    btnModificar.type = "button";
    btnModificar.textContent = "Modificar";
    btnModificar.classList.add("botonOperacion");
    btnModificar.addEventListener("click", () => {
        abrirModificarUsuario(usuario.cedula);
    });

    const btnEliminar = document.createElement("button");
    btnEliminar.type = "button";
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("botonOperacion");
    btnEliminar.addEventListener("click", () => {
        eliminarUsuarioLocal(usuario.cedula);
    });

    campoOperaciones.appendChild(btnModificar);
    campoOperaciones.appendChild(btnEliminar);

    return campoOperaciones;
}

/**
 * Agrega un usuario a la tabla.
 * @param {Usuario} usuario Usuario que se mostrará.
 * @returns {void}
 */

function agregarFilaUsuario(usuario) {
    const fila = document.createElement("tr");

    const campoCedula = document.createElement("td");
    campoCedula.textContent = usuario.cedula;

    const campoNombre = document.createElement("td");
    campoNombre.textContent = usuario.nombre;

    const campoApellido = document.createElement("td");
    campoApellido.textContent = usuario.apellido;

    const campoCargo = document.createElement("td");
    campoCargo.textContent = usuario.cargo;

    const campoOperaciones = crearCampoOperacionesUsuario(usuario);

    fila.appendChild(campoCedula);
    fila.appendChild(campoNombre);
    fila.appendChild(campoApellido);
    fila.appendChild(campoCargo);
    fila.appendChild(campoOperaciones);

    cuerpoTablaUsuarios.appendChild(fila);
}

/**
 * Actualiza la tabla con los usuarios guardados.
 * @returns {void}
 */

function actualizarTablaUsuarios() {
    cuerpoTablaUsuarios.replaceChildren();
    const usuarios = cargarUsuariosGuardadosLocal();

    for (const usuario of usuarios) {
        agregarFilaUsuario(usuario);
    }
}

/**
 * Elimina un usuario según su cédula.
 * @param {string} cedula Cédula del usuario.
 * @returns {void}
 */

function eliminarUsuarioLocal(cedula) {
    const usuarios = cargarUsuariosGuardadosLocal();

    if (confirm("¿Estás seguro de que deseas eliminar este usuario?")) {
        const usuariosActualizados = usuarios.filter(usuario => {
            return usuario.cedula !== cedula;
        });
        actualizarUsuariosLocal(usuariosActualizados);
        actualizarTablaUsuarios();
        console.log("Usuario eliminado");
    } else {
        console.log("Acción cancelada");
    }
}

/**
 * Actualiza los datos editables de un usuario.
 * @param {Usuario} usuarioEnFormulario Datos del formulario.
 * @returns {void}
 */

function modificarUsuarioLocal(usuarioEnFormulario) {
    const usuarios = cargarUsuariosGuardadosLocal();

    const usuarioAModificar = usuarios.find(usuario => {
        return usuario.cedula === usuarioEnFormulario.cedula;
    });

    if (usuarioAModificar === undefined) {
        alert("No se encontró un usuario con esa cédula");
        return;
    }

    usuarioAModificar.nombre = usuarioEnFormulario.nombre;
    usuarioAModificar.apellido = usuarioEnFormulario.apellido;
    usuarioAModificar.cargo = usuarioEnFormulario.cargo;

    actualizarUsuariosLocal(usuarios);
}

/**
 * Guarda la lista completa de usuarios.
 * @param {Usuario[]} usuarios Lista que se almacenará.
 * @returns {void}
 */

function actualizarUsuariosLocal(usuarios) {
    localStorage.setItem("usuarios", JSON.stringify(usuarios));
}

/**
 * Guarda un usuario nuevo si su cédula no existe.
 * @param {Usuario} usuario Usuario que se guardará.
 * @returns {void}
 */

function guardarUsuarioLocal(usuario) {
    const usuarios = cargarUsuariosGuardadosLocal();

    const cedulaExistente = usuarios.some((usuarioGuardado) => {
        return usuarioGuardado.cedula === usuario.cedula;
    });

    if (cedulaExistente) {
        alert("Ya existe un usuario con esa cédula");
        return;
    }

    usuarios.push(usuario);
    actualizarUsuariosLocal(usuarios);
}

/**
 * Procesa el alta o modificación de un usuario.
 * @param {SubmitEvent} eventoFormulario Evento de envío.
 * @returns {void}
 */

function gestionarUsuario(eventoFormulario) {
    eventoFormulario.preventDefault();

    const usuario = obtenerDatosFormularioUsuario();

    if (!usuarioEnEdicion) {
        guardarUsuarioLocal(usuario);
    } else {
        modificarUsuarioLocal(usuario);
    }

    cerrarAltaUsuario();
    actualizarTablaUsuarios();
}

if (formularioAltaUsuario) {
    formularioAltaUsuario.addEventListener("submit", gestionarUsuario);
}
}

if (btnAltaUsuario) {
    btnAltaUsuario.addEventListener("click", abrirAltaUsuario);
}

if (btnCerrarAltaUsuario) {
    btnCerrarAltaUsuario.addEventListener("click", cerrarAltaUsuario);
}

if (dialogAltaUsuario) {
    dialogAltaUsuario.addEventListener("cancel", limpiarFormularioAltaUsuario);
}

//if (cuerpoTablaUsuarios) {
 //   actualizarTablaUsuarios();
//}

/**
 * Botón para abrir el alta de dispositivos.
 * @type {HTMLButtonElement|null}
 */

const btnAltaDispositivo = document.getElementById("btnAltaDispositivo");
/**
 * Botón para cerrar el alta de dispositivos.
 * @type {HTMLButtonElement|null}
 */

const btnCerrarAltaDispositivo = document.getElementById("btnCerrarAltaDispositivo");
/**
 * Diálogo de gestión de dispositivos.
 * @type {HTMLDialogElement|null}
 */

const dialogAltaDispositivo = document.querySelector(".dialogAltaDispositivo");
/**
 * Formulario de dispositivos.
 * @type {HTMLFormElement|null}
 */

const formularioAltaDispositivo = document.getElementById("formularioAltaDispositivo");
/**
 * Cuerpo de la tabla de dispositivos.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaDispositivos = document.getElementById("cuerpoTablaDispositivos");

/**
 * Campo número del dispositivo.
 * @type {HTMLInputElement|null}
 */

const campoNumero = document.getElementById("numeroDispositivo");
/**
 * Campo tipo de recurso.
 * @type {HTMLSelectElement|null}
 */

const campoRecurso = document.getElementById("recurso");
/**
 * Campo laboratorio del dispositivo.
 * @type {HTMLSelectElement|null}
 */

const campoLaboratorio = document.getElementById("idLab");
/**
 * Campo modificaciones del dispositivo.
 * @type {HTMLSelectElement|null}
 */

const campoModificaciones = document.getElementById("modificaciones");
/**
 * Campo estado del dispositivo.
 * @type {HTMLSelectElement|null}
 */

const campoEstado = document.getElementById("estado");
/**
 * Campo fecha del último cambio.
 * @type {HTMLInputElement|null}
 */

const campoUltimoCambio = document.getElementById("ultimoCambio");

/**
 * Indica si se está modificando un dispositivo.
 * @type {boolean}
 */

let dispositivoEnEdicion = false;

/**
 * Abre el diálogo de dispositivos.
 * @returns {void}
 */

function abrirAltaDispositivo() {
    if (dialogAltaDispositivo) {
        dialogAltaDispositivo.showModal();
    }
}

/**
 * Cierra y limpia el diálogo de dispositivos.
 * @returns {void}
 */

function cerrarAltaDispositivo() {
    limpiarFormularioAltaDispositivo();
    if (dialogAltaDispositivo) {
        dialogAltaDispositivo.close();
    }
}

/**
 * Restablece el formulario de dispositivos.
 * @returns {void}
 */

function limpiarFormularioAltaDispositivo() {
    if (formularioAltaDispositivo) {
        formularioAltaDispositivo.reset();
    }
    dispositivoEnEdicion = false;
}

// La tabla de dispositivos se carga desde PHP y MySQL, no desde localStorage.

if (btnAltaDispositivo) {
    btnAltaDispositivo.addEventListener("click", abrirAltaDispositivo);
}

if (btnCerrarAltaDispositivo) {
    btnCerrarAltaDispositivo.addEventListener("click", cerrarAltaDispositivo);
}

if (dialogAltaDispositivo) {
    dialogAltaDispositivo.addEventListener("cancel", limpiarFormularioAltaDispositivo);
}

//if (cuerpoTablaDispositivos) {
//    actualizarTablaDispositivos();
//}

/**
 * Cuerpo de la tabla de incidencias del administrador.
 * @type {HTMLTableSectionElement|null}
 */

const cuerpoTablaIncidenciasAdmin = document.getElementById("cuerpoTablaIncidencias");

/**
 * Obtiene las incidencias almacenadas localmente.
 * @returns {Incidencia[]} Lista de incidencias.
 */

function cargarIncidenciasAdminLocal() {
    const incidenciasGuardadas = localStorage.getItem("incidencias");
    if (incidenciasGuardadas === null) return [];
    return JSON.parse(incidenciasGuardadas);
}

/**
 * Agrega una incidencia a la tabla del administrador.
 * @param {Incidencia} incidencia Incidencia que se mostrará.
 * @returns {void}
 */

function agregarFilaIncidenciaAdmin(incidencia) {
    const fila = document.createElement("tr");

    const campoIdIncidencia = document.createElement("td");
    campoIdIncidencia.textContent = incidencia.id;

    const campoLaboratorioIncidencia = document.createElement("td");
    campoLaboratorioIncidencia.textContent = incidencia.laboratorio;


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

    fila.appendChild(campoIdIncidencia);
    fila.appendChild(campoLaboratorioIncidencia);
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

    cuerpoTablaIncidenciasAdmin.appendChild(fila);
}

/**
 * Actualiza la tabla administrativa de incidencias.
 * @returns {void}
 */
function actualizarTablaIncidenciasAdmin() {
    cuerpoTablaIncidenciasAdmin.replaceChildren();
    const incidencias = cargarIncidenciasAdminLocal();

    for (const incidencia of incidencias) {
        agregarFilaIncidenciaAdmin(incidencia);
    }
}

//if (cuerpoTablaIncidenciasAdmin) {
 //   actualizarTablaIncidenciasAdmin();
// }
