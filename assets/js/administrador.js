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

const btnAltaDispositivo = document.getElementById("btnAltaDispositivo");
const btnCerrarAltaDispositivo = document.getElementById("btnCerrarAltaDispositivo");
const dialogAltaDispositivo = document.querySelector(".dialogAltaDispositivo");
const formularioAltaDispositivo = document.getElementById("formularioAltaDispositivo");
const cuerpoTablaDispositivos = document.getElementById("cuerpoTablaDispositivos");

const campoId = document.getElementById("id");
const campoMarca = document.getElementById("marca");
const campoNumero = document.getElementById("numeroDispositivo");
const campoRecurso = document.getElementById("recurso");
const campoLaboratorio = document.getElementById("laboratorio");
const campoTaller = document.getElementById("taller");
const campoModificaciones = document.getElementById("modificaciones");
const campoEstado = document.getElementById("estado");
const campoUltimoCambio = document.getElementById("ultimoCambio");

let dispositivoEnEdicion = false;

function abrirAltaDispositivo() {
    dialogAltaDispositivo.showModal();
}

function cerrarAltaDispositivo() {
    limpiarFormularioAltaDispositivo();
    dialogAltaDispositivo.close();
}

function limpiarFormularioAltaDispositivo() {
    formularioAltaDispositivo.reset();
    campoId.readOnly = false;
    campoLaboratorio.disabled = false;
    campoTaller.disabled = false;
    campoNumero.readOnly = false;
    campoRecurso.disabled = false;
    dispositivoEnEdicion = false;
}

function abrirModificarDispositivo(id) {
    const dispositivos = cargarDispositivosLocal();
    const dispositivo = dispositivos.find(d => d.id === id);

    if (!dispositivo) {
        alert("No se encontró un dispositivo con ese ID");
        return;
    }

    campoId.value = dispositivo.id;
    campoMarca.value = dispositivo.marca;
    campoNumero.value = dispositivo.numeroDispositivo;
    campoLaboratorio.value = dispositivo.laboratorio;
    campoTaller.value = dispositivo.taller;
    campoRecurso.value = dispositivo.recurso;
    campoModificaciones.value = dispositivo.modificaciones;
    campoEstado.value = dispositivo.estado;
    campoUltimoCambio.value = dispositivo.ultimoCambio;

    campoId.readOnly = true;
    campoLaboratorio.disabled = true;
    campoTaller.disabled = true;
    campoNumero.readOnly = true;
    campoRecurso.disabled = true;

    dispositivoEnEdicion = true;
    dialogAltaDispositivo.showModal();
}

function obtenerDatosFormularioDispositivo() {
    return {
        id: campoId.value.trim(),
        marca: campoMarca.value.trim(),
        numeroDispositivo: campoNumero.value.trim(),
        laboratorio: campoLaboratorio.value,
        taller: campoTaller.value,
        recurso: campoRecurso.value,
        modificaciones: campoModificaciones.value,
        estado: campoEstado.value,
        ultimoCambio: campoUltimoCambio.value
    };
}

function cargarDispositivosLocal() {
    const data = localStorage.getItem("dispositivos");
    return data ? JSON.parse(data) : [];
}

function actualizarDispositivosLocal(dispositivos) {
    localStorage.setItem("dispositivos", JSON.stringify(dispositivos));
}

function guardarDispositivoLocal(dispositivo) {
    const dispositivos = cargarDispositivosLocal();
    const idExistente = dispositivos.some(dispositivoGuardado => {
        return dispositivoGuardado.id === dispositivo.id;
    });

    if (idExistente) {
        alert("Ya existe un dispositivo con ese id");
        return;
    }

    dispositivos.push(dispositivo);
    actualizarDispositivosLocal(dispositivos);
}

function modificarDispositivoLocal(dispositivoFormulario) {
    const dispositivos = cargarDispositivosLocal();
    const dispositivo = dispositivos.find(dispositivo => {
        return dispositivo.id === dispositivoFormulario.id;
    });

    if (!dispositivo) {
        alert("No se encontró un dispositivo con ese ID");
        return;
    }

    dispositivo.id = dispositivoFormulario.id;
    dispositivo.marca = dispositivoFormulario.marca;
    dispositivo.numeroDispositivo = dispositivoFormulario.numeroDispositivo;
    dispositivo.laboratorio = dispositivoFormulario.laboratorio;
    dispositivo.taller = dispositivoFormulario.taller;
    dispositivo.recurso = dispositivoFormulario.recurso;
    dispositivo.modificaciones = dispositivoFormulario.modificaciones;
    dispositivo.estado = dispositivoFormulario.estado;
    dispositivo.ultimoCambio = dispositivoFormulario.ultimoCambio;

    actualizarDispositivosLocal(dispositivos);
}

function eliminarDispositivoLocal(id) {
    const dispositivos = cargarDispositivosLocal();

    if (confirm("¿Estás seguro de que deseas eliminar este dispositivo?")) {
        const nuevos = dispositivos.filter(dispositivo => {
            return dispositivo.id !== id;
        });

        actualizarDispositivosLocal(nuevos);
        actualizarTablaDispositivos();
        console.log("Dispositivo eliminado");
    } else {
        console.log("Acción cancelada");
    }
}

function crearCampoOperacionesDispositivo(dispositivo) {
    const contenedor = document.createElement("div");
    contenedor.classList.add("cajaOperaciones");

    const btnModificar = document.createElement("button");
    btnModificar.textContent = "Modificar";
    btnModificar.classList.add("botonOperacion");
    btnModificar.addEventListener("click", () => {
        abrirModificarDispositivo(dispositivo.id);
    });

    const btnEliminar = document.createElement("button");
    btnEliminar.textContent = "Eliminar";
    btnEliminar.classList.add("botonOperacion");
    btnEliminar.addEventListener("click", () => {
        eliminarDispositivoLocal(dispositivo.id);
    });

    contenedor.appendChild(btnModificar);
    contenedor.appendChild(btnEliminar);

    return contenedor;
}

function agregarFilaDispositivo(dispositivo) {
    const fila = document.createElement("tr");

    const campoId = document.createElement("td");
    campoId.textContent = dispositivo.id;

    const campoMarca = document.createElement("td");
    campoMarca.textContent = dispositivo.marca;

    const campoNumDispositivo = document.createElement("td");
    campoNumDispositivo.textContent = dispositivo.numeroDispositivo;

    const campoLaboratorio = document.createElement("td");
    campoLaboratorio.textContent = dispositivo.laboratorio;

    const campoTaller = document.createElement("td");
    campoTaller.textContent = dispositivo.taller;

    const campoRecurso = document.createElement("td");
    campoRecurso.textContent = dispositivo.recurso;

    const campoModificaciones = document.createElement("td");
    campoModificaciones.textContent = dispositivo.modificaciones;

    const campoEstado = document.createElement("td");
    campoEstado.textContent = dispositivo.estado;

    const campoUltimoCambio = document.createElement("td");
    campoUltimoCambio.textContent = dispositivo.ultimoCambio;

    const campoOperaciones = crearCampoOperacionesDispositivo(dispositivo);

    fila.appendChild(campoId);
    fila.appendChild(campoMarca);
    fila.appendChild(campoNumDispositivo);
    fila.appendChild(campoLaboratorio);
    fila.appendChild(campoTaller);
    fila.appendChild(campoRecurso);
    fila.appendChild(campoModificaciones);
    fila.appendChild(campoEstado);
    fila.appendChild(campoUltimoCambio);
    fila.appendChild(campoOperaciones);

    cuerpoTablaDispositivos.appendChild(fila);
}

function actualizarTablaDispositivos() {
    cuerpoTablaDispositivos.replaceChildren();
    const dispositivos = cargarDispositivosLocal();

    for (const dispositivo of dispositivos) {
        agregarFilaDispositivo(dispositivo);
    }
}

function gestionarDispositivo(eventoFormulario) {
    eventoFormulario.preventDefault();
    const dispositivo = obtenerDatosFormularioDispositivo();

    if (!dispositivoEnEdicion) {
        guardarDispositivoLocal(dispositivo);
    } else {
        modificarDispositivoLocal(dispositivo);
    }

    cerrarAltaDispositivo();
    actualizarTablaDispositivos();
}

if (formularioAltaDispositivo) {
    formularioAltaDispositivo.addEventListener("submit", gestionarDispositivo);
}

if (btnAltaDispositivo) {
    btnAltaDispositivo.addEventListener("click", abrirAltaDispositivo);
}

if (btnCerrarAltaDispositivo) {
    btnCerrarAltaDispositivo.addEventListener("click", cerrarAltaDispositivo);
}

if (dialogAltaDispositivo) {
    dialogAltaDispositivo.addEventListener("cancel", limpiarFormularioAltaDispositivo);
}

if (cuerpoTablaDispositivos) {
    actualizarTablaDispositivos();
}
