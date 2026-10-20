const dialogUsuario = document.querySelector(".dialogAltaUsuario");
const formularioUsuario = document.getElementById("formularioAltaUsuario");
const campoCedulaUsuario = document.getElementById("cedula");
const campoNombreUsuario = document.getElementById("nombre");
const campoApellidoUsuario = document.getElementById("apellido");
const campoRolUsuario = document.getElementById("rol");
const campoClaveUsuario = document.getElementById("contrasena");
const campoConfirmarClaveUsuario = document.getElementById("confirmarContrasena");
let usuarioEnEdicion = false;

function limpiarEstadoUsuario() {
    usuarioEnEdicion = false;
    formularioUsuario.reset();
    campoCedulaUsuario.readOnly = false;
    campoClaveUsuario.required = true;
    campoConfirmarClaveUsuario.required = true;
    campoClaveUsuario.placeholder = "";
    campoConfirmarClaveUsuario.placeholder = "";
    campoClaveUsuario.setCustomValidity("");
    campoConfirmarClaveUsuario.setCustomValidity("");
    document.getElementById("tituloFormularioUsuario").textContent = "Gestión de usuarios";
    document.getElementById("botonGuardarUsuario").textContent = "Guardar usuario";
}

function abrirAltaUsuario() {
    limpiarEstadoUsuario();
    dialogUsuario.showModal();
}

function cerrarGestionUsuario() {
    dialogUsuario.close();
    limpiarEstadoUsuario();
}

function obtenerDatosFormularioUsuario() {
    return {
        cedula: campoCedulaUsuario.value.trim(),
        nombre: campoNombreUsuario.value.trim(),
        apellido: campoApellidoUsuario.value.trim(),
        clave: campoClaveUsuario.value,
        confirmarClave: campoConfirmarClaveUsuario.value,
        rol: campoRolUsuario.value
    };
}

async function obtenerUsuario(cedula) {
    return await obtenerDatosApi(API_DATOS + "&cedula=" + encodeURIComponent(cedula));
}

async function abrirModificarUsuario(cedula) {
    try {
        const usuario = await obtenerUsuario(cedula);
        if (usuario === null) return;
        limpiarEstadoUsuario();
        usuarioEnEdicion = true;
        campoClaveUsuario.required = false;
        campoConfirmarClaveUsuario.required = false;
        campoClaveUsuario.placeholder = "Dejar vacía para conservar la contraseña";
        campoConfirmarClaveUsuario.placeholder = "Completar solo si cambia la contraseña";
        campoCedulaUsuario.value = usuario.cedula;
        campoCedulaUsuario.readOnly = true;
        campoNombreUsuario.value = usuario.nombre;
        campoApellidoUsuario.value = usuario.apellido;
        campoRolUsuario.value = usuario.administrador == 1 ? "administrador" : usuario.tecnico == 1 ? "tecnico" : "solicitante";
        document.getElementById("tituloFormularioUsuario").textContent = "Modificar usuario";
        document.getElementById("botonGuardarUsuario").textContent = "Guardar modificación";
        dialogUsuario.showModal();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../index.php?ruta=usuarios";

async function obtenerDatos() {
    return await obtenerDatosApi(API_DATOS);
}

function agregarFila(dato) {
    const fila = document.createElement("tr");
    const rolValor = dato.administrador == 1 ? "administrador" : dato.tecnico == 1 ? "tecnico" : "solicitante";
    const rol = rolValor === "administrador" ? "Administrador" : rolValor === "tecnico" ? "Técnico" : "Solicitante";
    const campoCedula = document.createElement("td");
    campoCedula.textContent = dato.cedula;
    fila.appendChild(campoCedula);

    const campoNombre = document.createElement("td");
    campoNombre.textContent = dato.nombre;
    fila.appendChild(campoNombre);

    const campoApellido = document.createElement("td");
    campoApellido.textContent = dato.apellido;
    fila.appendChild(campoApellido);

    const campoRol = document.createElement("td");
    campoRol.textContent = rol;
    fila.appendChild(campoRol);

    const campoEstado = document.createElement("td");
    campoEstado.textContent = dato.estado == 1 ? "Activo" : "Inactivo";
    fila.appendChild(campoEstado);

    const campoOperaciones = document.getElementById("operacionesUsuario").content.cloneNode(true);
    const botonModificar = campoOperaciones.querySelector("[data-modificar]");
    botonModificar.addEventListener("click", () => abrirModificarUsuario(dato.cedula));
    const botonDesactivar = campoOperaciones.querySelector("[data-desactivar]");
    const botonActivar = campoOperaciones.querySelector("[data-activar]");
    botonDesactivar.hidden = dato.estado != 1;
    botonActivar.hidden = dato.estado == 1;
    botonDesactivar.addEventListener("click", () => cambiarEstadoUsuario(dato.cedula, "0"));
    botonActivar.addEventListener("click", () => cambiarEstadoUsuario(dato.cedula, "1"));
    fila.appendChild(campoOperaciones);

    cuerpoTabla.appendChild(fila);
}

async function actualizarTabla() {
    try {
        const datos = await obtenerDatos();
        cuerpoTabla.replaceChildren();
        for (const dato of datos) {
            agregarFila(dato);
        }
        actualizarControlesTabla('tablaRegistros1');
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}


async function guardarUsuario(usuario) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DATOS, "POST", usuario);
}

async function modificarUsuario(usuario) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DATOS, "PUT", usuario);
}

async function cambiarEstadoUsuario(cedula, estado) {
    try {
        if (!await formularioListo) return;
        const cambiado = estado === "0"
            ? await enviarDatosApi(API_DATOS, "DELETE", {cedula: cedula})
            : await modificarUsuario({cedula: cedula, estado: "1"});
        if (cambiado) await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

async function gestionarUsuario(eventoFormulario) {
    eventoFormulario.preventDefault();
    const usuario = obtenerDatosFormularioUsuario();
    try {
        if (!usuarioEnEdicion) {
            const guardado = await guardarUsuario(usuario);
            if (!guardado) return;
        } else {
            const modificado = await modificarUsuario(usuario);
            if (!modificado) return;
        }
        cerrarGestionUsuario();
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

formularioUsuario.addEventListener("submit", gestionarUsuario);
document.getElementById("btnAltaUsuario").addEventListener("click", abrirAltaUsuario);
document.getElementById("btnCerrarAltaUsuario").addEventListener("click", cerrarGestionUsuario);
dialogUsuario.addEventListener("cancel", limpiarEstadoUsuario);
actualizarTabla();
