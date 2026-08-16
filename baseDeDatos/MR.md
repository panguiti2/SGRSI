USUARIO(
    cedula PK,
    claveHash,
    estado,
    nombre,
    apellido
)

ADMINISTRADOR(
    cedula PK,
    FK cedula → USUARIO.cedula
)

TECNICO(
    cedula PK,
    FK cedula → USUARIO.cedula
)

SOLICITANTE(
    cedula PK,
    FK cedula → USUARIO.cedula
)

LABORATORIO(
    idLaboratorio PK,
    nombre
)

DISPOSITIVO(
    idLaboratorio PK,
    numeroDispositivo PK,
    estado,
    ultimoCambio,
    modificaciones,

    FK idLaboratorio → LABORATORIO.idLaboratorio
)

CONTROLA(
    cedulaAdministrador PK,
    idLaboratorio PK,
    numeroDispositivo PK,

    FK cedulaAdministrador → ADMINISTRADOR.cedula,

    FK (idLaboratorio, numeroDispositivo)
        → DISPOSITIVO(idLaboratorio, numeroDispositivo)
)

PRESTAMO(
    idPrestamo PK,
    numeroLaptop,
    fechaRetiro,
    fechaDevolucion,
    fechaSolicitud,
    turno,
    cedulaSolicitante,

    FK cedulaSolicitante → SOLICITANTE.cedula
)

TICKET(
    idTicket PK,
    cedulaSolicitante,
    cedulaTecnico,
    idLaboratorio,
    numeroDispositivo,
    fechaApertura,
    fechaCierre,
    fechaGestion,
    grupo,
    nombreDocente,
    descripcion,
    turno,
    estado,
    asignatura,

    FK cedulaSolicitante → SOLICITANTE.cedula,
    FK cedulaTecnico → TECNICO.cedula,

    FK (idLaboratorio, numeroDispositivo)
        → DISPOSITIVO(idLaboratorio, numeroDispositivo)
)

INCIDENCIA(
    idTicket PK,
    nombreAlumno,

    FK idTicket → TICKET.idTicket
)

REPORTE_ALUMNO(
    idTicket PK,
    reporteAlumno PK,

    FK idTicket → INCIDENCIA.idTicket
)

SERVICIO(
    idTicket PK,
    idServicio UNIQUE,

    FK idTicket → TICKET.idTicket
)

TIPO_SERVICIO(
    idTicket PK,
    tipoServicio PK,

    FK idTicket → SERVICIO.idTicket
)

