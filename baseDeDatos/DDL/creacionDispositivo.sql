CREATE TABLE LABORATORIO (
    idLaboratorio CHAR(8) NOT NULL,
    nombre VARCHAR(50) NOT NULL,

    CONSTRAINT pk_laboratorio
        PRIMARY KEY (idLaboratorio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE DISPOSITIVO (
    idLaboratorio CHAR(8) NOT NULL,
    numeroDispositivo CHAR(8) NOT NULL,
    Modificaciones VARCHAR(255) NOT NULL DEFAULT 'N/A',
    ultimoCambio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    eliminado BOOLEAN NOT NULL DEFAULT FALSE,
    cedulaAdministrador CHAR(8) NULL,

    CONSTRAINT pk_dispositivo
        PRIMARY KEY (idLaboratorio, numeroDispositivo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE DISPOSITIVO
    ADD CONSTRAINT fk_dispositivo_laboratorio
    FOREIGN KEY (idLaboratorio)
    REFERENCES LABORATORIO (idLaboratorio);
