/** Busca y ordena datos que la pantalla ya recibió del servidor. */
function textoBusqueda(valor) {
    return String(valor).normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
}

function coincideBusqueda(textos, termino, columna) {
    let texto = textos.join(' ');
    if (columna !== '') texto = textos[Number(columna)] || '';
    return textoBusqueda(texto).includes(textoBusqueda(termino));
}

function compararValores(primero, segundo) {
    return primero.localeCompare(segundo, 'es', { numeric: true, sensitivity: 'base' });
}

function aplicarControlesTabla(formulario) {
    const tabla = document.getElementById(formulario.dataset.tabla);
    const cuerpo = tabla.tBodies[0];
    const filas = Array.from(cuerpo.rows);
    const columna = formulario.elements.orden.value;

    // Orden original: el mismo en que llegaron los datos desde la API.
    filas.sort(function (primera, segunda) {
        if (primera.cells.length !== tabla.tHead.rows[0].cells.length || segunda.cells.length !== tabla.tHead.rows[0].cells.length) return 0;
        if (columna === '') return Number(primera.dataset.posicion) - Number(segunda.dataset.posicion);
        const comparacion = compararValores(primera.cells[columna].textContent, segunda.cells[columna].textContent);
        if (formulario.elements.direccion.value === 'desc') return -comparacion;
        return comparacion;
    });

    let visibles = 0;
    for (const fila of filas) {
        // Las filas de aviso no son registros y no se cuentan ni se buscan.
        if (fila.cells.length !== tabla.tHead.rows[0].cells.length) continue;
        const textos = [];
        for (let i = 0; i < fila.cells.length; i++) {
            const titulo = tabla.tHead.rows[0].cells[i].textContent.trim();
            textos.push(/^(acciones|operaciones)$/i.test(titulo) ? '' : fila.cells[i].textContent);
        }
        fila.hidden = !coincideBusqueda(textos, formulario.elements.busqueda.value, formulario.elements.columna.value);
        if (!fila.hidden) visibles++;
        cuerpo.appendChild(fila);
    }
    const resultado = document.querySelector('[data-resultado="' + tabla.id + '"]');
    resultado.textContent = visibles === 0 ? 'No se encontraron registros.' : visibles + ' registros';
}

/** Cada módulo llama a esta función al terminar de cargar las filas. */
function actualizarControlesTabla(idTabla) {
    const tabla = document.getElementById(idTabla);
    const formulario = document.querySelector('form[data-tabla="' + idTabla + '"]');
    if (!tabla || !formulario) return;
    for (let i = 0; i < tabla.tBodies[0].rows.length; i++) {
        tabla.tBodies[0].rows[i].dataset.posicion = i;
    }
    aplicarControlesTabla(formulario);
}

for (const formulario of document.querySelectorAll('form[data-tabla]')) {
    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        aplicarControlesTabla(formulario);
    });
    formulario.elements.ordenar.addEventListener('click', function () {
        aplicarControlesTabla(formulario);
    });
    formulario.elements.limpiar.addEventListener('click', function () {
        formulario.reset();
        aplicarControlesTabla(formulario);
    });
    actualizarControlesTabla(formulario.dataset.tabla);
}
