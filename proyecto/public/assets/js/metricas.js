const API_METRICAS = "../index.php?ruta=metricas";

async function obtenerMetricas() {
    return await obtenerDatosApi(API_METRICAS);
}

async function actualizarMetricas() {
    try {
        const metricas = await obtenerMetricas();

        if (metricas === null) {
            return;
        }

        const campoUsuariosActivos = document.getElementById("usuariosActivos");
        if (campoUsuariosActivos) {
            campoUsuariosActivos.textContent = metricas.usuariosActivos;
        }
        document.getElementById("ticketsAbiertos").textContent = metricas.ticketsAbiertos;
        document.getElementById("incidenciasAbiertas").textContent = metricas.incidenciasAbiertas;
        document.getElementById("solicitudesAbiertas").textContent = metricas.solicitudesAbiertas;
        document.getElementById("prestamosPendientes").textContent = metricas.prestamosPendientes;
        document.getElementById("dispositivosActivos").textContent = metricas.dispositivosActivos;
        document.getElementById("registrosUso").textContent = metricas.registrosUso;
        const tabla = document.getElementById("metricasLaboratorios");
        if (tabla) {
            tabla.replaceChildren();
            for (const laboratorio of metricas.porLaboratorio) {
                const fila = document.createElement("tr");
                for (const campo of ["nombre", "dispositivosActivos", "incidenciasAbiertas", "solicitudesAbiertas", "registrosUso"]) {
                    const celda = document.createElement("td");
                    celda.textContent = laboratorio[campo];
                    fila.appendChild(celda);
                }
                tabla.appendChild(fila);
            }
        }
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
    }
}

actualizarMetricas();
