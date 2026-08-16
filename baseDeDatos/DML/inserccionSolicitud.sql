INSERT INTO SOLICITUD (
    idSolicitud, cedulaSolicitante, laboratorio, turno, docente,
    asignatura, email, fechaHora, tipoServicio, software,
    todasMaquinas, prioridad, descripcion, estado
) VALUES (
    'SOL00001', '33333333', 'LAB1', 'MATUTINO', 'Ana Pérez',
    'Programación', 'ana@example.com', '2026-08-17 08:00:00',
    'INSTALACION', 'Visual Studio Code', TRUE, 'NORMAL',
    'Instalación para la clase.', 'PENDIENTE'
);
