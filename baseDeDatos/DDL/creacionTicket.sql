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
