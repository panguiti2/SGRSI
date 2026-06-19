const btnAltaUsuario = document.getElementById("btnAltaUsuario");
const btnCerrarAltaUsuario = document.getElementById("btnCerrarAltaUsuario");
const dialogAltaUsuario = document.querySelector(".dialogAltaUsuario");
const formularioAltaUsuario = document.getElementById("formularioAltaUsuario");
const cuerpoTablaUsuarios = document.getElementById("cuerpoTablaUsuarios");

const campoCedula = document.getElementById("cedula");
const campoNombre = document.getElementById("nombre");
const campoApellido = document.getElementById("apellido");
const campoCargo = document.getElementById("cargo");

let usuarioEnEdicion = false;

function abrirAltaUsuario() {
    dialogAltaUsuario.showModal();
}

function limpiarFormularioAltaUsuario() {
    usuarioEnEdicion = false;
    campoCedula.readOnly = false;
    formularioAltaUsuario.reset();
}

function cerrarAltaUsuario() {
    limpiarFormularioAltaUsuario();
    dialogAltaUsuario.close();
}

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

function cargarUsuariosGuardadosLocal() {
    const usuariosGuardados = localStorage.getItem("usuarios");
    if (usuariosGuardados === null) return [];
    return JSON.parse(usuariosGuardados);
}

function obtenerDatosFormularioUsuario() {
    const cedula = campoCedula.value.trim();
    const nombre = campoNombre.value.trim();
    const apellido = campoApellido.value.trim();
    const cargo = campoCargo.value;

    return { cedula, nombre, apellido, cargo };
}

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

function actualizarTablaUsuarios() {
    cuerpoTablaUsuarios.replaceChildren();
    const usuarios = cargarUsuariosGuardadosLocal();

    for (const usuario of usuarios) {
        agregarFilaUsuario(usuario);
    }
}

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

function actualizarUsuariosLocal(usuarios) {
    localStorage.setItem("usuarios", JSON.stringify(usuarios));
}

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

if (btnAltaUsuario) {
    btnAltaUsuario.addEventListener("click", abrirAltaUsuario);
}

if (btnCerrarAltaUsuario) {
    btnCerrarAltaUsuario.addEventListener("click", cerrarAltaUsuario);
}

if (dialogAltaUsuario) {
    dialogAltaUsuario.addEventListener("cancel", limpiarFormularioAltaUsuario);
}

if (cuerpoTablaUsuarios) {
    actualizarTablaUsuarios();
}

