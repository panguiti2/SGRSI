UPDATE DISPOSITIVO
SET modificaciones = :modificaciones,
    ultimoCambio = :ultimoCambio,
    estado = :estado
WHERE idLaboratorio = :idLaboratorio
    AND numeroDispositivo = :numeroDispositivo;
