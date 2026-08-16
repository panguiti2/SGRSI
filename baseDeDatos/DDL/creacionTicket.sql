CREATE TABLE TICKET (
    id CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    fechaApertura DATETIME NOT NULL,
    fechaCierre DATETIME NULL,
    grupo VARCHAR(50) NOT NULL,
    nombreDocente VARCHAR(100) NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    turno VARCHAR(20) NOT NULL,
    estado VARCHAR(15) NOT NULL DEFAULT 'PENDIENTE',
    asignatura VARCHAR(100) NOT NULL,
    CONSTRAINT pk_ticket PRIMARY KEY (id),
    CONSTRAINT fk_ticket_solicitante FOREIGN KEY (cedulaSolicitante) REFERENCES SOLICITANTE (cedula),
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
    CONSTRAINT pk_servicio PRIMARY KEY (idServicio),
    CONSTRAINT fk_servicio_ticket FOREIGN KEY (idServicio) REFERENCES TICKET (id)
);

CREATE TABLE GESTIONA (
    idTicket CHAR(8) NOT NULL,
    cedulaTecnico CHAR(8) NOT NULL,
    fecha DATETIME NOT NULL,
    CONSTRAINT pk_gestiona PRIMARY KEY (idTicket),
    CONSTRAINT fk_gestiona_ticket FOREIGN KEY (idTicket) REFERENCES TICKET (id),
    CONSTRAINT fk_gestiona_tecnico FOREIGN KEY (cedulaTecnico) REFERENCES TECNICO (cedula)
);

CREATE TABLE INCLUYE (
    idTicket CHAR(8) NOT NULL,
    idLaboratorio CHAR(8) NOT NULL,
    numeroDispositivo CHAR(8) NOT NULL,
    CONSTRAINT pk_incluye PRIMARY KEY (idTicket, idLaboratorio, numeroDispositivo),
    CONSTRAINT fk_incluye_ticket FOREIGN KEY (idTicket) REFERENCES TICKET (id),
    CONSTRAINT fk_incluye_dispositivo FOREIGN KEY (idLaboratorio, numeroDispositivo) REFERENCES DISPOSITIVO (idLaboratorio, numeroDispositivo)
);

CREATE TABLE CONTROLA (
    cedulaAdministrador CHAR(8) NOT NULL,
    idLaboratorio CHAR(8) NOT NULL,
    numeroDispositivo CHAR(8) NOT NULL,
    CONSTRAINT pk_controla PRIMARY KEY (cedulaAdministrador, idLaboratorio, numeroDispositivo),
    CONSTRAINT fk_controla_administrador FOREIGN KEY (cedulaAdministrador) REFERENCES ADMINISTRADOR (cedula),
    CONSTRAINT fk_controla_dispositivo FOREIGN KEY (idLaboratorio, numeroDispositivo) REFERENCES DISPOSITIVO (idLaboratorio, numeroDispositivo)
);
