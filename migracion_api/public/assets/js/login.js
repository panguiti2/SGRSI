const mensajesError = {
    "credenciales": "La cédula o la contraseña son incorrectas.",
    "inactivo": "El usuario se encuentra inactivo.",
    "sin_roles": "El usuario no tiene roles habilitados.",
    "sin_sesion": "Debe iniciar sesión para acceder a esa página.",
    "no_autorizado": "No tiene autorización para acceder a ese panel.",
    "peticion": "La petición de ingreso no es válida."
};
const codigoError = new URLSearchParams(window.location.search).get("error");
const mensajeLogin = document.getElementById("mensajeLogin");
if (mensajesError[codigoError]) {
    mensajeLogin.textContent = mensajesError[codigoError];
    mensajeLogin.hidden = false;
}
