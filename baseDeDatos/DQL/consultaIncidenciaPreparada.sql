SELECT t.id AS idIncidencia, t.cedulaSolicitante, t.fechaApertura, t.fechaCierre,
       t.grupo, t.nombreDocente, t.descripcion, t.turno, t.estado,
       t.asignatura, i.reportoAlumno, i.nombreAlumno,
       t.cedulaTecnico, t.fechaGestion
FROM TICKET AS t
INNER JOIN INCIDENCIA AS i ON i.id = t.id
WHERE t.cedulaSolicitante = :cedulaSolicitante
ORDER BY t.fechaApertura DESC;
