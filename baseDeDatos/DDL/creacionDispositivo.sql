CREATE TABLE DISPOSITIVO (
    id CHAR(8) NOT NULL,

    CONSTRAINT pk_dispositivo
        PRIMARY KEY (id)
);

CREATE TABLE COMPONENTE (
    id CHAR(8) NOT NULL,
    idDispositivo CHAR(8) NOT NULL,
    marca VARCHAR(255) NOT NULL,
    modelo VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_componente
        PRIMARY KEY (id, idDispositivo)
);


CREATE TABLE TIPOCOMPONENTE (
    nombre VARCHAR(255) NOT NULL,
    idComponente CHAR(8) NOT NULL,

    CONSTRAINT pk_tipocomponente
        PRIMARY KEY (idComponente, nombre)
);

ALTER TABLE TIPOCOMPONENTE
    ADD CONSTRAINT fk_tipocomponente_componente
    FOREIGN KEY (idComponente)
    REFERENCES COMPONENTE (id, idDispositivo);

ALTER TABLE COMPONENTE
    ADD CONSTRAINT fk_componente_dispositivo
    FOREIGN KEY (idDispositivo)
    REFERENCES DISPOSITIVO (id);