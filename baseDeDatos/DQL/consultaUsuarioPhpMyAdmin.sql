
SELECT
    u.cedula,
    u.claveHash,
    u.activo,
    CASE WHEN a.cedula IS NOT NULL THEN 1 ELSE 0 END AS administrador,
    CASE WHEN t.cedula IS NOT NULL THEN 1 ELSE 0 END AS tecnico,
    CASE WHEN s.cedula IS NOT NULL THEN 1 ELSE 0 END AS solicitante
FROM USUARIO AS u
LEFT JOIN ADMINISTRADOR AS a ON a.cedula = u.cedula
LEFT JOIN TECNICO AS t ON t.cedula = u.cedula
LEFT JOIN SOLICITANTE AS s ON s.cedula = u.cedula
WHERE u.cedula = '33333333';
