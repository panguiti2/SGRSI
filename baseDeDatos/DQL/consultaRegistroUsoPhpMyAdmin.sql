SELECT
    r.idRegistro,
    u.cedula AS cedulaSolicitante,
    CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
    l.nombre AS laboratorio,
    r.turno,
    r.fechaHora,
    r.nombreDocente,
    r.grupo,
    r.asignatura,
    r.usoMaquinas,
    r.huboIncidencias
FROM REGISTRO_USO AS r
INNER JOIN USUARIO AS u ON u.cedula = r.cedulaSolicitante
INNER JOIN LABORATORIO AS l ON l.idLaboratorio = r.idLaboratorio
ORDER BY r.fechaHora DESC;
