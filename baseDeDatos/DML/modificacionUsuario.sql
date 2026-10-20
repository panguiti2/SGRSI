UPDATE USUARIO
SET nombre = :nombre,
    apellido = :apellido
WHERE cedula = :cedula;

-- Solo si se indicó una nueva contraseña validada:
UPDATE USUARIO SET claveHash = :claveHash WHERE cedula = :cedula;

-- El DAO bloquea la fila y comprueba que esté activa antes de modificar.
-- El rol solo cambia cuando difiere del anterior. Dentro de la transacción,
-- se elimina la asociación anterior y se inserta la nueva con tablas de una
-- lista permitida; no se borran las tres asociaciones indiscriminadamente.
-- Si el rol tiene registros asociados, se revierte toda la operación.
