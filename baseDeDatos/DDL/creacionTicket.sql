CREATE TABLE SOLICITUD (
    idSolicitud CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    laboratorio VARCHAR(10) NOT NULL,
    turno VARCHAR(15) NOT NULL,
    docente VARCHAR(100) NOT NULL,
    asignatura VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    fechaHora DATETIME NOT NULL,
    tipoServicio VARCHAR(20) NOT NULL,
    software VARCHAR(100) NULL,
    todasMaquinas BOOLEAN NOT NULL,
    prioridad VARCHAR(10) NOT NULL DEFAULT 'NORMAL',
    descripcion VARCHAR(500) NULL,
    estado VARCHAR(15) NOT NULL DEFAULT 'PENDIENTE',

    CONSTRAINT pk_solicitud PRIMARY KEY (idSolicitud),
    CONSTRAINT fk_solicitud_solicitante
        FOREIGN KEY (cedulaSolicitante) REFERENCES SOLICITANTE (cedula)
);

CREATE TABLE INCIDENCIA (
    idIncidencia CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    laboratorio VARCHAR(10) NOT NULL,
    turno VARCHAR(15) NOT NULL,
    fechaHora DATETIME NOT NULL,
    docente VARCHAR(100) NOT NULL,
    grupo VARCHAR(50) NOT NULL,
    asignatura VARCHAR(100) NOT NULL,
    reportoAlumno BOOLEAN NOT NULL,
    nombreAlumno VARCHAR(100) NULL,
    maquina INT NULL,
    recurso VARCHAR(20) NOT NULL,
    tipoIncidencia VARCHAR(30) NOT NULL,
    descripcion VARCHAR(500) NOT NULL,
    vencimiento DATETIME NULL,
    estado VARCHAR(15) NOT NULL DEFAULT 'PENDIENTE',
    urgencia VARCHAR(10) NULL,
    tecnicoAsignado VARCHAR(100) NULL,

    CONSTRAINT pk_incidencia PRIMARY KEY (idIncidencia),
    CONSTRAINT fk_incidencia_solicitante
        FOREIGN KEY (cedulaSolicitante) REFERENCES SOLICITANTE (cedula)
);
