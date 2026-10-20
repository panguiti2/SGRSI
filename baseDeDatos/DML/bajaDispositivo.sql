-- Baja lógica. Ejecutar desde el DAO, que comprueba los tickets y registra el historial.
UPDATE DISPOSITIVO SET eliminado = TRUE, estado = FALSE, ultimoCambio = ultimoCambio
WHERE idLaboratorio = :idLaboratorio
    AND numeroDispositivo = :numeroDispositivo;
