const API_METRICAS = "../api/metricas.php";

async function obtenerMetricas() {
    const respuesta = await fetch(API_METRICAS);

    if (!respuesta.ok) {
        console.error("No se pudieron obtener las métricas");
        return null;
    }

    const contenido = await respuesta.json();

    return contenido.datos;
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
    } catch (error) {
        console.error(error);
        alert("No se pudieron cargar los datos. Compruebe la conexión con el servidor.");
    }
}

actualizarMetricas();
