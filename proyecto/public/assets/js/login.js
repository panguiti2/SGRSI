const formularioLogin = document.getElementById("formLogin");
const mensajeLogin = document.getElementById("mensajeLogin");
const mensajesLogin = {
    sin_sesion: "Debe iniciar sesión para acceder a esa página.",
    no_autorizado: "No tiene autorización para acceder a ese panel."
};
const errorLogin = new URLSearchParams(window.location.search).get("error");
if (mensajesLogin[errorLogin]) {
    mensajeLogin.textContent = mensajesLogin[errorLogin];
    mensajeLogin.hidden = false;
}
formularioLogin.addEventListener("submit", async (evento) => {
    evento.preventDefault();
    const boton = formularioLogin.querySelector('button[type="submit"]');
    boton.disabled = true;
    try {
        const respuesta = await peticionApi("index.php?ruta=login", {
            method: "POST", headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                cedula: document.getElementById("cedula").value.trim(),
                clave: document.getElementById("clave").value
            })
        });
        const datos = respuesta.data;
        if (!datos.csrfToken || !datos.roles.length) throw new Error("La API no devolvió una sesión válida.");
        sessionStorage.setItem("csrfToken", datos.csrfToken);
        const carpetas = { administrador: "admin", tecnico: "tecnico", solicitante: "solicitante" };
        window.location.replace(carpetas[datos.roles[0]] + "/inicio.html");
    } catch (error) {
        mensajeLogin.textContent = error.message;
        mensajeLogin.hidden = false;
    } finally {
        boton.disabled = false;
    }
});
