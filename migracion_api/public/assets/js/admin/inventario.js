const API_DISPOSITIVOS = "../index.php?ruta=dispositivos";
const cuerpoTabla = document.getElementById("cuerpoTabla");
const dialogDispositivo = document.querySelector(".dialogAltaDispositivo");
const formularioDispositivo = document.getElementById("formularioAltaDispositivo");
const campoNumeroDispositivo = document.getElementById("numeroDispositivo");
const campoLaboratorio = document.getElementById("idLab");
const campoLaboratorioFijo = document.getElementById("idLabFijo");
const campoModificaciones = document.getElementById("modificaciones");
const campoEstado = document.getElementById("estado");
const campoUltimoCambio = document.getElementById("ultimoCambio");
const grupoModificaciones = document.getElementById("grupoModificaciones");
const tituloDispositivo = document.getElementById("tituloFormularioDispositivo");
const botonGuardarDispositivo = document.getElementById("botonGuardarDispositivo");

let dispositivoEnEdicion = false;

function limpiarEstadoDispositivo() {
    dispositivoEnEdicion = false;
    formularioDispositivo.reset();
    campoNumeroDispositivo.readOnly = false;
    campoLaboratorio.disabled = false;
    campoLaboratorioFijo.value = "";
    grupoModificaciones.hidden = true;
    campoModificaciones.required = false;
    tituloDispositivo.textContent = "Gestión de dispositivos";
    botonGuardarDispositivo.textContent = "Guardar dispositivo";
}

function abrirAltaDispositivo() {
    limpiarEstadoDispositivo();
    dialogDispositivo.showModal();
}

function cerrarGestionDispositivo() {
    dialogDispositivo.close();
    limpiarEstadoDispositivo();
}

function obtenerDatosFormularioDispositivo() {
    const dispositivo = {
        idLaboratorio: campoLaboratorio.value,
        numeroDispositivo: campoNumeroDispositivo.value.trim(),
        modificaciones: campoModificaciones.value,
        estado: campoEstado.value,
        ultimoCambio: campoUltimoCambio.value
    };
    return dispositivo;
}

async function obtenerDispositivos() {
    return await obtenerDatosApi(API_DISPOSITIVOS);
}

async function obtenerDispositivo(idLaboratorio, numeroDispositivo) {
    return await obtenerDatosApi(
        API_DISPOSITIVOS + "&idLaboratorio=" + encodeURIComponent(idLaboratorio)
        + "&numeroDispositivo=" + encodeURIComponent(numeroDispositivo)
    );
}

async function guardarDispositivo(dispositivo) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DISPOSITIVOS, "POST", dispositivo);
}

async function modificarDispositivo(dispositivo) {
    if (!await formularioListo) return false;
    return await enviarDatosApi(API_DISPOSITIVOS, "PUT", dispositivo);
}

async function eliminarDispositivo(idLaboratorio, numeroDispositivo) {
    if (!await formularioListo) return false;
    if (!confirm("¿Desea eliminar este dispositivo?")) return;
    try {
        await enviarDatosApi(API_DISPOSITIVOS, 'DELETE', { idLaboratorio, numeroDispositivo });
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

function agregarFila(dispositivo) {
    const fila = document.createElement("tr");
    const campoLaboratorioTabla = document.createElement("td");
    campoLaboratorioTabla.textContent = dispositivo.laboratorio;
    const campoNumero = document.createElement("td");
    campoNumero.textContent = dispositivo.numeroDispositivo;
    const campoModificacionesTabla = document.createElement("td");
    campoModificacionesTabla.textContent = dispositivo.modificaciones;
    const campoEstadoTabla = document.createElement("td");
    campoEstadoTabla.textContent = dispositivo.estado == 1 ? "Activo" : "Inactivo";
    const campoFecha = document.createElement("td");
    campoFecha.textContent = dispositivo.ultimoCambio;
    const campoOperaciones = document.createElement("td");
    const cajaOperaciones = document.createElement("div");
    cajaOperaciones.className = "cajaOperaciones";
    const botonModificar = document.createElement("button");
    botonModificar.type = "button";
    botonModificar.className = "btnOperacion btnModificarDispositivo";
    botonModificar.textContent = "Modificar";
    botonModificar.addEventListener("click", () => abrirModificarDispositivo(
        dispositivo.idLab, dispositivo.numeroDispositivo
    ));
    const botonEliminar = document.createElement("button");
    botonEliminar.type = "button";
    botonEliminar.className = "btnOperacion btnDesactivar";
    botonEliminar.textContent = "Eliminar";
    botonEliminar.addEventListener("click", () => eliminarDispositivo(
        dispositivo.idLab, dispositivo.numeroDispositivo
    ));
    cajaOperaciones.appendChild(botonModificar);
    cajaOperaciones.appendChild(botonEliminar);
    campoOperaciones.appendChild(cajaOperaciones);
    fila.appendChild(campoLaboratorioTabla);
    fila.appendChild(campoNumero);
    fila.appendChild(campoModificacionesTabla);
    fila.appendChild(campoEstadoTabla);
    fila.appendChild(campoFecha);
    fila.appendChild(campoOperaciones);
    cuerpoTabla.appendChild(fila);
}

async function actualizarTabla() {
    try {
        const dispositivos = await obtenerDispositivos();
        cuerpoTabla.replaceChildren();
        for (const dispositivo of dispositivos) {
            agregarFila(dispositivo);
        }
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

async function abrirModificarDispositivo(idLaboratorio, numeroDispositivo) {
    if (!await formularioListo) return;
    try {
        const dispositivo = await obtenerDispositivo(idLaboratorio, numeroDispositivo);
        if (dispositivo === null) return;
        limpiarEstadoDispositivo();
        dispositivoEnEdicion = true;
        campoLaboratorio.value = dispositivo.idLab;
        campoLaboratorio.disabled = true;
        campoLaboratorioFijo.value = dispositivo.idLab;
        campoNumeroDispositivo.value = dispositivo.numeroDispositivo;
        campoNumeroDispositivo.readOnly = true;
        campoModificaciones.value = dispositivo.modificaciones;
        campoEstado.value = dispositivo.estado == 1 ? "1" : "0";
        campoUltimoCambio.value = dispositivo.ultimoCambio.replace(" ", "T").slice(0, 16);
        grupoModificaciones.hidden = false;
        campoModificaciones.required = true;
        tituloDispositivo.textContent = "Modificar dispositivo";
        botonGuardarDispositivo.textContent = "Guardar modificación";
        dialogDispositivo.showModal();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

async function gestionarDispositivo(eventoFormulario) {
    eventoFormulario.preventDefault();
    const dispositivo = obtenerDatosFormularioDispositivo();
    try {
        if (!dispositivoEnEdicion) {
            const guardado = await guardarDispositivo(dispositivo);
            if (!guardado) return;
        } else {
            const modificado = await modificarDispositivo(dispositivo);
            if (!modificado) return;
        }
        cerrarGestionDispositivo();
        await actualizarTabla();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

formularioDispositivo.addEventListener("submit", gestionarDispositivo);
document.getElementById("btnAltaDispositivo").addEventListener("click", abrirAltaDispositivo);
document.getElementById("btnCerrarAltaDispositivo").addEventListener("click", cerrarGestionDispositivo);
dialogDispositivo.addEventListener("cancel", limpiarEstadoDispositivo);
actualizarTabla();
