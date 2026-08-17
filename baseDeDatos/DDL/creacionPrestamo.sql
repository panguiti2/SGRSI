CREATE TABLE PRESTAMO (
    idPrestamo CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    turno VARCHAR(20) NOT NULL,
    nombreSolicitante VARCHAR(100) NOT NULL,
    numeroLaptop VARCHAR(20) NOT NULL,
    fechaRetiro DATETIME NOT NULL,
    fechaDevolucion DATETIME NOT NULL,
    fechaDevolucionReal DATETIME NULL,
    estado VARCHAR(10) NOT NULL DEFAULT 'ACTIVO',

    CONSTRAINT pk_prestamo PRIMARY KEY (idPrestamo),
    CONSTRAINT chk_prestamo_fechas CHECK (
        fechaDevolucion >= fechaRetiro
        AND (fechaDevolucionReal IS NULL OR fechaDevolucionReal >= fechaRetiro)
    ),
    CONSTRAINT chk_prestamo_estado CHECK (estado IN ('ACTIVO', 'CERRADO'))
);
