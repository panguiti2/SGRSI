CREATE TABLE USUARIO (
    cedula CHAR(8) NOT NULL,
    claveHash VARCHAR(255) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_usuario
        PRIMARY KEY (cedula)
);

CREATE TABLE ADMINISTRADOR (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_administrador
        PRIMARY KEY (cedula)
);

CREATE TABLE TECNICO (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_tecnico
        PRIMARY KEY (cedula)
);

CREATE TABLE SOLICITANTE (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_solicitante
        PRIMARY KEY (cedula)
);


ALTER TABLE ADMINISTRADOR
    ADD CONSTRAINT fk_administrador_usuario
    FOREIGN KEY (cedula)
    REFERENCES USUARIO (cedula);

ALTER TABLE TECNICO
    ADD CONSTRAINT fk_tecnico_usuario
    FOREIGN KEY (cedula)
    REFERENCES USUARIO (cedula);

ALTER TABLE SOLICITANTE
    ADD CONSTRAINT fk_solicitante_usuario
    FOREIGN KEY (cedula)
    REFERENCES USUARIO (cedula);


    
