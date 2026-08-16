CREATE TABLE TICKET (
    id CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    laboratorio VARCHAR(10) NOT NULL,
    fechaHora DATETIME NOT NULL,
    FechaCierre DATETIME NULL,
    NombreDocente VARCHAR(255) NOT NULL,
    estado VARCHAR(15) NOT NULL DEFAULT 'PENDIENTE',

    CONSTRAINT pk_ticket
        PRIMARY KEY (id)
);

CREATE TABLE INCIDENCIA (
    id CHAR(8) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    turno VARCHAR(255) NOT NULL,
    asignatura VARCHAR(255) NOT NULL,
    reportoAlumno BOOLEAN NOT NULL,
    NombreAlumno VARCHAR(255) NULL,
    grupo VARCHAR(255) NOT NULL,
    maquina INT NULL,
    recurso VARCHAR(20) NOT NULL,
    tipoIncidencia VARCHAR(30) NOT NULL,
    vencimiento DATETIME NULL,
    urgencia VARCHAR(10) NULL,
    tecnicoAsignado VARCHAR(100) NULL,

    CONSTRAINT pk_incidencia
        PRIMARY KEY (id)
);

CREATE TABLE SERVICIO (
    id CHAR(8) NOT NULL,
    TipoServicio VARCHAR(20) NOT NULL,
    turno VARCHAR(15) NOT NULL,
    asignatura VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    software VARCHAR(100) NULL,
    todasMaquinas BOOLEAN NOT NULL,
    prioridad VARCHAR(10) NOT NULL DEFAULT 'NORMAL',
    descripcion VARCHAR(500) NULL,

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
