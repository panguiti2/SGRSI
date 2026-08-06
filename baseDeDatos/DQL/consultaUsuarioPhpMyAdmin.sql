
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


SELECT cedula
FROM USUARIO
WHERE activo = TRUE;


SELECT cedula
FROM USUARIO
WHERE activo = FALSE;


SELECT u.cedula, u.activo
FROM USUARIO AS u
INNER JOIN ADMINISTRADOR AS a ON a.cedula = u.cedula;

SELECT u.cedula, u.activo
FROM USUARIO AS u
INNER JOIN TECNICO AS t ON t.cedula = u.cedula;


SELECT u.cedula, u.activo
FROM USUARIO AS u
INNER JOIN SOLICITANTE AS s ON s.cedula = u.cedula;


SELECT u.cedula
FROM USUARIO AS u
LEFT JOIN ADMINISTRADOR AS a ON a.cedula = u.cedula
LEFT JOIN TECNICO AS t ON t.cedula = u.cedula
LEFT JOIN SOLICITANTE AS s ON s.cedula = u.cedula
WHERE a.cedula IS NULL
  AND t.cedula IS NULL
  AND s.cedula IS NULL;


SELECT
    u.cedula,
    u.nombre,
    u.apellido,

    CASE
        WHEN a.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS administrador,

    CASE
        WHEN t.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS tecnico,

    CASE
        WHEN s.cedula IS NOT NULL THEN 1
        ELSE 0
    END AS solicitante

FROM USUARIO AS u

LEFT JOIN ADMINISTRADOR AS a
    ON a.cedula = u.cedula

LEFT JOIN TECNICO AS t
    ON t.cedula = u.cedula

LEFT JOIN SOLICITANTE AS s
    ON s.cedula = u.cedula
