USUARIO(
    cedula PK,
    claveHash,
    estado,
    nombre,
    apellido
)

ADMINISTRADOR(
    cedula PK, FK,
    
    cedula es FK de USUARIO(cedula)
)

TECNICO(
    cedula PK, FK,

    cedula es FK de USUARIO(cedula)
)

SOLICITANTE(
    cedula PK, FK,

    cedula es FK de USUARIO(cedula)
)

GERENCIAR(
    cedulaAdministrador PK, FK,
    cedula PK, FK,

    cedulaAdministrador es FK de ADMINISTRADOR(cedula)
    cedula es FK de USUARIO(cedula)
)

LABORATORIO(
    idLaboratorio PK,
    nombre
)

USA(
    cedulaSolicitante PK, FK,
    idLaboratorio PK, FK,

    cedulaSolicitante es FK de SOLICITANTE(cedula)
    idLaboratorio es FK de LABORATORIO(idLaboratorio)
)

DISPOSITIVO(
    idLaboratorio PK, FK,
    numeroDispositivo PK,
    estado,
    ultimoCambio,
    modificaciones,

    idLaboratorio es FK de LABORATORIO(idLaboratorio)
)

CONTROLA(
    cedulaAdministrador PK, FK,
    numeroDispositivo PK, FK,

    cedulaAdministrador es FK de ADMINISTRADOR(cedula)
    numeroDispositivo es FK de DISPOSITIVO(numeroDispositivo, idLaboratorio)
)    

REGISTROUSO(
    id PK,
    fecha,
    horaEntrada,
    horaSalida,
    turno,
    grupo,
    asignatura,
    cedulaSolicitante FK,
    idLaboratorio FK,

    cedulaSolicitante, idLaboratorio son FK de USA(cedulaSolicitante, idLaboratorio)
)

PRESTAMO(
    idPrestamo PK,
    numeroLaptop,
    fechaEsperada,
    fechaDevolucion,
    estado,
    nombreSolicitanteP,
    cedulaSolicitanteP,
    cedulaTecnico FK,
    fechaRegistro

    cedulaTecnico es FK de TECNICO(cedula)
)

TICKET(
    id PK,
    fechaApertura,
    fechaCierre,
    asignatura,
    estado,
    turno,
    grupo,
    descripcion,
    cedulaSolicitante FK,
    cedulaTecnico FK,
    numeroDispositivo FK,
    idLaboratorio FK,
    fechaGestion

    cedulaSolicitante es FK de SOLICITANTE(cedula)
    cedulaTecnico es FK de TECNICO(cedula)
    numeroDispositivo, idLaboratorio son FK de DISPOSITIVO(numeroDispositivo, idLaboratorio)
)

INCIDENCIA(
    idTicket PK, FK,
    reportoAlumno,
    nombreAlumno,

    idTicket es FK de TICKET(id)
)

SERVICIO(
    idTicket PK, FK,
    tipoServicio,

    idTicket es FK de TICKET(id)
)

