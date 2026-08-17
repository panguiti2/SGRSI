UPDATE USUARIO
SET nombre = :nombre,
    apellido = :apellido,
    claveHash = :claveHash
WHERE cedula = :cedula;

DELETE FROM ADMINISTRADOR WHERE cedula = :cedula;
DELETE FROM TECNICO WHERE cedula = :cedula;
DELETE FROM SOLICITANTE WHERE cedula = :cedula;

INSERT INTO SOLICITANTE (cedula) VALUES (:cedula);
