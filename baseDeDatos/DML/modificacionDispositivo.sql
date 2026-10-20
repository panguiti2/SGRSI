UPDATE DISPOSITIVO
SET modificaciones = :modificaciones,
    ultimoCambio = :ultimoCambio,
    estado = :estado
WHERE eliminado = FALSE AND idLaboratorio = :idLaboratorio
    AND numeroDispositivo = :numeroDispositivo;
