CREATE TABLE IF NOT EXISTS HISTORIAL_DISPOSITIVO (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    idLaboratorio CHAR(8) NOT NULL,
    numeroDispositivo CHAR(8) NOT NULL,
    operacion VARCHAR(20) NOT NULL,
    cedulaActor CHAR(8) NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modificacionAnterior VARCHAR(255) NULL,
    modificacionNueva VARCHAR(255) NULL,
    estadoAnterior BOOLEAN NULL,
    estadoNuevo BOOLEAN NULL,
    fechaAnterior DATETIME NULL,
    fechaNueva DATETIME NULL,
    PRIMARY KEY (id),
    INDEX idx_historial_equipo (idLaboratorio, numeroDispositivo, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
