CREATE TABLE LABORATORIO (
    idLaboratorio CHAR(8) NOT NULL,
    nombre VARCHAR(50) NOT NULL,

    CONSTRAINT pk_laboratorio
        PRIMARY KEY (idLaboratorio)
);

CREATE TABLE DISPOSITIVO (
    idLaboratorio CHAR(8) NOT NULL,
    numeroDispositivo CHAR(8) NOT NULL,
    Modificaciones VARCHAR(255) NOT NULL DEFAULT 'N/A',
    ultimoCambio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    cedulaAdministrador CHAR(8) NULL,

    CONSTRAINT pk_dispositivo
        PRIMARY KEY (idLaboratorio, numeroDispositivo)
);


ALTER TABLE DISPOSITIVO
    ADD CONSTRAINT fk_dispositivo_laboratorio
    FOREIGN KEY (idLaboratorio)
    REFERENCES LABORATORIO (idLaboratorio);
