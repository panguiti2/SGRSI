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
    const respuesta = await fetch(API_DATOS + "?cedula=" + encodeURIComponent(cedula));
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return null;
    }
    const contenido = await respuesta.json();
    return contenido.datos;
}

async function abrirModificarUsuario(cedula) {
    try {
        const usuario = await obtenerUsuario(cedula);
        if (usuario === null) return;
        limpiarEstadoUsuario();
        usuarioEnEdicion = true;
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
        alert("No se pudo comunicar con el servidor.");
    }
}

const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../api/usuarios.php";

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

    const campoOperaciones = document.createElement("td");
    const cajaOperaciones = document.createElement("div");
    cajaOperaciones.className = "cajaOperaciones";
    const botonModificar = document.createElement("button");
    botonModificar.type = "button";
    botonModificar.className = "btnOperacion btnModificarUsuario";
    botonModificar.textContent = "Modificar";
    botonModificar.addEventListener("click", () => abrirModificarUsuario(dato.cedula));
    const botonEstado = document.createElement("button");
    botonEstado.type = "button";
    botonEstado.className = dato.estado == 1 ? "btnOperacion btnDesactivar" : "btnOperacion btnActivar";
    botonEstado.textContent = dato.estado == 1 ? "Desactivar" : "Activar";
    botonEstado.addEventListener("click", () => cambiarEstadoUsuario(dato.cedula, dato.estado == 1 ? "0" : "1"));
    cajaOperaciones.appendChild(botonModificar);
    cajaOperaciones.appendChild(botonEstado);
    campoOperaciones.appendChild(cajaOperaciones);
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
    } catch (error) {
        console.error(error);
        alert("No se pudieron cargar los datos. Compruebe la conexión con el servidor.");
    }
}


async function guardarUsuario(usuario) {
    if (!await formularioListo) return false;
    const respuesta = await fetch(API_DATOS, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken
        },
        body: JSON.stringify(usuario)
    });
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return false;
    }
    return true;
}

async function modificarUsuario(usuario) {
    if (!await formularioListo) return false;
    const respuesta = await fetch(API_DATOS, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-Token": window.csrfToken
        },
        body: JSON.stringify(usuario)
    });
    if (!respuesta.ok) {
        const contenido = await respuesta.json();
        alert(contenido.mensaje);
        return false;
    }
    return true;
}

async function cambiarEstadoUsuario(cedula, estado) {
    try {
        const cambiado = await modificarUsuario({cedula: cedula, estado: estado});
        if (cambiado) await actualizarTabla();
    } catch (error) {
        console.error(error);
        alert("No se pudo comunicar con el servidor.");
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
        alert("No se pudo comunicar con el servidor.");
    }
}

formularioUsuario.addEventListener("submit", gestionarUsuario);
document.getElementById("btnAltaUsuario").addEventListener("click", abrirAltaUsuario);
document.getElementById("btnCerrarAltaUsuario").addEventListener("click", cerrarGestionUsuario);
dialogUsuario.addEventListener("cancel", limpiarEstadoUsuario);
actualizarTabla();
