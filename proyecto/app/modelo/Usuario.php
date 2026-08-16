<?php

/**
 * Representa las credenciales, el estado y el rol de un usuario.
 */
class Usuario
{
    private string $cedula;
    private string $claveHash;
    private bool $activo;
    private bool $administrador;
    private bool $tecnico;
    private bool $solicitante;

    /**
     * Inicializa un usuario con sus credenciales, estado y rol.
     * @param string $cedula Cédula que identifica al usuario.
     * @param string $claveHash Contraseña cifrada del usuario.
     * @param bool $activo Indica si la cuenta está habilitada.
     * @param bool $administrador Indica si tiene rol de administrador.
     * @param bool $tecnico Indica si tiene rol de técnico.
     * @param bool $solicitante Indica si tiene rol de solicitante.
     */
    public function __construct(
        string $cedula,
        string $claveHash,
        bool $activo,
        bool $administrador,
        bool $tecnico,
        bool $solicitante
    ) {
        $this->cedula = $cedula;
        $this->claveHash = $claveHash;
        $this->activo = $activo;
        $this->administrador = $administrador;
        $this->tecnico = $tecnico;
        $this->solicitante = $solicitante;
    }

    /**
     * Obtiene la cédula del usuario.
     * @return string Cédula del usuario.
     */
    public function getCedula(): string
    {
        return $this->cedula;
    }

    /**
     * Obtiene la contraseña cifrada del usuario.
     * @return string Contraseña cifrada.
     */
    public function getClaveHash(): string
    {
        return $this->claveHash;
    }

    /**
     * Indica si la cuenta del usuario está habilitada.
     * @return bool Verdadero si la cuenta está activa.
     */
    public function estaActivo(): bool
    {
        return $this->activo;
    }

    /**
     * Indica si el usuario tiene rol de administrador.
     * @return bool Verdadero si es administrador.
     */
    public function esAdministrador(): bool
    {
        return $this->administrador;
    }

    /**
     * Indica si el usuario tiene rol de técnico.
     * @return bool Verdadero si es técnico.
     */
    public function esTecnico(): bool
    {
        return $this->tecnico;
    }

    /**
     * Indica si el usuario tiene rol de solicitante.
     * @return bool Verdadero si es solicitante.
     */
    public function esSolicitante(): bool
    {
        return $this->solicitante;
    }
}
