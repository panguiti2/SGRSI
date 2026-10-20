CREATE TABLE USUARIO (
    cedula CHAR(8) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    claveHash VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,

    CONSTRAINT pk_usuario
        PRIMARY KEY (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ADMINISTRADOR (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_administrador
        PRIMARY KEY (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE TECNICO (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_tecnico
        PRIMARY KEY (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE SOLICITANTE (
    cedula CHAR(8) NOT NULL,

    CONSTRAINT pk_solicitante
        PRIMARY KEY (cedula)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


    
