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
const verDispositivosBaja = document.getElementById('verDispositivosBaja');

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
    return await obtenerDatosApi(API_DISPOSITIVOS + '&retirados=' + (verDispositivosBaja.checked ? '1' : '0'));
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
    if (!confirm("¿Desea dar de baja este dispositivo? Sus tickets e historial se conservarán y podrá restaurarlo después.")) return;
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
    campoEstadoTabla.textContent = verDispositivosBaja.checked ? 'Dado de baja' : dispositivo.estado == 1 ? "Activo" : "Inactivo";
    const campoFecha = document.createElement("td");
    campoFecha.textContent = dispositivo.ultimoCambio;
    const campoOperaciones = document.getElementById('operacionesDispositivo').content.cloneNode(true);
    const botonModificar = campoOperaciones.querySelector('[data-modificar]');
    botonModificar.addEventListener("click", () => abrirModificarDispositivo(
        dispositivo.idLab, dispositivo.numeroDispositivo
    ));
    const botonEliminar = campoOperaciones.querySelector('[data-baja]');
    botonEliminar.addEventListener("click", () => eliminarDispositivo(
        dispositivo.idLab, dispositivo.numeroDispositivo
    ));
    const botonRestaurar = campoOperaciones.querySelector('[data-restaurar]');
    botonModificar.hidden = verDispositivosBaja.checked;
    botonEliminar.hidden = verDispositivosBaja.checked;
    botonRestaurar.hidden = !verDispositivosBaja.checked;
    botonRestaurar.addEventListener('click', () => restaurarDispositivo(dispositivo.idLab, dispositivo.numeroDispositivo));
    fila.appendChild(campoLaboratorioTabla);
    fila.appendChild(campoNumero);
    fila.appendChild(campoModificacionesTabla);
    fila.appendChild(campoEstadoTabla);
    fila.appendChild(campoFecha);
    fila.appendChild(campoOperaciones);
    cuerpoTabla.appendChild(fila);
}

async function actualizarTabla() {
    verDispositivosBaja.disabled = true;
    document.getElementById('tituloInventario').textContent = verDispositivosBaja.checked ? 'Dispositivos dados de baja' : 'Datos de dispositivo';
    document.getElementById('ayudaRestauracion').hidden = !verDispositivosBaja.checked;
    try {
        const dispositivos = await obtenerDispositivos();
        cuerpoTabla.replaceChildren();
        for (const dispositivo of dispositivos) {
            agregarFila(dispositivo);
        }
        actualizarControlesTabla('tablaRegistros1');
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    } finally {
        verDispositivosBaja.disabled = false;
    }
}

async function restaurarDispositivo(idLaboratorio, numeroDispositivo) {
    if (!await formularioListo) return;
    if (!confirm('¿Restaurar este dispositivo como inactivo? Se conservarán sus datos y su historial.')) return;
    try {
        await enviarDatosApi(API_DISPOSITIVOS, 'PUT', {accion:'restaurar', idLaboratorio, numeroDispositivo});
        verDispositivosBaja.checked = false;
        await actualizarTabla();
        alert('Dispositivo restaurado como inactivo. Revíselo y actívelo desde Modificar para permitir nuevos tickets.');
    } catch (error) { mostrarErrorApi(error); }
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
verDispositivosBaja.addEventListener('change', function () {
    document.querySelector('form[data-tabla="tablaRegistros1"]').reset();
    actualizarTabla();
});
actualizarTabla();
