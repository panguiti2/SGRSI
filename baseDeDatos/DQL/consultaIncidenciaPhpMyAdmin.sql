SELECT t.id AS idIncidencia, t.cedulaSolicitante, t.fechaApertura, t.fechaCierre,
       t.grupo, t.nombreDocente, t.descripcion, t.turno, t.estado,
       t.asignatura, i.reportoAlumno, i.nombreAlumno, i.diagnostico, i.solucion,
       t.cedulaTecnico, t.fechaGestion
FROM TICKET AS t
INNER JOIN INCIDENCIA AS i ON i.id = t.id
ORDER BY t.fechaApertura DESC;
