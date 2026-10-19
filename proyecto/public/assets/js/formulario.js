window.csrfToken = "";
window.dispositivosFormulario = [];

function cargarOpciones(id, opciones, clave) {
    const selector = document.getElementById(id);
    if (!selector || !opciones) return;
    if (selector.options.length === 0) {
        const inicial = document.createElement("option");
        inicial.value = "";
        inicial.disabled = true;
        inicial.selected = true;
        inicial.textContent = "Seleccione";
        selector.appendChild(inicial);
    }
    for (const dato of opciones) {
        const opcion = document.createElement("option");
        opcion.value = dato[clave];
        opcion.textContent = dato.nombre;
        selector.appendChild(opcion);
    }
}

async function cargarFormulario() {
    const rol = document.body.dataset.rol;
    const seccion = document.body.dataset.seccion;
    try {
        const datos = await obtenerDatosApi("../index.php?ruta=formulario&rol=" + encodeURIComponent(rol)
            + "&seccion=" + encodeURIComponent(seccion));
        window.csrfToken = datos.csrfToken;
        sessionStorage.setItem("csrfToken", datos.csrfToken);
        window.dispositivosFormulario = datos.dispositivosFormulario || [];
        cargarOpciones("idLaboratorio", datos.laboratorios, "idLaboratorio");
        cargarOpciones("idLab", datos.laboratorios, "idLaboratorio");
        cargarOpciones("turno", datos.turnos, "codigo");
        cargarOpciones("tipoServicio", datos.tiposServicio, "codigo");
        cargarOpciones("modificaciones", datos.modificaciones, "codigo");
        cargarOpciones("estadoSolicitud", datos.estadosTicket, "codigo");
        cargarOpciones("estadoIncidencia", datos.estadosTicket, "codigo");
        for (const campo of document.querySelectorAll('input[name="csrfToken"]')) {
            campo.value = datos.csrfToken;
        }
        return true;
    } catch (error) {
        console.error(error);
        mostrarErrorApi(error);
        return false;
    }
}

const formularioListo = cargarFormulario();
for (const enlace of document.querySelectorAll('[data-cerrar-sesion]')) {
    enlace.addEventListener('click', async (evento) => {
        evento.preventDefault();
        try {
            if (!await formularioListo) return;
            await peticionApi('../index.php?ruta=logout', {
                method: 'POST', headers: { 'X-CSRF-Token': window.csrfToken }
            });
            sessionStorage.removeItem('csrfToken');
            window.location.replace('../index.html');
        } catch (error) {
            mostrarErrorApi(error);
        }
    });
}
