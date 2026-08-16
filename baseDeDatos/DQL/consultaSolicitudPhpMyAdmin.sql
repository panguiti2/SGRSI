SELECT
    idSolicitud, cedulaSolicitante, laboratorio, turno, docente,
    asignatura, email, fechaHora, tipoServicio, software,
    todasMaquinas, prioridad, descripcion, estado
FROM SOLICITUD
ORDER BY fechaHora DESC;
