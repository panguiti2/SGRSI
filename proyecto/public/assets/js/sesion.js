// Eventos de cierre de sesión. La autenticación se valida en el servidor.
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
