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
    cedulaAdministrador,

    FK idLaboratorio → LABORATORIO.idLaboratorio,
    FK cedulaAdministrador → ADMINISTRADOR.cedula
)

PRESTAMO(
    idPrestamo PK,
    numeroLaptop,
    fechaRetiro,
    fechaDevolucion,
    fechaSolicitud,
    turno,
    cedulaSolicitante
)

TICKET(
    id PK,
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
    id PK,
    reportoAlumno,
    nombreAlumno,

    FK id → TICKET.id
)

SERVICIO(
    idServicio PK,
    tipoServicio,

    FK idServicio → TICKET.id
)

