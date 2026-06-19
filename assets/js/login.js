/**
 * Controla el acceso simulado al sistema según el primer dígito de la cédula.
 * @file
 */

/**
 * Formulario de ingreso al sistema.
 * @type {HTMLFormElement|null}
 */

const formularioLogin = document.getElementById("formLogin");

/**
 * Campo donde se ingresa la cédula.
 * @type {HTMLInputElement|null}
 */

const entradaCedula = document.getElementById("cedula");

/**
 * Procesa el ingreso y redirige a la pantalla correspondiente al rol.
 * @param {SubmitEvent} evento Evento de envío del formulario.
 * @returns {void}
 */

formularioLogin.addEventListener("submit", function (evento) {
    evento.preventDefault();

    const cedula = entradaCedula.value.trim();
    const primerDigito = cedula.charAt(0);

    switch (primerDigito) {
        case "1":
            window.location.href = "pages/admin/admin.html";
            break;

        case "2":
            window.location.href = "pages/tecnico/tecnico.html";
            break;

        case "3":
            window.location.href = "pages/user/user.html";
            break;
    }
});
