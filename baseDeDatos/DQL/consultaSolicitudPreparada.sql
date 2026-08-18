SELECT
    t.id AS idSolicitud, t.cedulaSolicitante, t.fechaApertura, t.fechaCierre,
    t.grupo, t.nombreDocente, t.descripcion, t.turno, t.estado,
    t.asignatura, s.tipoServicio, s.fechaEsperada
FROM TICKET AS t
INNER JOIN SERVICIO AS s ON s.idServicio = t.id
WHERE t.cedulaSolicitante = :cedulaSolicitante
ORDER BY t.fechaApertura DESC;
