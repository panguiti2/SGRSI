<?php

/** Rechaza campos requeridos sin contenido antes de consultar la base. */
function validarCamposObligatorios(array $campos, string $pagina): void
{
    foreach ($campos as $campo) {
        if (trim((string) $campo) === "") {
            header("Location: " . $pagina . "?error=campos_vacios");
            exit;
        }
    }
}
