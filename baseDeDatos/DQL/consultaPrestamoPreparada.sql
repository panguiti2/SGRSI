SELECT idPrestamo, cedulaSolicitante, turno, nombreSolicitante,
       numeroLaptop, fechaRetiro, fechaDevolucion
FROM PRESTAMO
WHERE cedulaSolicitante = :cedulaSolicitante
ORDER BY fechaRetiro DESC;
