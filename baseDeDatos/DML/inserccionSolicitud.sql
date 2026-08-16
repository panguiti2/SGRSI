START TRANSACTION;

INSERT INTO TICKET (
    id, cedulaSolicitante, laboratorio, fechaHora, NombreDocente, estado
) VALUES (
    'SOL00001', '33333333', 'LAB1', '2026-08-17 08:00:00',
    'Ana Pérez', 'PENDIENTE'
);

INSERT INTO SERVICIO (
    id, TipoServicio, turno, asignatura, email, software,
    todasMaquinas, prioridad, descripcion
) VALUES (
    'SOL00001', 'INSTALACION', 'MATUTINO', 'Programación',
    'ana@example.com', 'Visual Studio Code', TRUE, 'NORMAL',
    'Instalación para la clase.'
);

COMMIT;
