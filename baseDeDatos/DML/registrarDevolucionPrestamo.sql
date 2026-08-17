UPDATE PRESTAMO
SET fechaDevolucion = :fechaDevolucion,
    estado = 'CERRADO'
WHERE idPrestamo = :idPrestamo
    AND estado = 'ACTIVO';
