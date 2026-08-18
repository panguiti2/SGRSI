SELECT
    r.idRegistro,
    r.cedulaSolicitante,
    l.nombre AS laboratorio,
    r.turno,
    r.fechaHora,
    r.nombreDocente,
    r.grupo,
    r.asignatura,
    r.usoMaquinas,
    r.huboIncidencias
FROM REGISTRO_USO AS r
INNER JOIN LABORATORIO AS l ON l.idLaboratorio = r.idLaboratorio
WHERE r.cedulaSolicitante = :cedulaSolicitante
ORDER BY r.fechaHora DESC;
