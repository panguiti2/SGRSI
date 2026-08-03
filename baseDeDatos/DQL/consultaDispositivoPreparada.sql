SELECT
    d.id AS idDispositivo,
    c.id AS idComponente,
    tc.nombre AS tipoComponente,
    c.marca,
    c.modelo,
    c.estado
FROM DISPOSITIVO AS d
LEFT JOIN COMPONENTE AS c
    ON c.idDispositivo = d.id
LEFT JOIN TIPOCOMPONENTE AS tc
    ON tc.idComponente = c.id
WHERE d.id = :id;