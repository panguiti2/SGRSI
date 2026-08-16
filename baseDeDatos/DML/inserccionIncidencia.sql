START TRANSACTION;

INSERT INTO TICKET (
    id, cedulaSolicitante, laboratorio, fechaHora, NombreDocente, estado
) VALUES (
    'INC00001', '33333333', 'LAB1', '2026-08-14 09:00:00',
    'Ana Pérez', 'PENDIENTE'
);

INSERT INTO INCIDENCIA (
    id, descripcion, turno, asignatura, reportoAlumno, NombreAlumno,
    grupo, maquina, recurso, tipoIncidencia
) VALUES (
    'INC00001', 'El teclado no responde.', 'MATUTINO', 'Programación',
    FALSE, NULL, '3A', 12, 'TECLADO', 'NO_FUNCIONA'
);

COMMIT;
