/** Consulta de historial mediante el transporte común de la API. */
const botonHistorialDispositivos = document.getElementById('btnHistorialDispositivos');
if (botonHistorialDispositivos) {
    const dialogo = document.getElementById('dialogoHistorialDispositivos');
    const cerrar = document.getElementById('cerrarHistorialDispositivos');
    const estadoCarga = document.getElementById('estadoHistorialDispositivos');
    const cuerpo = document.getElementById('filasHistorialDispositivos');
    cerrar.addEventListener('click', () => dialogo.close());
    const nombreEstado = estado => estado === null ? 'No corresponde' : Number(estado) === 1 ? 'Activo' : 'Inactivo';
    botonHistorialDispositivos.addEventListener('click', async () => {
        if (!await formularioListo) return;
        cuerpo.replaceChildren();
        actualizarControlesTabla('tablaHistorialDispositivos');
        estadoCarga.textContent = 'Cargando historial…';
        dialogo.showModal();
        try {
            const datos = await obtenerDatosApi('../index.php?ruta=historialDispositivos');
            for (const dato of datos) {
                const fila = cuerpo.insertRow();
                for (const valor of [dato.fecha, dato.idLaboratorio, dato.numeroDispositivo,
                    dato.operacion === 'RESTAURACION' ? 'Restauración anterior' : dato.operacion === 'BAJA' ? 'Baja del inventario' : dato.operacion === 'ALTA' ? 'Alta' : 'Modificación',
                    dato.cedulaActor || 'Sin cuenta registrada', dato.modificacionAnterior ?? 'No corresponde',
                    dato.modificacionNueva ?? 'No corresponde', nombreEstado(dato.estadoAnterior), nombreEstado(dato.estadoNuevo),
                    dato.fechaAnterior ?? 'No corresponde', dato.fechaNueva ?? 'No corresponde']) {
                    fila.insertCell().textContent = valor;
                }
            }
            actualizarControlesTabla('tablaHistorialDispositivos');
            estadoCarga.textContent = datos.length ? '' : 'Todavía no hay cambios registrados.';
        } catch (error) {
            estadoCarga.textContent = error.message || 'No se pudo cargar el historial';
            console.error(error);
        }
    });
}
