CREATE TABLE LABORATORIO (
    idLab CHAR(8) NOT NULL,
    nombre VARCHAR(50) NOT NULL,

    CONSTRAINT pk_laboratorio
        PRIMARY KEY (idLab)
);

CREATE TABLE DISPOSITIVO (
    idLab CHAR(8) NOT NULL,
    numeroDispositivo CHAR(8) NOT NULL,
    Modificaciones VARCHAR(255) NOT NULL,
    ultimoCambio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    estado BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_dispositivo
        PRIMARY KEY (idLab, numeroDispositivo)
);


ALTER TABLE DISPOSITIVO
    ADD CONSTRAINT fk_dispositivo_laboratorio
    FOREIGN KEY (idLab)
    REFERENCES LABORATORIO (idLab);
