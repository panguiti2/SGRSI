<?php

require_once RUTA_MODELO . "/AccesoDatosUsuario.php";

/**
 * Autentica usuarios y expone el motivo de un acceso rechazado.
 */
class Login {
    private AccesoDatosUsuario $accesoDatosUsuario;
    private string $codigoError;

    /**
     * Inicializa el servicio con el acceso a datos de usuarios.
     * @param AccesoDatosUsuario $accesoDatosUsuario Repositorio utilizado para buscar usuarios.
     */
    public function __construct(AccesoDatosUsuario $accesoDatosUsuario) {
        $this->accesoDatosUsuario = $accesoDatosUsuario;
        $this->codigoError = "";
    }

    /**
     * Valida las credenciales y el estado del usuario.
     * @param string $cedula Cédula ingresada para iniciar sesión.
     * @param string $clave Contraseña sin cifrar ingresada por el usuario.
     * @return Usuario|null Usuario autenticado o null si la validación falla.
     */
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

    /**
     * Obtiene el código del último error de autenticación.
     * @return string Código de error o cadena vacía si no hubo errores.
     */
    public function getCodigoError(): string {
        return $this->codigoError;
    }
}

?>
