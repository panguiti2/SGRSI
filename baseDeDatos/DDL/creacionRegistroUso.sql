CREATE TABLE REGISTRO_USO (
    idRegistro CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    idLaboratorio CHAR(8) NOT NULL,
    turno VARCHAR(20) NOT NULL,
    fechaHora DATETIME NOT NULL,
    nombreDocente VARCHAR(100) NOT NULL,
    grupo VARCHAR(50) NOT NULL,
    asignatura VARCHAR(100) NOT NULL,
    usoMaquinas BOOLEAN NOT NULL,
    huboIncidencias BOOLEAN NOT NULL,

    CONSTRAINT pk_registro_uso PRIMARY KEY (idRegistro),
    CONSTRAINT fk_registro_uso_solicitante FOREIGN KEY (cedulaSolicitante) REFERENCES SOLICITANTE (cedula),
    CONSTRAINT fk_registro_uso_laboratorio FOREIGN KEY (idLaboratorio) REFERENCES LABORATORIO (idLaboratorio),
    CONSTRAINT fk_registro_uso_turno FOREIGN KEY (turno) REFERENCES TURNO (codigoTurno)
);
