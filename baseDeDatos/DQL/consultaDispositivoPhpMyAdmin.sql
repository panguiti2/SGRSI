SELECT
    d.idLaboratorio,
    l.nombre AS laboratorio,
    d.numeroDispositivo,
    d.Modificaciones AS modificaciones,
    CASE
        WHEN d.estado = TRUE THEN 'Activo'
        ELSE 'Inactivo'
    END AS estado,
    d.ultimoCambio
FROM DISPOSITIVO AS d
INNER JOIN LABORATORIO AS l
    ON l.idLaboratorio = d.idLaboratorio
ORDER BY d.idLaboratorio, d.numeroDispositivo;
