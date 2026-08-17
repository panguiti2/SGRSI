<?php

/**
 * Administra la conexión PDO con la base de datos del sistema.
 */
class ConectorPDO
{
    private string $servidor;
    private string $usuario;
    private string $clave;
    private string $baseDatos;
    private ?PDO $conexion;



    /**
     * Recibe la configuración de conexión cargada desde el archivo .env.
     * @param string $servidor Servidor de base de datos.
     * @param string $usuario Usuario de conexión.
     * @param string $clave Contraseña de conexión.
     * @param string $baseDatos Nombre de la base de datos.
     */
    public function __construct(string $servidor, string $usuario, string $clave, string $baseDatos) {
        $this->servidor = $servidor;
        $this->usuario = $usuario;
        $this->clave = $clave;
        $this->baseDatos = $baseDatos;
        $this->conexion = null;
    }
    /**
     * Establece una conexión con la base de datos utilizando PDO.
     * Configura el modo de errores para que las excepciones sean manejadas mediante PDOException.
     * @return PDO Conexion activa con la base de datos.
     * POSTCONDICION: Devuelve un objeto PDO válido si la conexión fue exitosa.
     */
    public function establecerConexion(): PDO
    {
        try {

            $this->conexion = new PDO("mysql:host=$this->servidor;dbname=$this->baseDatos;charset=utf8", $this->usuario, 
            $this->clave);

            $this->conexion->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            die("Error al conectar con la base de datos: " . $e->getMessage());

        }

        return $this->conexion;
    }

    /**
     * Cierra la conexión con la base de datos.
     * @return void
     * POSTCONDICION: El atributo $conexion queda con valor NULL.
     */
        public function desconectar(): void
    {
        $this->conexion = null;
    }
}

?>
