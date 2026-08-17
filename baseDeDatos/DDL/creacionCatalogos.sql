CREATE TABLE IF NOT EXISTS TURNO (
    codigoTurno VARCHAR(20) NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    CONSTRAINT pk_turno PRIMARY KEY (codigoTurno)
);

CREATE TABLE IF NOT EXISTS TIPO_SERVICIO (
    codigoTipoServicio VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    CONSTRAINT pk_tipo_servicio PRIMARY KEY (codigoTipoServicio)
);

CREATE TABLE IF NOT EXISTS MODIFICACION_DISPOSITIVO (
    codigoModificacion VARCHAR(20) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    CONSTRAINT pk_modificacion_dispositivo PRIMARY KEY (codigoModificacion)
);
