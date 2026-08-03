SELECT
    d.id,
FROM DISPOSITIVO AS d

SELECT
    c.id,
    c.idDispositivo,
    c.marca,
    c.modelo,
    c.estado
FROM COMPONENTE AS c


SELECT
    c.id,
    tc.nombre AS tipoComponente,
    c.marca,
    c.modelo,
    c.estado,
    c.idDispositivo
    FROM COMPONENTE AS c
    INNER JOIN TIPOCOMPONENTE AS tc ON tc.idComponente = c.id;

SELECT
    d.id AS idDispositivo,
    c.id AS idComponente,
    tc.nombre AS tipoComponente,
    c.marca,
    c.modelo,
    c.estado
FROM DISPOSITIVO AS d
LEFT JOIN COMPONENTE AS c ON c.idDispositivo = d.id
LEFT JOIN TIPOCOMPONENTE AS tc ON tc.idComponente = c.id
ORDER BY d.id, c.id;

SELECT
    c.id,
    tc.nombre AS tipoComponente,
    c.marca,
    c.modelo,
    c.estado
FROM COMPONENTE AS c
INNER JOIN TIPOCOMPONENTE AS tc ON tc.idComponente = c.id
WHERE c.idDispositivo = '44';

SELECT
    d.id
FROM DISPOSITIVO AS d
LEFT JOIN COMPONENTE AS c ON c.idDispositivo = d.id
WHERE c.id IS NULL;

SELECT
    c.id,
    tc.nombre AS tipoComponente,
    c.marca,
    c.modelo,
    c.idDispositivo
FROM COMPONENTE AS c
INNER JOIN TIPOCOMPONENTE AS tc ON tc.idComponente = c.id
WHERE c.estado = 'activo';

SELECT
    c.id,
    tc.nombre AS tipoComponente,
    c.marca,
    c.modelo,
    c.idDispositivo
FROM COMPONENTE AS c
INNER JOIN TIPOCOMPONENTE AS tc ON tc.idComponente = c.id
WHERE c.estado = 'inactivo';

SELECT
    d.id,
    COUNT(c.id) AS cantidadComponentes
FROM DISPOSITIVO AS d
LEFT JOIN COMPONENTE AS c ON c.idDispositivo = d.id
GROUP BY d.id
ORDER BY d.id;