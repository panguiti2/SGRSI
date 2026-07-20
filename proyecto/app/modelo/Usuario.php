<?php

class Usuario
{
    private string $cedula;
    private string $nombre;
    private string $clave;
    private string $rol;

    public function __construct(
        string $cedula,
        string $nombre,
        string $clave,
        string $rol
    ) {
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->clave = $clave;
        $this->rol = $rol;
    }

    public function getCedula(): string
    {
        return $this->cedula;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getClave(): string
    {
        return $this->clave;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function esAdministrador(): bool
    {
        return $this->rol === "administrador";
    }

    public function esTecnico(): bool
    {
        return $this->rol === "tecnico";
    }

    public function esSolicitante(): bool
    {
        return $this->rol === "solicitante";
    }
}