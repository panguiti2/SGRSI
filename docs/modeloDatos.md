# Modelo de datos de SGRSI

## Diagrama entidad-relación

```mermaid
erDiagram
    USUARIO ||--o| ADMINISTRADOR : posee
    USUARIO ||--o| TECNICO : posee
    USUARIO ||--o| SOLICITANTE : posee
    SOLICITANTE ||--o{ SOLICITUD : crea
    SOLICITANTE ||--o{ INCIDENCIA : reporta
    SOLICITANTE ||--o{ REGISTRO_USO : registra
    LABORATORIO ||--o{ DISPOSITIVO : contiene

    USUARIO {
        char_8 cedula PK
        varchar nombre
        varchar apellido
        varchar claveHash
        boolean estado
    }
    ADMINISTRADOR { char_8 cedula PK_FK }
    TECNICO { char_8 cedula PK_FK }
    SOLICITANTE { char_8 cedula PK_FK }
    LABORATORIO { char_8 idLab PK varchar nombre }
    DISPOSITIVO { char_8 idLab PK_FK char_8 numeroDispositivo PK varchar modificaciones timestamp ultimoCambio boolean estado }
    PRESTAMO { char_8 idPrestamo PK char_8 cedulaSolicitante varchar turno varchar nombreSolicitante char_8 numeroLaptop datetime fechaRetiro datetime fechaDevolucion }
    SOLICITUD { char_8 idSolicitud PK char_8 cedulaSolicitante FK varchar laboratorio varchar turno datetime fechaHora varchar tipoServicio varchar estado }
    INCIDENCIA { char_8 idIncidencia PK char_8 cedulaSolicitante FK varchar laboratorio datetime fechaHora varchar recurso varchar estado }
    REGISTRO_USO { char_8 idRegistro PK char_8 cedulaSolicitante FK varchar laboratorio datetime fechaHora boolean usoMaquinas boolean huboIncidencias }
```

Cada usuario pertenece a un único subtipo de rol. Esta exclusividad se valida en la lógica de alta. `fechaDevolucion` forma parte de `PRESTAMO`; no existe una entidad de devolución. Todo recurso físico se ubica en un laboratorio y el concepto Taller no integra el modelo.

## Modelo relacional

- USUARIO(**cedula**, nombre, apellido, claveHash, estado)
- ADMINISTRADOR(**cedula** → USUARIO.cedula)
- TECNICO(**cedula** → USUARIO.cedula)
- SOLICITANTE(**cedula** → USUARIO.cedula)
- LABORATORIO(**idLab**, nombre)
- DISPOSITIVO(**idLab** → LABORATORIO.idLab, **numeroDispositivo**, modificaciones, ultimoCambio, estado)
- PRESTAMO(**idPrestamo**, cedulaSolicitante, turno, nombreSolicitante, numeroLaptop, fechaRetiro, fechaDevolucion)
- SOLICITUD(**idSolicitud**, cedulaSolicitante → SOLICITANTE.cedula, laboratorio, turno, docente, asignatura, email, fechaHora, tipoServicio, software, todasMaquinas, prioridad, descripcion, estado)
- INCIDENCIA(**idIncidencia**, cedulaSolicitante → SOLICITANTE.cedula, laboratorio, turno, fechaHora, docente, grupo, asignatura, reportoAlumno, nombreAlumno, maquina, recurso, tipoIncidencia, descripcion, vencimiento, estado, urgencia, tecnicoAsignado)
- REGISTRO_USO(**idRegistro**, cedulaSolicitante → SOLICITANTE.cedula, laboratorio, turno, fechaHora, docente, grupo, asignatura, usoMaquinas, huboIncidencias)

## Tercera forma normal

El modelo cumple 1FN porque todos los atributos son atómicos y no contiene grupos repetidos. Cumple 2FN porque los atributos no clave dependen de la clave completa; en la única clave compuesta, DISPOSITIVO, sus datos describen al dispositivo identificado por laboratorio y número. Cumple 3FN porque los datos de usuarios y laboratorios se almacenan una sola vez y las tablas operativas conservan únicamente sus claves foráneas, sin dependencias transitivas entre atributos no clave.

Los campos de estado y asignación pertenecen al hecho que describen. Los datos capturados del contexto de clase se conservan en solicitudes, incidencias y registros de uso porque representan la información histórica declarada al crear cada registro.
