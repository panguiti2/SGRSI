const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');

for (const raiz of ['proyecto']) {
    const entorno = { document: { forms: [] }, window: { location: { search: '' } }, URLSearchParams };
    vm.createContext(entorno);
    vm.runInContext(fs.readFileSync(`${raiz}/public/assets/js/validacionCampos.js`, 'utf8'), entorno);
    const casos = [
        { required: true, value: '', esperado: 'Existen campos vacios' },
        { required: true, value: '   ', esperado: 'Existen campos vacios' },
        { required: true, value: '0', esperado: '' },
        { required: false, value: '', esperado: '' },
        { required: true, disabled: true, value: '', esperado: '' },
        { required: true, type: 'radio', value: 'SI', validity: { valueMissing: true }, esperado: 'Existen campos vacios' },
        { required: true, type: 'radio', value: 'SI', validity: { valueMissing: false }, esperado: '' }
    ];
    for (const caso of casos) {
        caso.setCustomValidity = mensaje => { caso.mensaje = mensaje; };
        entorno.actualizarValidacionCampos({ elements: [caso] });
        assert.equal(caso.mensaje, caso.esperado);
    }
    const campo = { required: true, value: '', setCustomValidity(mensaje) { this.mensaje = mensaje; } };
    entorno.actualizarValidacionCampos({ elements: [campo] });
    campo.value = 'completo';
    entorno.actualizarValidacionCampos({ elements: [campo] });
    assert.equal(campo.mensaje, '');
    console.log(`${raiz}: 8 comprobaciones correctas.`);
}
