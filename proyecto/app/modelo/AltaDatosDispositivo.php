<?php

/**
 * Clase encargada de realizar operaciones de alta
 * relacionadas con los usuarios del sistema.
 */
class AltaDatosDispositivo
{
    private PDO $conexion;

    /**
     * Constructor parametrizado que recibe una conexión a la base de datos.
     * @param PDO $conexion La conexión a la base de datos. PRECONDICIÓN: No debe ser NULL.
     */
    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
         }

/**
     * Registra un nuevo dispositivo con su rol.
     *
     * @param string $idLab id del laboratorio.
     * @param string $numeroDispositivo Numero del dispositivo.
     * @param string $Modificaciones modificaciones realizadas.
     * @param string $ultimoCambio ultimo cambio registrado.
     * @param bool $estado estado del dispositivo.
     *
     * @return bool TRUE si el registro se completa correctamente, FALSE en caso contrario.
     */
    public function registrarDispositivo(string $idLab, string $numeroDispositivo, string $Modificaciones, string $ultimoCambio, bool $estado): bool
    {

        try {
            //Método que ejecuta de forma agrupada todas las instrucciones dirigidas a la base de datos
            //Si una instrucción falla, retorna excepción con la posibilidad de deshacer cambios con rollBack()
            $this->conexion->beginTransaction();

            $sqlDispositivo = "INSERT INTO DISPOSITIVO (idLab, numeroDispositivo, Modificaciones, ultimoCambio, estado) VALUES (:idLab, :numeroDispositivo, :Modificaciones, :ultimoCambio, :estado)";

            $consultaDispositivo = $this->conexion->prepare($sqlDispositivo);

            $consultaDispositivo->execute(["idLab" => $idLab, "numeroDispositivo" => $numeroDispositivo, "Modificaciones" => $Modificaciones, "ultimoCambio" => $ultimoCambio, "estado" => $estado]);

            //Confirma todas las operaciones realizadas.
            $this->conexion->commit();

            return true;

        } catch (PDOException $error) {

            //Verifica si se encuentra en una transacción
            if ($this->conexion->inTransaction()) {
                //Deshace los cambios causados por la excepción
                $this->conexion->rollBack();
            }

            return false;
        }
    }
}

?>         
                  
