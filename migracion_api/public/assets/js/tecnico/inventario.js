const cuerpoTabla = document.getElementById("cuerpoTabla");
const API_DATOS = "../index.php?ruta=dispositivos";

async function obtenerDatos() {
    return await obtenerDatosApi(API_DATOS);
}

function agregarFila(dato) {
    const fila = document.createElement("tr");
    const campoNumeroDispositivo = document.createElement("td");
    campoNumeroDispositivo.textContent = dato.numeroDispositivo;
    fila.appendChild(campoNumeroDispositivo);

    const campoLaboratorio = document.createElement("td");
    campoLaboratorio.textContent = dato.laboratorio;
    fila.appendChild(campoLaboratorio);

    const campoModificaciones = document.createElement("td");
    campoModificaciones.textContent = dato.modificaciones;
    fila.appendChild(campoModificaciones);

    const campoEstado = document.createElement("td");
    campoEstado.textContent = dato.estado == 1 ? "Activo" : "Inactivo";
    fila.appendChild(campoEstado);

    const campoUltimoCambio = document.createElement("td");
    campoUltimoCambio.textContent = dato.ultimoCambio;
    fila.appendChild(campoUltimoCambio);

    cuerpoTabla.appendChild(fila);
}

async function actualizarTabla() {
    try {
        const datos = await obtenerDatos();
        cuerpoTabla.replaceChildren();
        for (const dato of datos) {
            agregarFila(dato);
        }
        if (typeof configurarOperaciones === "function") configurarOperaciones();
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

actualizarTabla();
