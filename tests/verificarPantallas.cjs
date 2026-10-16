const fs = require('node:fs');
const path = require('node:path');
const {execFileSync} = require('node:child_process');
const assert = require('node:assert/strict');
const edge = 'C:/Program Files (x86)/Microsoft/Edge/Application/msedge.exe';
const salida = path.resolve('tmp/pantallas-api');
fs.mkdirSync(salida,{recursive:true});
const paginas = {administrador:['inicio','usuarios','inventario','incidencias','metricas'],tecnico:['inicio','inventario','incidencias','solicitudes','prestamos','registrosUso','metricas'],solicitante:['inicio','incidencias','solicitudes','registroUso']};
let total=0;
function capturar(nombre,url,ancho=1366,alto=900) {
    const inicio=Date.now();
    const png=path.join(salida,nombre+'.png');
    let dom;
    try {
        dom=execFileSync(edge,['--headless=new','--disable-gpu','--disable-background-networking','--no-first-run','--no-default-browser-check','--user-data-dir='+path.join(salida,'perfil-'+nombre+'-'+inicio),'--window-size='+ancho+','+alto,'--virtual-time-budget=5000','--screenshot='+png,'--dump-dom',url],{encoding:'utf8',windowsHide:true,timeout:45000,maxBuffer:4*1024*1024,stdio:['ignore','pipe','pipe']});
    } catch(error) {
        // Algunas versiones de Edge no cierran tras producir ambos resultados.
        const completo=error.stdout?.includes('</html>') || (nombre==='der' && error.stdout?.includes('</svg>'));
        if(error.code!=='ETIMEDOUT' || !completo || !fs.existsSync(png) || fs.statSync(png).mtimeMs<inicio) throw error;
        dom=error.stdout;
        console.log('Edge produjo DOM y PNG, pero no cerró en el plazo: '+nombre);
    }
    assert.ok(!/ERR_CONNECTION_REFUSED|ERR_TOO_MANY_REDIRECTS/.test(dom),nombre);
    if(nombre==='login') assert.match(dom,/id="formLogin"/);
    else if(!nombre.startsWith('der')) {
        assert.match(dom,/data-rol="/);
        assert.match(dom,/data-cerrar-sesion/);
        if (nombre.endsWith('-inicio')) assert.match(dom,/aria-label="Accesos a los módulos"/);
        if (nombre.startsWith('tecnico-')) assert.match(dom,/href="registrosUso.html"/);
        assert.ok(!/Error interno del servidor|No tiene autorización para acceder a este panel/.test(dom),nombre);
        if(/usuarios|inventario|incidencias|solicitudes|prestamos|registrosUso/.test(nombre)) assert.match(dom,/<tbody[^>]*>[\s\S]*?<tr/,nombre+' no cargó tabla');
        if(nombre.includes('dialogo')) assert.match(dom,/<dialog[^>]*\bopen\b/,nombre+' no abrió el formulario');
    }
    total++;
    console.log('Correcta: '+nombre);
}
if (!['--der','--dialogos','--compacta','--navegacion'].includes(process.argv[2])) {
    capturar('login','http://127.0.0.1:3107/index.html');
    for(const [rol,secciones] of Object.entries(paginas)) for(const pagina of secciones) capturar(rol+'-'+pagina,'http://127.0.0.1:3107/_sesionPrueba?rol='+rol+'&pagina='+pagina);
    capturar('solicitante-incidencias-compacta','http://127.0.0.1:3107/_sesionPrueba?rol=solicitante&pagina=incidencias',800);
}
if (process.argv[2]==='--navegacion') {
    for (const rol of Object.keys(paginas)) capturar(rol+'-inicio','http://127.0.0.1:3107/_sesionPrueba?rol='+rol+'&pagina=inicio');
    capturar('tecnico-prestamos','http://127.0.0.1:3107/_sesionPrueba?rol=tecnico&pagina=prestamos');
} else if (process.argv[2]==='--compacta') {
    capturar('solicitante-incidencias-compacta','http://127.0.0.1:3107/_sesionPrueba?rol=solicitante&pagina=incidencias',800);
} else if (process.argv[2]==='--dialogos') {
    for(const [rol,pagina] of [['administrador','usuarios'],['administrador','inventario'],['tecnico','prestamos'],['tecnico','incidencias'],['tecnico','solicitudes']]) capturar(rol+'-'+pagina+'-dialogo','http://127.0.0.1:3107/_sesionPrueba?rol='+rol+'&pagina='+pagina+'&dialogo=prueba');
} else capturar('der','http://127.0.0.1:3107/_derPrueba.svg',1660,1500);
console.log(total+' pantallas capturadas y verificadas; inspeccionar PNG en '+salida);
