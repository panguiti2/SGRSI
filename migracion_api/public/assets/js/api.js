/** Transporte común, basado en fetch y en la lectura de respuestas del profesor. */
async function peticionApi(url, opciones = {}) {
    const metodo = opciones.method || "GET";
    try {
        const respuesta = await fetch(url, { credentials: "same-origin", ...opciones });
        const texto = await respuesta.clone().text();
        if (!texto.trim()) throw new Error(`La API respondió sin cuerpo (HTTP ${respuesta.status}).`);
        let contenido;
        try {
            contenido = JSON.parse(texto);
        } catch {
            throw new Error(`HTTP ${respuesta.status}: La API no devolvió JSON.`);
        }
        if (!respuesta.ok) {
            const error = new Error(contenido?.mensaje || 'La solicitud no se pudo completar.');
            error.estado = respuesta.status;
            throw error;
        }
        if (contenido === null || typeof contenido !== 'object') {
            throw new Error('La API no devolvió los datos esperados.');
        }
        if (!Object.prototype.hasOwnProperty.call(contenido, "datos")) {
            throw new Error("La API no devolvió los datos esperados.");
        }
        return respuesta;
    } catch (error) {
        error.metodo = metodo;
        console.error("API", metodo, url, error.estado || "red/formato", error.message);
        if (error.estado === 401 && document.body.dataset.rol) {
            window.location.replace("../index.html?error=sin_sesion");
        }
        if (error.estado === 403 && metodo === "GET" && document.body.dataset.rol) {
            window.location.replace("../index.html?error=no_autorizado");
        }
        throw error;
    }
}

async function obtenerDatosApi(url, opciones = {}) {
    const respuesta = await peticionApi(url, opciones);
    return (await respuesta.json()).datos;
}

async function enviarDatosApi(url, metodo, datos) {
    await peticionApi(url, {
        method: metodo,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': window.csrfToken },
        body: JSON.stringify(datos)
    });
    return true;
}

function mostrarErrorApi(error) {
    if (document.body.dataset.rol && (error.estado === 401 || (error.estado === 403 && error.metodo === 'GET'))) return;
    alert(error.message || 'No se pudo completar la operación');
}
