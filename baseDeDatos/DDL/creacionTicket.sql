CREATE TABLE TICKET (
    id CHAR(8) NOT NULL,
    FechaCierre VARCHAR(255) NOT NULL,
    NombreDocente VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_ticket
        PRIMARY KEY (id)
);

CREATE TABLE INCIDENCIA (
    id CHAR(8) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    turno VARCHAR(255) NOT NULL,
    asignatura VARCHAR(255) NOT NULL,
    NombreAlumno VARCHAR(255) NOT NULL,
    grupo VARCHAR(255) NOT NULL,

    CONSTRAINT pk_incidencia
        PRIMARY KEY (id)
);

REATE TABLE SERVICIO (
    id CHAR(8) NOT NULL,
    TipoServicio BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_servicio
        PRIMARY KEY (id)
);

ALTER TABLE INCIDENCIA
    ADD CONSTRAINT fk_incidencia_ticket
    FOREIGN KEY (id)
    REFERENCES TICKET (id);

ALTER TABLE SERVICIO
    ADD CONSTRAINT fk_servicio_ticket
    FOREIGN KEY (id)
    REFERENCES TICKET (id);
