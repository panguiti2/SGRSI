<?php

/**
 * Clase que simula una recuperación de datos de dispositivos correspondientes a la base de datos.
 */
class AccesoDatosDispositivo {
    private PDO $conexion;


    /**
     * Constructor parametrizado que recibe una conexión a la base de datos.
     * @param PDO $conexion La conexion a la base de datos. PRECONDICION: No debe ser NULL.
     */
    public function __construct (PDO $conexion) {
        $this->conexion = $conexion;
    }
    
    /**
     * Lista todos los dispositivos registrados en la base de datos.
     * @return array lista de dispositivos con sus roles.
     */
    public function listarDispositivos (): array {
        $sql = "
            SELECT
                d.idLab,
                d.numeroDispositivo,
                d.Modificaciones AS modificaciones,
                d.ultimoCambio,
                CASE
                WHEN d.estado = TRUE THEN 'Activo'
                ELSE 'Inactivo'
                END AS estado
            FROM DISPOSITIVO AS d
            INNER JOIN LABORATORIO AS l
                ON l.idLab = d.idLab
            ORDER BY d.idLab, d.numeroDispositivo
        ";  

        $consulta = $this->conexion->query($sql);

        $dispositivos = $consulta->fetchAll(PDO::FETCH_ASSOC);

        $consulta = null;

        return $dispositivos;
    }

}

?>
