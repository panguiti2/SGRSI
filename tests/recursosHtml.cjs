const fs=require('node:fs');
const path=require('node:path');
const assert=require('node:assert/strict');
const raiz=path.resolve('migracion_api/public');
let paginas=0,referencias=0;
function revisar(carpeta) {
    for(const entrada of fs.readdirSync(carpeta,{withFileTypes:true})) {
        const archivo=path.join(carpeta,entrada.name);
        if(entrada.isDirectory()) {revisar(archivo);continue;}
        if(!archivo.endsWith('.html')) continue;
        paginas++;
        const html=fs.readFileSync(archivo,'utf8');
        assert.equal((html.match(/src="[^"\n]*\/api.js"/g)||[]).length,1,archivo);
        if (html.includes('data-rol=')) {
            assert.equal((html.match(/src="[^"\n]*\/navegacion.js"/g)||[]).length,1,archivo);
            assert.ok(html.indexOf('/navegacion.js')<html.indexOf('/formulario.js'),archivo+' orden de cierre de sesión');
        }
        for(const [,url] of html.matchAll(/(?:src|href)="([^"]*)"/g)) {
            if(!url || /^(?:[a-z]+:|#|\/\/)/i.test(url)) continue;
            assert.ok(fs.existsSync(path.resolve(path.dirname(archivo),url.split('?')[0])),`${archivo}: ${url}`);
            referencias++;
        }
    }
}
revisar(raiz);
console.log(`${paginas} páginas y ${referencias} referencias locales correctas`);
