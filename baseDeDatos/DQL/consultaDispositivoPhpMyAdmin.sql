SELECT
    d.idLab,
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
    ON l.idLab = d.idLab
ORDER BY d.idLab, d.numeroDispositivo;
