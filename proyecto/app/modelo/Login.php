<?php

require_once __DIR__ . "/../modelo/AccesoDatosUsuario.php";

class Login {
    private AccesoDatosUsuario $accesoDatosUsuario;
    private string $codigoError;

    public function __construct(AccesoDatosUsuario $accesoDatosUsuario) {
        $this->accesoDatosUsuario = $accesoDatosUsuario;
        $this->codigoError = "";
    }

    public function autenticar(string $cedula, string $clave): ?Usuario {
        $this->codigoError = "";
        $usuario = $this->accesoDatosUsuario->buscarUsuario($cedula);

        if ($usuario === null) {
            $this->codigoError = "credenciales";
            return null;
        }

        if (!$usuario->estaActivo()) {
            $this->codigoError = "inactivo";
            return null;
        }

        if ( !password_verify($clave, $usuario->getClaveHash() ) ){
            $this->codigoError = "credenciales";
            return null;
        }

        return $usuario;
    }

    public function getCodigoError(): string {
        return $this->codigoError;
    }
}

?>
