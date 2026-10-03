<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metricas y Reportes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/global.css">
   
</head>

<body class="d-flex flex-column min-vh-100 sgrsi-app" id="inicio">

    <header class="navbar navbar-expand-md navbar-dark sgrsi-navbar sticky-top">
        <section class="container-fluid">
            <a class="navbar-brand fw-bold" href="inicio.php"><img src="../assets/img/logoITI.png" alt="Logo ITI" class="sgrsi-navbar-logo">SGRSI</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <nav class="collapse navbar-collapse" id="menuPrincipal">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="inicio.php">Inicio</a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link" href="usuarios.php">Usuarios</a>
                        </li>

                    <li class="nav-item">
                        <a class="nav-link" href="incidencias.php">Incidencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="metricas.php">Métricas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inventario.php">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../cerrarSesion.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </nav>
        </section>
    </header>

    <main class="flex-grow-1 p-2 p-md-3 p-lg-4">
        <section class="container-fluid">
            <h1 class="h3 text-center text-md-start mb-2">Métricas del sistema</h1>
            <p class="text-secondary mb-4">Resumen actual de la información registrada.</p>

            <section class="row g-3" aria-label="Indicadores principales">
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Usuarios activos</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["usuariosActivos"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Tickets abiertos</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["ticketsAbiertos"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Incidencias abiertas</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["incidenciasAbiertas"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Solicitudes abiertas</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["solicitudesAbiertas"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Préstamos pendientes</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["prestamosPendientes"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Dispositivos activos</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["dispositivosActivos"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
                <article class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="h6">Registros de uso</h2>
                            <p class="display-6 mb-0"><?= htmlspecialchars((string) ($metricas["registrosUso"] ?? 0)) ?></p>
                        </div>
                    </div>
                </article>
            </section>
        </section>
    </main>

    <footer class="sgrsi-footer text-light mt-auto py-3 py-md-4">
        <address class="d-flex flex-column flex-md-row justify-content-center gap-2 gap-md-3 text-center mb-2">
            <a href="http://instagram.com" class="text-light text-decoration-none">@SGRSI</a>
            <a href="tel:+29043586" class="text-light text-decoration-none">+29043586</a>
            <a href="mailto:asistentesiti@gmail.com" class="text-light text-decoration-none">asistentesiti@gmail.com</a>
        </address>
        <p class="text-center mb-0">© 2026 SGRSI</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/administrador.js"></script>
</body>

</html>


