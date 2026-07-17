<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Administrador | SGRSI</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/globalSistema.css">
    <link rel="stylesheet" href="assets/css/formularios.css">

</head>

<body id="inicio">

<header class="barraNavegacion">

    <img src="assets/img/imagen_generica.png"
         alt="Logo del SGRSI"
         class="logo">

    <h1>SGRSSI</h1>

    <nav>

        <button class="btnMenu" id="btnMenu" type="button">
            <img src="assets/img/list.svg"
                 alt="Abrir menú"
                 class="iconoMenu">
        </button>

        <button class="btnCerrarMenu" id="btnCerrarMenu" type="button">
            <img src="assets/img/x.svg"
                 alt="Cerrar menú"
                 class="iconoMenu">
        </button>

        <ul class="listaNavegacion">

            <li>

                <a href="cerrarSesion.php"
                   class="btnNavegacion">

                    Cerrar sesión

                </a>

            </li>

        </ul>

    </nav>

</header>

<main>

    <section class="seccionTablaEmpleados">

        <header class="cajaEncabezado">

            <h2>
                Gestión de usuarios
            </h2>

            <button
                type="button"
                class="btnOperacion"
                id="btnAltaEmpleado">

                Registrar usuario

            </button>

        </header>

        <table>

            <caption>

                Usuarios registrados

            </caption>

            <thead>

                <tr>

                    <th>Cédula</th>

                    <th>Nombre</th>

                    <th>Apellido</th>

                    <th>Rol</th>

                    <th>Acciones</th>

                </tr>

            </thead>

            <tbody id="cuerpoTablaEmpleados">

            </tbody>

        </table>

    </section>

    <dialog
        id="dialogGestionarEmpleado"
        class="dialogGestionarEmpleado seccionFormulario">

        <button
            class="btnCerrarGestionarEmpleado"
            id="btnCerrarGestionarEmpleado"
            type="button">

            <img src="assets/img/x.svg"
                 alt="Cerrar"
                 class="iconoMenu">

        </button>

        <form
            action="administrador.php"
            method="post"
            id="formularioGestionarEmpleado">

            <fieldset>

                <legend>

                    Gestión de usuario

                </legend>

                <fieldset>

                    <legend>

                        Datos del usuario

                    </legend>

                    <div class="cajaEntradaDeDatos">

                        <label for="cedula">

                            Cédula

                        </label>

                        <input
                            type="text"
                            id="cedula"
                            name="cedula"
                            maxlength="8"
                            required>

                    </div>

                    <div class="cajaEntradaDeDatos">

                        <label for="nombre">

                            Nombre

                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            required>

                    </div>

                    <div class="cajaEntradaDeDatos">

                        <label for="apellido">

                            Apellido

                        </label>

                        <input
                            type="text"
                            id="apellido"
                            name="apellido"
                            required>

                    </div>

                    <div class="cajaEntradaDeDatos">

                        <label for="rol">

                            Rol

                        </label>

                        <select
                            name="rol"
                            id="rol"
                            required>

                            <option value="">
                                Seleccione
                            </option>

                            <option value="administrador">
                                Administrador
                            </option>

                            <option value="tecnico">
                                Técnico
                            </option>

                            <option value="solicitante">
                                Solicitante
                            </option>

                        </select>

                    </div>

                </fieldset>

                <button type="submit">

                    Guardar usuario

                </button>

            </fieldset>

        </form>

    </dialog>

</main>

<a href="#inicio" class="btnSubir">

    <i class="bi bi-caret-up-fill"></i>

</a>

<footer>

    <p>

        Sistema de Gestión de Recursos y Soporte Informático

    </p>

    <p>

        © 2026 SGRSI

    </p>

</footer>

<script src="assets/js/barraNavegacion.js"></script>

<script src="assets/js/gestionUsuarios.js"></script>

</body>

</html>