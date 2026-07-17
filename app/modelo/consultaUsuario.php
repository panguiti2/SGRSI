<?php

require_once __DIR__ . "/Usuario.php";

class ConsultaUsuario
{
    private array $usuarios;

    public function __construct()
    {
        $this->usuarios = [
            new Usuario(
                "12345678",
                "Administrador SGRSI",
                "admin123",
                "administrador"
            ),

            new Usuario(
                "23456789",
                "Técnico SGRSI",
                "tecnico123",
                "tecnico"
            ),

            new Usuario(
                "34567890",
                "Usuario Solicitante",
                "usuario123",
                "solicitante"
            )
        ];
    }

    public function buscarPorCedula(string $cedula): ?Usuario
    {
        foreach ($this->usuarios as $usuario) {
            if ($usuario->getCedula() === $cedula) {
                return $usuario;
            }
        }

        return null;
    }
}