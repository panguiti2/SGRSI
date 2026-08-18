START TRANSACTION;

INSERT INTO TICKET (id, cedulaSolicitante, fechaApertura, grupo, nombreDocente, descripcion, turno, estado, asignatura)
VALUES ('SOL00001', '33333333', '2026-08-17 08:00:00', '3A', 'Ana Pérez', 'Instalación para la clase.', 'MATUTINO', 'PENDIENTE', 'Programación');

INSERT INTO SERVICIO (idServicio, tipoServicio, fechaEsperada)
VALUES ('SOL00001', 'INSTALACION', '2026-08-31 12:00:00');

COMMIT;
