<?php

require_once RUTA_MODELO . '/UsuarioDAO.php';

class LoginController
{
    public function autenticar(string $metodo): void
    {
        if ($metodo !== 'POST') throw new ErrorHttp('Método no permitido', 405);
        $datos = Peticion::recibirDatos();
        $cedula = trim($datos['cedula'] ?? '');
        $clave = $datos['clave'] ?? '';
        Peticion::validarCamposObligatorios([$cedula, $clave]);
        Peticion::validarCedula($cedula);
        $dao = new UsuarioDAO(Conexion::conectar());
        $usuario = $dao->buscarCredenciales($cedula);
        if (!$usuario || !password_verify($clave, $usuario['claveHash'])) {
            throw new ErrorHttp('La cédula o la contraseña son incorrectas.', 401);
        }
        if (!$usuario['estado']) throw new ErrorHttp('El usuario se encuentra inactivo.', 403);
        if (!$usuario['administrador'] && !$usuario['tecnico'] && !$usuario['solicitante']) {
            throw new ErrorHttp('El usuario no tiene roles habilitados.', 403);
        }
        Sesion::iniciar($usuario);
        RespuestaJson::exito(Sesion::datos());
    }

    public function cerrarSesion(string $metodo): void
    {
        if ($metodo !== 'POST') throw new ErrorHttp('Método no permitido', 405);
        Token::verificarCsrf();
        Sesion::cerrar();
        RespuestaJson::exito(['mensaje' => 'Sesión cerrada correctamente']);
    }

    public function consultarSesion(string $metodo): void
    {
        if ($metodo !== 'GET') throw new ErrorHttp('Método no permitido', 405);
        RespuestaJson::exito(Sesion::datos());
    }
}
