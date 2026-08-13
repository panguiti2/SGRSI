CREATE TABLE PRESTAMO (
    idPrestamo CHAR(8) NOT NULL,
    cedulaSolicitante CHAR(8) NOT NULL,
    turno VARCHAR(20) NOT NULL,
    nombreSolicitante VARCHAR(100) NOT NULL,
    numeroLaptop VARCHAR(20) NOT NULL,
    fechaRetiro DATETIME NOT NULL,
    fechaDevolucion DATETIME NOT NULL,

    CONSTRAINT pk_prestamo PRIMARY KEY (idPrestamo)
);
