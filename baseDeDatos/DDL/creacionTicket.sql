CREATE TABLE TICKET (
    id CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    cedulaTecnico CHAR(8) NULL,
    idLaboratorio CHAR(8) NULL,
    numeroDispositivo CHAR(8) NULL,
    fechaApertura DATETIME NOT NULL,
    fechaCierre DATETIME NULL,
    fechaGestion DATETIME NULL,
    grupo VARCHAR(50) NOT NULL,
    nombreDocente VARCHAR(100) NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    turno VARCHAR(20) NOT NULL,
    estado VARCHAR(15) NOT NULL DEFAULT 'PENDIENTE',
    asignatura VARCHAR(100) NOT NULL,
    CONSTRAINT pk_ticket PRIMARY KEY (id),
    CONSTRAINT fk_ticket_solicitante FOREIGN KEY (cedulaSolicitante) REFERENCES SOLICITANTE (cedula),
    CONSTRAINT fk_ticket_tecnico FOREIGN KEY (cedulaTecnico) REFERENCES TECNICO (cedula),
    CONSTRAINT fk_ticket_dispositivo FOREIGN KEY (idLaboratorio, numeroDispositivo) REFERENCES DISPOSITIVO (idLaboratorio, numeroDispositivo),
    CONSTRAINT chk_ticket_fechas CHECK (fechaCierre IS NULL OR fechaCierre >= fechaApertura)
);

CREATE TABLE INCIDENCIA (
    id CHAR(8) NOT NULL,
    reportoAlumno BOOLEAN NOT NULL,
    nombreAlumno VARCHAR(100) NULL,
    CONSTRAINT pk_incidencia PRIMARY KEY (id),
    CONSTRAINT fk_incidencia_ticket FOREIGN KEY (id) REFERENCES TICKET (id),
    CONSTRAINT chk_incidencia_alumno CHECK (
        (reportoAlumno = TRUE AND nombreAlumno IS NOT NULL AND TRIM(nombreAlumno) <> '')
        OR (reportoAlumno = FALSE AND nombreAlumno IS NULL)
    )
);

CREATE TABLE SERVICIO (
    idServicio CHAR(8) NOT NULL,
    tipoServicio VARCHAR(50) NOT NULL,
    fechaEsperada DATETIME NULL,
    CONSTRAINT pk_servicio PRIMARY KEY (idServicio),
    CONSTRAINT fk_servicio_ticket FOREIGN KEY (idServicio) REFERENCES TICKET (id)
);

ALTER TABLE DISPOSITIVO
    ADD CONSTRAINT fk_dispositivo_administrador
    FOREIGN KEY (cedulaAdministrador)
    REFERENCES ADMINISTRADOR (cedula);
