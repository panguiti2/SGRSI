/**
 * Maneja los diálogos de usuarios y dispositivos del administrador.
 * La carga y la persistencia de datos se realizan en PHP mediante PDO.
 * @file
 */

const dialogUsuario = document.querySelector(".dialogAltaUsuario");
const formularioUsuario = document.getElementById("formularioAltaUsuario");
const botonAltaUsuario = document.getElementById("btnAltaUsuario");
const botonCerrarUsuario = document.getElementById("btnCerrarAltaUsuario");
const campoCedulaUsuario = document.getElementById("cedula");
const campoNombreUsuario = document.getElementById("nombre");
const campoApellidoUsuario = document.getElementById("apellido");
const campoRolUsuario = document.getElementById("rol");
const tituloUsuario = document.getElementById("tituloFormularioUsuario");
const botonGuardarUsuario = document.getElementById("botonGuardarUsuario");

function prepararAltaUsuario() {
    if (!formularioUsuario) return;

    formularioUsuario.reset();
    formularioUsuario.action = "procesarAltaUsuario.php";
    campoCedulaUsuario.readOnly = false;
    tituloUsuario.textContent = "Gestión de usuarios";
    botonGuardarUsuario.textContent = "Guardar usuario";
}

if (botonAltaUsuario && dialogUsuario) {
    botonAltaUsuario.addEventListener("click", () => {
        prepararAltaUsuario();
        dialogUsuario.showModal();
    });
}

document.querySelectorAll(".btnModificarUsuario").forEach((boton) => {
    boton.addEventListener("click", () => {
        formularioUsuario.reset();
        formularioUsuario.action = "procesarModificarUsuario.php";
        campoCedulaUsuario.value = boton.dataset.cedula;
        campoNombreUsuario.value = boton.dataset.nombre;
        campoApellidoUsuario.value = boton.dataset.apellido;
        campoRolUsuario.value = boton.dataset.rol;
        campoCedulaUsuario.readOnly = true;
        tituloUsuario.textContent = "Modificar usuario";
        botonGuardarUsuario.textContent = "Guardar modificación";
        dialogUsuario.showModal();
    });
});

if (botonCerrarUsuario) {
    botonCerrarUsuario.addEventListener("click", () => dialogUsuario.close());
}

const dialogDispositivo = document.querySelector(".dialogAltaDispositivo");
const formularioDispositivo = document.getElementById("formularioAltaDispositivo");
const botonAltaDispositivo = document.getElementById("btnAltaDispositivo");
const botonCerrarDispositivo = document.getElementById("btnCerrarAltaDispositivo");
const campoNumeroDispositivo = document.getElementById("numeroDispositivo");
const campoLaboratorio = document.getElementById("idLab");
const campoLaboratorioFijo = document.getElementById("idLabFijo");
const campoModificaciones = document.getElementById("modificaciones");
const campoEstado = document.getElementById("estado");
const campoUltimoCambio = document.getElementById("ultimoCambio");
const grupoModificaciones = document.getElementById("grupoModificaciones");
const tituloDispositivo = document.getElementById("tituloFormularioDispositivo");
const botonGuardarDispositivo = document.getElementById("botonGuardarDispositivo");

function prepararAltaDispositivo() {
    if (!formularioDispositivo) return;

    formularioDispositivo.reset();
    formularioDispositivo.action = "procesarAltaDispositivo.php";
    campoNumeroDispositivo.readOnly = false;
    campoLaboratorio.disabled = false;
    campoLaboratorioFijo.value = "";
    grupoModificaciones.hidden = true;
    tituloDispositivo.textContent = "Gestión de dispositivos";
    botonGuardarDispositivo.textContent = "Guardar dispositivo";
}

if (botonAltaDispositivo && dialogDispositivo) {
    botonAltaDispositivo.addEventListener("click", () => {
        prepararAltaDispositivo();
        dialogDispositivo.showModal();
    });
}

document.querySelectorAll(".btnModificarDispositivo").forEach((boton) => {
    boton.addEventListener("click", () => {
        formularioDispositivo.reset();
        formularioDispositivo.action = "procesarModificarDispositivo.php";
        campoLaboratorio.value = boton.dataset.laboratorio;
        campoLaboratorio.disabled = true;
        campoLaboratorioFijo.value = boton.dataset.laboratorio;
        campoNumeroDispositivo.value = boton.dataset.numero;
        campoNumeroDispositivo.readOnly = true;
        campoModificaciones.value = boton.dataset.modificaciones;
        campoEstado.value = boton.dataset.estado;
        campoUltimoCambio.value = boton.dataset.ultimoCambio.replace(" ", "T").slice(0, 16);
        grupoModificaciones.hidden = false;
        tituloDispositivo.textContent = "Modificar dispositivo";
        botonGuardarDispositivo.textContent = "Guardar modificación";
        dialogDispositivo.showModal();
    });
});

if (botonCerrarDispositivo) {
    botonCerrarDispositivo.addEventListener("click", () => dialogDispositivo.close());
}
