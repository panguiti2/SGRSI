
INSERT INTO USUARIO (cedula, nombre, apellido, claveHash, estado ) VALUES
    ('11111111', 'John', 'Doe', '$2y$10$9wMFbjtXvc0NVodPXH9vrORiNnNnJfZLV8Dr3qhVTbLFm9uYHeXtK', TRUE),
    ('22222222', 'Jane', 'Smith', '$2y$10$9wMFbjtXvc0NVodPXH9vrORiNnNnJfZLV8Dr3qhVTbLFm9uYHeXtK', TRUE),
    ('33333333', 'Bob', 'Johnson', '$2y$10$9wMFbjtXvc0NVodPXH9vrORiNnNnJfZLV8Dr3qhVTbLFm9uYHeXtK', TRUE),
    ('44444444', 'Alice', 'Williams', '$2y$10$9wMFbjtXvc0NVodPXH9vrORiNnNnJfZLV8Dr3qhVTbLFm9uYHeXtK', TRUE),
    ('55555555', 'Charlie', 'Brown', '$2y$10$9wMFbjtXvc0NVodPXH9vrORiNnNnJfZLV8Dr3qhVTbLFm9uYHeXtK', FALSE);

INSERT INTO ADMINISTRADOR (cedula) VALUES
    ('11111111'),
    ('55555555');

INSERT INTO TECNICO (cedula) VALUES
    ('22222222');

INSERT INTO SOLICITANTE (cedula) VALUES
    ('33333333');
