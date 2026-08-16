START TRANSACTION;

INSERT INTO TICKET (id, cedulaSolicitante, fechaApertura, grupo, nombreDocente, descripcion, turno, estado, asignatura)
VALUES ('INC00001', '33333333', '2026-08-14 09:00:00', '3A', 'Ana Pérez', 'El alumno informó una incidencia.', 'MATUTINO', 'PENDIENTE', 'Programación');

INSERT INTO INCIDENCIA (id, reportoAlumno, nombreAlumno)
VALUES ('INC00001', TRUE, 'Juan Pérez');

COMMIT;
