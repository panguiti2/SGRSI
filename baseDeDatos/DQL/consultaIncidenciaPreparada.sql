SELECT t.id AS idIncidencia, t.cedulaSolicitante, t.laboratorio,
       i.turno, t.fechaHora, t.NombreDocente AS docente, i.grupo,
       i.asignatura, i.reportoAlumno, i.NombreAlumno AS nombreAlumno,
       i.maquina, i.recurso, i.tipoIncidencia, i.descripcion,
       i.vencimiento, t.estado, i.urgencia, i.tecnicoAsignado
FROM TICKET AS t
INNER JOIN INCIDENCIA AS i ON i.id = t.id
WHERE t.cedulaSolicitante = :cedulaSolicitante
ORDER BY t.fechaHora DESC;
