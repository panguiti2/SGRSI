SELECT
    p.idPrestamo,
    p.cedulaSolicitante,
    p.turno,
    p.nombreSolicitante,
    p.numeroLaptop,
    p.fechaRetiro,
    p.fechaDevolucion
FROM PRESTAMO AS p
ORDER BY p.fechaRetiro DESC;
