SELECT idIncidencia, cedulaSolicitante, laboratorio, turno, fechaHora,
       docente, grupo, asignatura, reportoAlumno, nombreAlumno, maquina,
       recurso, tipoIncidencia, descripcion, vencimiento, estado,
       urgencia, tecnicoAsignado
FROM INCIDENCIA
WHERE cedulaSolicitante = :cedulaSolicitante
ORDER BY fechaHora DESC;
