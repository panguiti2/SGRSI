const mensajesPorPagina = {
    "admin/incidencias": {},
    "admin/inicio": {},
    "admin/inventario": {
        "peticion": "La petición no es válida.",
        "campos_vacios": "Debe completar todos los campos del dispositivo.",
        "datos_incorrectos": "Los datos del dispositivo no son válidos.",
        "conexion": "No se pudo establecer conexión con la base de datos.",
        "error_dispositivo": "No se pudo registrar el dispositivo.",
        "dispositivo_en_uso": "No se puede eliminar el dispositivo porque está asociado a un ticket.",
        "exito": "El dispositivo se registró exitosamente."
    },
    "admin/metricas": {},
    "admin/usuarios": {
        "credenciales": "La cédula o la contraseña son incorrectas.",
        "peticion": "La petición de ingreso no es válida.",
        "contraseña": "Las contraseñas ingresadas no coinciden.",
        "campos_vacios": "No se pudo registrar el empleado: existen campos vacíos.",
        "cedula_incorrecta": "No se pudo registrar el empleado: cédula incorrecta.",
        "datos_incorrectos": "Los datos del usuario no son válidos.",
        "contraseña_corta": "La contraseña debe contener al menos 12 caracteres.",
        "rol_incorrecto": "Debe seleccionar un rol válido.",
        "conexion": "No se pudo establecer conexión con la base de datos.",
        "error_usuario": "No se pudo registrar el usuario.",
        "exito": "El usuario se registró exitosamente."
    },
    "tecnico/incidencias": {
        "datos_incorrectos": "Los datos de gestión no son válidos.",
        "asignacion": "No se pudo gestionar la incidencia."
    },
    "tecnico/inicio": {},
    "tecnico/inventario": {},
    "tecnico/metricas": {},
    "tecnico/prestamos": {
        "peticion": "La petición no es válida.",
        "campos_vacios": "Debe completar todos los campos del préstamo.",
        "datos_incorrectos": "Los datos del préstamo no son válidos.",
        "error_prestamo": "No se pudo registrar el préstamo.",
        "error_devolucion": "No se pudo registrar la devolución."
    },
    "tecnico/registrosUso": {},
    "tecnico/solicitudes": {
        "datos_incorrectos": "Los datos de gestión no son válidos.",
        "estado": "No se pudo actualizar el estado de la solicitud."
    },
    "solicitante/incidencias": {},
    "solicitante/inicio": {},
    "solicitante/registroUso": {
        "peticion": "La petición no es válida.",
        "datos_incorrectos": "Debe completar los datos del registro correctamente.",
        "error_registro": "No se pudo guardar el registro de uso."
    },
    "solicitante/solicitudes": {}
};

const carpetaRol = document.body.dataset.rol === "administrador" ? "admin" : document.body.dataset.rol;
const mensajesActuales = mensajesPorPagina[carpetaRol + "/" + document.body.dataset.seccion] || {};

window.csrfToken = "";
window.dispositivosFormulario = [];

function cargarOpciones(id, opciones, clave) {
    const selector = document.getElementById(id);
    if (!selector || !opciones) return;
    if (selector.options.length === 0) {
        const inicial = document.createElement("option");
        inicial.value = "";
        inicial.disabled = true;
        inicial.selected = true;
        inicial.textContent = "Seleccione";
        selector.appendChild(inicial);
    }
    for (const dato of opciones) {
        const opcion = document.createElement("option");
        opcion.value = dato[clave];
        opcion.textContent = dato.nombre;
        selector.appendChild(opcion);
    }
}

async function cargarFormulario() {
    const rol = document.body.dataset.rol;
    const seccion = document.body.dataset.seccion;
    try {
        const respuesta = await fetch("../api/formulario.php?rol=" + encodeURIComponent(rol)
            + "&seccion=" + encodeURIComponent(seccion));
        if (!respuesta.ok) {
            if (respuesta.status === 401 || respuesta.status === 403) {
                window.location.href = "../login.php?error=" + (respuesta.status === 401 ? "sin_sesion" : "no_autorizado");
            } else {
                const contenido = await respuesta.json();
                alert(contenido.mensaje);
            }
            return false;
        }
        const contenido = await respuesta.json();
        const datos = contenido.datos;
        window.csrfToken = datos.csrfToken;
        window.dispositivosFormulario = datos.dispositivosFormulario || [];
        cargarOpciones("idLaboratorio", datos.laboratorios, "idLaboratorio");
        cargarOpciones("idLab", datos.laboratorios, "idLaboratorio");
        cargarOpciones("turno", datos.turnos, "codigo");
        cargarOpciones("tipoServicio", datos.tiposServicio, "codigo");
        cargarOpciones("modificaciones", datos.modificaciones, "codigo");
        cargarOpciones("estadoSolicitud", datos.estadosTicket, "codigo");
        cargarOpciones("estadoIncidencia", datos.estadosTicket, "codigo");
        for (const campo of document.querySelectorAll('input[name="csrfToken"]')) {
            campo.value = datos.csrfToken;
        }
        return true;
    } catch (error) {
        console.error(error);
        alert("No se pudieron cargar las opciones. Compruebe la conexión con el servidor.");
        return false;
    }
}

const codigoErrorPagina = new URLSearchParams(window.location.search).get("error");
const mensajePagina = document.getElementById("mensajePagina");
if (mensajePagina && mensajesActuales[codigoErrorPagina]) {
    mensajePagina.textContent = mensajesActuales[codigoErrorPagina];
    mensajePagina.className = "alert alert-danger";
    mensajePagina.hidden = false;
}

const formularioListo = cargarFormulario();
