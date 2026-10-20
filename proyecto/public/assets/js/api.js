
async function peticionApi(url, opciones = {}) {
    const metodo = opciones.method || "GET";
    try {
        const respuesta = await fetch(url, { credentials: "same-origin", ...opciones,
            headers: { Accept: 'application/json', ...opciones.headers } });
        const texto = await respuesta.text();
        if (!texto.trim()) throw new Error(`La API respondió sin cuerpo (HTTP ${respuesta.status}).`);
        const tipo = (respuesta.headers.get('Content-Type') || '').split(';')[0].trim().toLowerCase();
        if (tipo !== 'application/json') {
            throw new Error(`HTTP ${respuesta.status}: La API no devolvió JSON con Content-Type application/json.`);
        }
        let contenido;
        try {
            contenido = JSON.parse(texto);
        } catch {
            throw new Error(`HTTP ${respuesta.status}: La API no devolvió JSON.`);
        }
        if (contenido === null || typeof contenido !== 'object' || Array.isArray(contenido)) {
            throw new Error('La API no devolvió los datos esperados.');
        }
        if (contenido.status !== respuesta.status || typeof contenido.message !== 'string'
            || !Object.prototype.hasOwnProperty.call(contenido, "data")
            || (!respuesta.ok && contenido.data !== null)) {
            throw new Error("La API no devolvió los datos esperados.");
        }
        if (!respuesta.ok) {
            const error = new Error(contenido.message);
            error.estado = respuesta.status;
            throw error;
        }
        return contenido;
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
    return respuesta.data;
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
