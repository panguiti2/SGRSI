<?php

$error = $_GET["error"] ?? "";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Inicio de sesión | SGRSI</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/login.css">

</head>

<body id="inicio">

<header class="barraNavegacion">

    <img src="assets/img/imagen_generica.png"
         alt="Logo del SGRSI"
         class="logo">

    <h1>SGRSI</h1>

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
                <a href="index.php" class="btnNavegacion">
                    Inicio
                </a>
            </li>

            <li>
                <a href="sobreNosotros.php" class="btnNavegacion">
                    Sobre nosotros
                </a>
            </li>

            <li>
                <a href="contacto.php" class="btnNavegacion">
                    Contacto
                </a>
            </li>

            <li>
                <a href="login.php" class="btnNavegacion">
                    Ingresar
                </a>
            </li>

        </ul>

    </nav>

</header>

<main>

    <section class="seccionLogin">

        <h2>Ingreso al sistema</h2>

        <?php if ($error !== ""): ?>

            <p class="mensajeError">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form action="procesarLogin.php" method="post">

            <fieldset>

                <legend>Inicio de sesión</legend>

                <div class="cajaEntradaDeDatos">

                    <label for="cedula">Cédula</label>

                    <input
                        type="text"
                        id="cedula"
                        name="cedula"
                        pattern="[1-9][0-9]{7}"
                        maxlength="8"
                        autocomplete="username"
                        required>

                </div>

                <div class="cajaEntradaDeDatos">

                    <label for="clave">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="clave"
                        name="clave"
                        autocomplete="current-password"
                        required>

                </div>

            </fieldset>

            <button type="submit">
                Iniciar sesión
            </button>

        </form>

    </section>

</main>

<a href="#inicio" class="btnSubir">

    <i class="bi bi-caret-up-fill"></i>

</a>

<footer>

    <address>

        <a href="http://instagram.com">
            @SGRSSI
        </a>

        <a href="tel:+59829043586">
            2904 3586
        </a>

        <a href="mailto:asistentesiti@gmail.com">
            asistentesiti@gmail.com
        </a>

    </address>

    <p>© 2026 SGRSI</p>

</footer>

<script src="assets/js/barraNavegacion.js"></script>

</body>

</html>