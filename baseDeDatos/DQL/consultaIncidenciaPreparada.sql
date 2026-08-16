SELECT t.id AS idIncidencia, t.cedulaSolicitante, t.fechaApertura, t.fechaCierre,
       t.grupo, t.nombreDocente, t.descripcion, t.turno, t.estado,
       t.asignatura, i.reportoAlumno, i.nombreAlumno,
       g.cedulaTecnico, g.fecha AS fechaGestion
FROM TICKET AS t
INNER JOIN INCIDENCIA AS i ON i.id = t.id
LEFT JOIN GESTIONA AS g ON g.idTicket = t.id
WHERE t.cedulaSolicitante = :cedulaSolicitante
ORDER BY t.fechaApertura DESC;
