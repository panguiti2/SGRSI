const formularioLogin = document.getElementById("formLogin");
const entradaCedula = document.getElementById("cedula");

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

