// Ejecutar únicamente contra tests/routerApi.php y la base ficticia aislada.
const assert = require('node:assert/strict');
const base = 'http://127.0.0.1:3107/index.php?ruta=';
let total = 0;
function cliente() {
    let cookie = '', token = '';
    return async (ruta, metodo='GET', datos, esperado=200, opciones={}) => {
        const headers = {Cookie:cookie, 'X-CSRF-Token':opciones.token ?? token};
        if(datos !== undefined) headers['Content-Type']=opciones.tipo ?? 'application/json';
        const respuesta = await fetch(base+ruta,{method:metodo,headers,body:opciones.cuerpo ?? (datos===undefined?undefined:JSON.stringify(datos)),signal:AbortSignal.timeout(15000)});
        if(respuesta.headers.get('set-cookie')) cookie=respuesta.headers.get('set-cookie').split(';')[0];
        const contenido=await respuesta.json();
        assert.equal(respuesta.status,esperado, `${metodo} ${ruta}: ${JSON.stringify(contenido)}`);
        if(contenido.datos?.csrfToken) token=contenido.datos.csrfToken;
        total++;
        return contenido.datos ?? contenido;
    };
}
(async () => {
    const anonimo=cliente(), admin=cliente(), tecnico=cliente(), solicitante=cliente();
    await anonimo('usuarios','GET',undefined,401);
    await anonimo('inexistente','GET',undefined,404);
    await anonimo('login','POST',{cedula:'',clave:''},422);
    for(const [c,cedula] of [[admin,'11111111'],[tecnico,'22222222'],[solicitante,'33333333']]) {
        await c('login','POST',{cedula,clave:'PruebaAislada123!'});
        await c('sesion');
    }
    await solicitante('usuarios','GET',undefined,403);
    await tecnico('usuarios','GET',undefined,403);
    await admin('formulario&rol=administrador&seccion=usuarios');
    await tecnico('formulario&rol=tecnico&seccion=prestamos');
    await solicitante('formulario&rol=solicitante&seccion=incidencias');
    await solicitante('formulario&rol=administrador&seccion=usuarios','GET',undefined,403);
    assert.equal((await admin('formulario&rol=&seccion=usuarios','GET',undefined,422)).mensaje,'Existen campos vacios');
    assert.equal((await admin('formulario&rol=administrador&seccion=','GET',undefined,422)).mensaje,'Existen campos vacios');
    const usuario={cedula:'11111111',nombre:'Duplicado',apellido:'Prueba',clave:'PruebaAislada123!',confirmarClave:'PruebaAislada123!',rol:'administrador'};
    assert.equal((await admin('usuarios','POST',usuario,409)).mensaje,'La cédula ya está registrada');
    for(const [c,ruta,datos] of [[admin,'usuarios',{...usuario,nombre:''}], [admin,'dispositivos',{}], [tecnico,'prestamos',{}], [solicitante,'tickets',{}], [solicitante,'registrosUso',{}]]) {
        assert.equal((await c(ruta,'POST',datos,422)).mensaje,'Existen campos vacios');
    }
    const prestamo={cedulaSolicitante:'99999999',turno:'MATUTINO',nombreSolicitante:'Externo',numeroLaptop:'LT-01',retiro:'2026-10-08T10:00',devolucion:'2026-10-09T10:00'};
    const creado=await tecnico('prestamos','POST',prestamo,201);
    await tecnico('prestamos','PUT',{idPrestamo:creado.idPrestamo,fechaDevolucion:'2026-10-07T09:00'},422);
    await tecnico('prestamos','PUT',{idPrestamo:'PRE00000',fechaDevolucion:'2026-10-08T11:00'},404);
    assert.equal((await tecnico('prestamos','POST',prestamo,409)).mensaje,'La cédula ya posee un préstamo activo');
    await tecnico('prestamos','PUT',{...prestamo,idPrestamo:creado.idPrestamo,accion:'editar'},405);
    await tecnico('prestamos','DELETE',{},405);
    await tecnico('prestamos','PUT',{idPrestamo:creado.idPrestamo,fechaDevolucion:'2026-10-08T11:00'});
    await tecnico('prestamos','PUT',{idPrestamo:creado.idPrestamo,fechaDevolucion:'2026-10-08T12:00'},409);
    const ticket={tipo:'INCIDENCIA',turno:'MATUTINO',nombreDocente:'Docente',grupo:'3MB',asignatura:'Programación',idLaboratorio:'LAB01',numeroDispositivo:'PC01',descripcion:'No enciende',reportoAlumno:'NO'};
    const incidencia=await solicitante('tickets','POST',ticket,201);
    await solicitante('tickets','PUT',{...ticket,id:incidencia.id,accion:'editar'},405);
    await solicitante('tickets','DELETE',{},405);
    const gestion={id:incidencia.id,tipo:'INCIDENCIA',estado:'EN PROCESO',diagnostico:'En revisión',solucion:''};
    await tecnico('tickets','PUT',gestion);
    assert.equal((await tecnico('tickets','PUT',{...gestion,estado:'RESUELTO'},422)).mensaje,'Existen campos vacios');
    assert.equal((await tecnico('tickets','PUT',{...gestion,estado:'resuelto'},422)).mensaje,'Existen campos vacios');
    await tecnico('tickets','PUT',{...gestion,estado:'RESUELTO',solucion:'Se ajustó conexión'});
    await tecnico('tickets','PUT',gestion,409);
    await tecnico('tickets','PUT',{...gestion,estado:'resuelto',solucion:'No sobrescribir'},409);
    const listado=await solicitante('tickets&tipo=INCIDENCIA');
    assert.equal(listado.find(t=>t.id===incidencia.id).estado,'RESUELTO'); total++;
    const solicitud=await solicitante('tickets','POST',{...ticket,tipo:'SOLICITUD',tipoServicio:'INSTALACION',fechaEsperada:'2026-10-09T10:00'},201);
    await tecnico('tickets','PUT',{id:solicitud.id,tipo:'SOLICITUD',estado:'RESUELTO'});
    await tecnico('tickets','PUT',{id:solicitud.id,tipo:'SOLICITUD',estado:'PENDIENTE'},409);
    const anteriores=Number((await admin('metricas')).porLaboratorio.find(l=>l.idLaboratorio==='LAB01').registrosUso);
    await solicitante('registrosUso','POST',{idLaboratorio:'LAB01',turno:'MATUTINO',fechaHora:'2026-10-08T12:00',nombreDocente:'Docente',grupo:'3MB',asignatura:'Programación',usoMaquinas:'SI',huboIncidencias:'NO'},201);
    const metricas=await admin('metricas');
    assert.equal(metricas.porLaboratorio.length,2);
    assert.equal(Number(metricas.porLaboratorio.find(l=>l.idLaboratorio==='LAB01').registrosUso),anteriores+1);total+=2;
    await solicitante('metricas','GET',undefined,403);
    // La edición de un usuario con historial conserva las FK de su rol.
    const cambio={cedula:'33333333',nombre:'Con historial',apellido:'Aislada',clave:'PruebaAislada123!',confirmarClave:'PruebaAislada123!',rol:'solicitante'};
    await admin('usuarios','PUT',cambio);
    assert.equal((await admin('usuarios&cedula=33333333')).nombre,'Con historial');total++;
    await admin('usuarios','PUT',{...cambio,nombre:'No debe guardarse',rol:'tecnico'},409);
    assert.equal((await admin('usuarios&cedula=33333333')).nombre,'Con historial');total++;
    await solicitante('sesion');
    const nuevo={...usuario,cedula:'44444444',nombre:'Sin historial',rol:'solicitante'};
    await admin('usuarios','POST',nuevo,201);
    await admin('usuarios','POST',nuevo,409);
    await admin('usuarios','PUT',{...nuevo,rol:'tecnico'});
    const sinHistorial=await admin('usuarios&cedula=44444444');
    assert.equal(Number(sinHistorial.tecnico),1);assert.equal(Number(sinHistorial.solicitante),0);total+=2;
    const cuentaTemporal=cliente();
    await cuentaTemporal('login','POST',{cedula:'44444444',clave:'PruebaAislada123!'});
    await admin('usuarios','PUT',{cedula:'44444444',estado:'0'});
    await cuentaTemporal('sesion','GET',undefined,401);
    await cuentaTemporal('login','POST',{cedula:'44444444',clave:'PruebaAislada123!'},403);
    assert.equal((await admin('usuarios&cedula=','GET',undefined,422)).mensaje,'Existen campos vacios');
    const dispositivo={idLaboratorio:'LAB01',numeroDispositivo:'PC02',estado:'1',ultimoCambio:'2026-10-08T12:00'};
    await admin('dispositivos','POST',dispositivo,201);
    await admin('dispositivos','POST',dispositivo,409);
    await admin('dispositivos','PUT',{...dispositivo,modificaciones:'Reparado'});
    const editado=await admin('dispositivos&idLaboratorio=LAB01&numeroDispositivo=PC02');
    assert.equal(editado.modificaciones,'Reparado');total++;
    assert.equal((await admin('dispositivos&idLaboratorio=&numeroDispositivo=PC02','GET',undefined,422)).mensaje,'Existen campos vacios');
    await admin('dispositivos','DELETE',{idLaboratorio:'LAB01',numeroDispositivo:'PC01'},409);
    await admin('dispositivos','DELETE',{idLaboratorio:'LAB01',numeroDispositivo:'PC02'});
    await admin('dispositivos','DELETE',{idLaboratorio:'LAB01',numeroDispositivo:'PC02'},404);
    await admin('dispositivos&idLaboratorio=LAB01&numeroDispositivo=PC02','GET',undefined,404);
    // Autorización negativa para cada módulo y método de escritura.
    for(const ruta of ['usuarios','dispositivos','prestamos','tickets','registrosUso','metricas','formulario']) await anonimo(ruta,'GET',undefined,401);
    for(const ruta of ['dispositivos','prestamos','metricas']) await solicitante(ruta,'GET',undefined,403);
    await admin('registrosUso','GET',undefined,403);
    for(const ruta of ['dispositivos','usuarios']) await tecnico(ruta,'POST',{},403);
    for(const ruta of ['tickets','registrosUso','prestamos']) await admin(ruta,'POST',{},403);
    await solicitante('tickets','PUT',gestion,403);
    await admin('usuarios','POST',nuevo,403,{token:'incorrecto'});
    await admin('usuarios','POST',{},400,{cuerpo:'{'});
    await admin('usuarios','POST',{},400,{cuerpo:'[]'});
    await admin('usuarios','POST',{},415,{tipo:'text/plain'});
    await admin('usuarios','POST',{},413,{cuerpo:' '.repeat(65537)});
    await admin('usuarios','POST',{...nuevo,nombre:'dato\u0000'},422);
    await admin('usuarios','POST',{cedula:44444444},422);
    await admin('usuarios','POST',{...nuevo,nombre:'x'.repeat(51)},422);
    await admin('usuarios','POST',{...nuevo,clave:'corta',confirmarClave:'corta'},422);
    await admin('usuarios','POST',{...nuevo,confirmarClave:'NoCoincide123!'},422);
    await admin('usuarios','POST',{...nuevo,clave:'x'.repeat(73),confirmarClave:'x'.repeat(73)},422);
    await admin('dispositivos','POST',{...dispositivo,ultimoCambio:'2026-02-30T12:00'},422);
    await solicitante('tickets','POST',{...ticket,reportoAlumno:'SI',nombreAlumno:''},422);
    await solicitante('registrosUso','POST',{idLaboratorio:'LAB01',turno:'MATUTINO',fechaHora:'2026-02-30T12:00',nombreDocente:'Docente',grupo:'3MB',asignatura:'Programación',usoMaquinas:'SI',huboIncidencias:'NO'},422);
    const otro=cliente();
    await admin('usuarios','POST',{...nuevo,cedula:'55555555'},201);
    await otro('login','POST',{cedula:'55555555',clave:'PruebaAislada123!'});
    assert.equal((await otro('tickets')).length,0);assert.equal((await otro('registrosUso')).length,0);total+=2;
    await otro('logout','POST');
    const obligatorios = [
        [admin,'usuarios','POST',nuevo,['cedula','nombre','apellido','clave','confirmarClave','rol']],
        [admin,'usuarios','PUT',cambio,['cedula','nombre','apellido','clave','confirmarClave','rol']],
        [admin,'usuarios','PUT',{cedula:'44444444',estado:'1'},['cedula','estado']],
        [admin,'dispositivos','POST',dispositivo,['idLaboratorio','numeroDispositivo','estado','ultimoCambio']],
        [admin,'dispositivos','PUT',{...dispositivo,modificaciones:'Reparado'},['idLaboratorio','numeroDispositivo','estado','ultimoCambio','modificaciones']],
        [admin,'dispositivos','DELETE',dispositivo,['idLaboratorio','numeroDispositivo']],
        [tecnico,'prestamos','POST',prestamo,['cedulaSolicitante','turno','nombreSolicitante','numeroLaptop','retiro','devolucion']],
        [tecnico,'prestamos','PUT',{idPrestamo:creado.idPrestamo,fechaDevolucion:'2026-10-09T12:00'},['idPrestamo','fechaDevolucion']],
        [solicitante,'tickets','POST',ticket,['tipo','turno','nombreDocente','grupo','asignatura','idLaboratorio','numeroDispositivo','descripcion','reportoAlumno']],
        [solicitante,'tickets','POST',{...ticket,tipo:'SOLICITUD',tipoServicio:'INSTALACION',fechaEsperada:'2026-10-09T12:00'},['tipoServicio','fechaEsperada']],
        [solicitante,'registrosUso','POST',{idLaboratorio:'LAB01',turno:'MATUTINO',fechaHora:'2026-10-08T12:00',nombreDocente:'Docente',grupo:'3MB',asignatura:'Programación',usoMaquinas:'SI',huboIncidencias:'NO'},['idLaboratorio','turno','fechaHora','nombreDocente','grupo','asignatura','usoMaquinas','huboIncidencias']]
    ];
    for(const [c,ruta,metodo,datos,campos] of obligatorios) for(const campo of campos) {
        const resultado=await c(ruta,metodo,{...datos,[campo]:'   '},422);
        assert.equal(resultado.mensaje,'Existen campos vacios',ruta+' '+campo);total++;
    }
    await admin('usuarios','DELETE',{},405);
    await admin('metricas','POST',{},405);
    await solicitante('registrosUso','PUT',{},405);
    await tecnico('logout','POST');
    await tecnico('sesion','GET',undefined,401);
    await solicitante('logout','POST');
    await solicitante('sesion','GET',undefined,401);
    await admin('logout','POST');
    await admin('sesion','GET',undefined,401);
    console.log(`${total} comprobaciones de integración correctas`);
})().catch(error=>{console.error(error);process.exitCode=1;});
