Relación USUARIO:
USUARIO (cedula,claveHash, estado, nombre, apellido)
Clave Primaria: {cedula}

Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
Se encuentra en 2FN porque al no tener clave compuesta no puede haber dependencias parciales y entonces todos los atributos dependen de la clave completa.

Tercera Forma Normal
USUARIO se encuentra en 3FN, ya que previamente cumple con 1FN y 2FN. Además, no existen dependencias transitivas, debido a que no hay atributos no clave que dependan de 
otros atributos no clave. 

Resultado
USUARIO (cedula,claveHash, estado, nombre, apellido)

Relación TECNICO:
TECNICO (cedula)
Clave Primaria: {cedula}
Se encuentra en 1FN, 2FN y 3FN, porque no tiene atributos no clave que puedan generar dependencias parciales o transitivas. 

Resultado
TECNICO (cedula)
cedula es FK de USUARIO(cedula)

Relación SOLICITANTE:
SOLICITANTE (cedula)
Clave Primaria: {cedula}
Se encuentra en 1FN, 2FN y 3FN, porque no tiene atributos no clave que puedan generar dependencias parciales o transitivas.

Resultado
SOLICITANTE (cedula)
cedula es FK de USUARIO(cedula)
 

Relación ADMINISTRADOR:
ADMINISTRADOR (cedula)
Clave Primaria: {cedula}
Se encuentra en 1FN, 2FN y 3FN, porque no tiene atributos no clave que puedan generar dependencias parciales o transitivas. 

Resultado
ADMINISTRADOR (cedula)
cedula es FK de USUARIO(cedula)
Relación LABORATORIO :
LABORATORIO (idLaboratorio, nombre)
Clave Primaria: {idLaboratorio}

Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
Se encuentra en 2FN porque al no tener clave compuesta no puede haber dependencias parciales y entonces todos los atributos dependen de la clave completa.

Tercera Forma Normal
LABORATORIO se encuentra en 3FN, ya que previamente cumple con 1FN y 2FN. Además, no existen dependencias transitivas, debido a que no hay atributos no clave que dependan de otros atributos no clave. 

Resultado
LABORATORIO (idLaboratorio, nombre)

Relación USA
USA (cedulaSolicitante, idLaboratorio)
Clave Primaria: {cedulaSolicitante, idLaboratorio}

Se encuentra en 1FN, 2FN y 3FN, porque no tiene atributos no clave que puedan generar dependencias parciales o transitivas. 

Resultado
USA (cedulaSolicitante, idLaboratorio)
cedulaSolicitante es FK de SOLICITANTE(cedula)
idLaboratorio es FK de LABORATORIO(idLaboratorio)

Relación DISPOSITIVO:
DISPOSITIVO (numeroDispositivo, idLaboratorio, estado, ultimoCambio, modificaciones)
Clave Primaria: {idLaboratorio, numeroDispositivo}

Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
{idLaboratorio, numeroDispositivo} -> {estado} D.F. Total
{idLaboratorio, numeroDispositivo} -> {ultimoCambio} D.F. Total
{idLaboratorio, numeroDispositivo} -> {modificaciones} D.F. Total
Se encuentra en 2FN porque los atributos dependen de la clave completa y no hay dependencias parciales.

Tercera Forma Normal
DISPOSITIVO se encuentra en 3FN, ya que previamente cumple con 1FN y 2FN. Además, no existen dependencias transitivas, debido a que no hay atributos no clave que dependan de otros atributos no clave.

Resultado
DISPOSITIVO (numeroDispositivo, idLaboratorio, estado, ultimoCambio, modificaciones)
   idLaboratorio es FK de LABORATORIO(idLaboratorio)


Relación CONTROLA:
CONTROLA (cedulaAdministrador, numeroDispositivo)
Clave Primaria: {cedulaAdministrador, numeroDispositivo}

Se encuentra en 1FN, 2FN y 3FN, porque no tiene atributos no clave que puedan generar dependencias parciales o transitivas. 

Resultado
CONTROLA (cedulaAdministrador, numeroDispositivo)
    cedulaAdministrador es FK de ADMINISTRADOR(cedula)
    numeroDispositivo es FK de DISPOSITIVO(numeroDispositivo, idLaboratorio)

Relación REGISTROUSO:
REGISTROUSO(id,  fecha, horaEntrada, horaSalida, turno, grupo, asignatura  cedulaSolicitante, idLaboratorio)
Clave Primaria: {id}

Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
Se encuentra en 2FN porque al no tener clave compuesta no puede haber dependencias parciales y entonces todos los atributos dependen de la clave completa.

Tercera Forma Normal
REGISTROUSO se encuentra en 3FN, ya que previamente cumple con 1FN y 2FN. Además, no existen dependencias transitivas, debido a que no hay atributos no clave que dependan de otros atributos no clave. 

Resultado
REGISTROUSO(id,  fecha, horaEntrada, horaSalida, turno, grupo, asignatura  cedulaSolicitante, idLaboratorio)
   cedulaSolicitante, idLaboratorio son FK de USA(cedulaSolicitante, idLaboratorio)


Relación PRESTAMO:
PRESTAMO (idPrestamo, numeroLaptop, fechaEsperada, fechaDevolucion, estado, cedulaSolicitanteP, nombreSolicitanteP, cedulaTecnico, fechaRegistro)
Clave Primaria: {idPrestamo}
Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
Se encuentra en 2FN porque al no tener clave compuesta no puede haber dependencias parciales y entonces todos los atributos dependen de la clave completa.

Tercera Forma Normal
{idPrestamo} -> {cedulaSolicitanteP}
{cedulaSolicitanteP} -> {nombreSolicitanteP} D.F. Transitiva 

PRESTAMO no se encuentra en 3FN por dependencias transitivas, por lo que se crea otra relación llamada SOLICITANTEP.
SOLICITANTEP (cedulaSolicitanteP, nombreSolicitanteP)

Resultado
SOLICITANTEP (cedulaSolicitanteP, nombreSolicitanteP)
PRESTAMO (idPrestamo, numeroLaptop, fechaEsperada, fechaDevolucion, estado, cedulaSolicitanteP, cedulaTecnico, fechaRegistro)
            cedulaSolicitanteP es FK de SOLICITANTEP(cedulaSolicitanteP)
            cedulaTecnico es FK de TECNICO(cedula)



Relación TICKET:
TICKET (id, fechaApertura, fechaCierre, asignatura, estado, turno, grupo, descripcion, cedulaSolicitante, cedulaTecnico, numeroDispositivo, idLaboratorio, fechaGestion)
Clave Primaria: {id}

Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
Se encuentra en 2FN porque al no tener clave compuesta no puede haber dependencias parciales y entonces todos los atributos dependen de la clave completa.

Tercera Forma Normal
TICKET se encuentra en 3FN, ya que previamente cumple con 1FN y 2FN. Además, no existen dependencias transitivas, debido a que no hay atributos no clave que dependan de otros atributos no clave. 



Resultado
TICKET (id, fechaApertura, fechaCierre, asignatura, estado, turno, grupo, descripcion, cedulaSolicitante, cedulaTecnico, numeroDispositivo, idLaboratorio, fechaGestion)
cedulaSolicitante es FK de SOLICITANTE(cedula)
    cedulaTecnico es FK de TECNICO(cedula)
    numeroDispositivo, idLaboratorio son FK de DISPOSITIVO(numeroDispositivo, idLaboratorio)

Relación SERVICIO:
SERVICIO (idTicket, tipoServicio)
Clave Primaria: {idTicket}

Primera Forma Normal
Se encuentra en 1FN porque todos sus atributos son atómicos. 

Segunda Forma Normal
Se encuentra en 2FN porque al no tener clave compuesta no puede haber dependencias parciales y entonces todos los atributos dependen de la clave completa.

Tercera Forma Normal
SERVICIO se encuentra en 3FN, ya que previamente cumple con 1FN y 2FN. Además, no existen dependencias transitivas, debido a que no hay atributos no clave que dependan de otros atributos no clave. 

Resultado
SERVICIO (idTicket, tipoServicio)
   idTicket es FK de TICKET(id)



