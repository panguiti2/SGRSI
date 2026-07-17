<?php

require_once __DIR__ . "/ConsultaUsuario.php";

class Login
{
    private ConsultaUsuario $consultaUsuario;

    public function __construct(ConsultaUsuario $consultaUsuario)
    {
        $this->consultaUsuario = $consultaUsuario;
    }

    public function autenticar(
        string $cedula,
        string $clave
    ): ?Usuario {
        $usuario = $this->consultaUsuario->buscarPorCedula($cedula);

        if ($usuario === null) {
            return null;
        }

        if ($usuario->getClave() !== $clave) {
            return null;
        }

        return $usuario;
    }
}