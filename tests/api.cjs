const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const entorno = {console: {error() {}}, document: {body:{dataset:{}}}, window:{location:{replace(){}}}, fetch: null};
vm.createContext(entorno);
vm.runInContext(fs.readFileSync('proyecto/public/assets/js/api.js', 'utf8'), entorno);
(async () => {
    for (const [cuerpo, estado, mensaje] of [['',200,'sin cuerpo'],['<html>',500,'no devolvió JSON'],['{"mensaje":"Conflicto"}',409,'Conflicto'],['{}',200,'datos esperados']]) {
        entorno.fetch = async () => new Response(cuerpo,{status:estado});
        await assert.rejects(entorno.peticionApi('prueba'), error => error.message.includes(mensaje));
    }
    entorno.fetch = async () => {throw new Error('Sin conexión');};
    await assert.rejects(entorno.peticionApi('prueba'), /Sin conexión/);
    entorno.fetch = async () => new Response('{"datos":[1]}');
    assert.deepEqual((await (await entorno.peticionApi('prueba')).json()).datos,[1]);
    assert.deepEqual(await entorno.obtenerDatosApi('prueba'),[1]);
    entorno.window.csrfToken='tokenFicticio';
    entorno.fetch=async (url,opciones)=>{
        assert.equal(opciones.method,'POST');
        assert.equal(opciones.headers['X-CSRF-Token'],'tokenFicticio');
        assert.deepEqual(JSON.parse(opciones.body),{dato:'prueba'});
        return new Response('{"datos":{"mensaje":"ok"}}');
    };
    assert.equal(await entorno.enviarDatosApi('prueba','POST',{dato:'prueba'}),true);
    let redireccion='';
    entorno.document.body.dataset.rol='tecnico';
    entorno.window.location.replace=valor=>{redireccion=valor;};
    entorno.fetch=async()=>new Response('{"mensaje":"Sin sesión"}',{status:401});
    await assert.rejects(entorno.peticionApi('prueba'),/Sin sesión/);
    assert.equal(redireccion,'../index.html?error=sin_sesion');
    let aviso='';
    entorno.alert=mensaje=>{aviso=mensaje;};
    entorno.mostrarErrorApi({estado:401,message:'Sin sesión'});
    assert.equal(aviso,'');
    entorno.mostrarErrorApi({estado:403,metodo:'GET',message:'Sin permiso'});
    assert.equal(aviso,'');
    entorno.mostrarErrorApi({estado:422,metodo:'POST',message:'Existen campos vacios'});
    assert.equal(aviso,'Existen campos vacios');
    console.log('15 comprobaciones de transporte y helpers correctas');
})().catch(error => {console.error(error); process.exitCode=1;});
