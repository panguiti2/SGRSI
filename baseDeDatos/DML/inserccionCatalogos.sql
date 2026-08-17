INSERT IGNORE INTO TURNO (codigoTurno, nombre) VALUES
    ('MATUTINO', 'Matutino'),
    ('VESPERTINO', 'Vespertino'),
    ('NOCTURNO', 'Nocturno');

INSERT IGNORE INTO TIPO_SERVICIO (codigoTipoServicio, nombre) VALUES
    ('INSTALACION', 'Instalación de software'),
    ('ACTUALIZACION', 'Actualización'),
    ('CONFIGURACION', 'Configuración'),
    ('OTRO', 'Otro');

INSERT IGNORE INTO MODIFICACION_DISPOSITIVO (codigoModificacion, nombre) VALUES
    ('N/A', 'Sin modificaciones'),
    ('Reparado', 'Reparado'),
    ('Actualizado', 'Actualizado');
