<?php


class AccesoDatosDispositivo {
    private PDO $conexion;

    public function __construct (PDO $conexion) {
        $this->conexion = $conexion;
    }
    
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
