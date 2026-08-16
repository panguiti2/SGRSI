SELECT
    t.id AS idSolicitud, t.cedulaSolicitante, t.laboratorio,
    s.turno, t.NombreDocente AS docente, s.asignatura, s.email,
    t.fechaHora, s.TipoServicio AS tipoServicio, s.software,
    s.todasMaquinas, s.prioridad, s.descripcion, t.estado
FROM TICKET AS t
INNER JOIN SERVICIO AS s ON s.id = t.id
WHERE t.cedulaSolicitante = :cedulaSolicitante
ORDER BY t.fechaHora DESC;
