-- Contraseña de todos los usuarios de prueba: password
INSERT INTO USUARIO (cedula, claveHash, activo) VALUES
    ('11111111', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', TRUE),
    ('22222222', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', TRUE),
    ('33333333', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', TRUE),
    ('44444444', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', TRUE),
    ('55555555', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', FALSE);

INSERT INTO ADMINISTRADOR (cedula) VALUES
    ('11111111'),
    ('33333333'),
    ('55555555');

INSERT INTO TECNICO (cedula) VALUES
    ('22222222'),
    ('33333333');

INSERT INTO SOLICITANTE (cedula) VALUES
    ('33333333');
