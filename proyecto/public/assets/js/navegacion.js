/** Presentación común: los permisos siempre se verifican en la API. */
const menusPorRol = {
    administrador: [['inicio','Inicio'],['usuarios','Usuarios'],['incidencias','Incidencias'],['metricas','Métricas'],['inventario','Inventario']],
    tecnico: [['inicio','Inicio'],['solicitudes','Solicitudes'],['incidencias','Incidencias'],['metricas','Métricas'],['inventario','Inventario'],['prestamos','Préstamos'],['registrosUso','Registros de uso']],
    solicitante: [['inicio','Inicio'],['solicitudes','Solicitudes'],['incidencias','Incidencias'],['registroUso','Registro de uso']]
};
const listaNavegacion = document.querySelector('#menuPrincipal .navbar-nav');
const enlacesNavegacion = menusPorRol[document.body.dataset.rol] || [];
if (listaNavegacion) {
    listaNavegacion.replaceChildren();
    for (const [seccion, nombre] of enlacesNavegacion) {
        const item = document.createElement('li');
        item.className = 'nav-item';
        const enlace = document.createElement('a');
        enlace.href = seccion + '.html';
        enlace.className = 'nav-link';
        enlace.textContent = nombre;
        if (seccion === document.body.dataset.seccion) {
            enlace.classList.add('active');
            enlace.setAttribute('aria-current', 'page');
        }
        item.appendChild(enlace);
        listaNavegacion.appendChild(item);
    }
    const item = document.createElement('li');
    item.className = 'nav-item';
    const salir = document.createElement('a');
    salir.href = '#';
    salir.className = 'nav-link';
    salir.textContent = 'Cerrar sesión';
    salir.setAttribute('data-cerrar-sesion', '');
    item.appendChild(salir);
    listaNavegacion.appendChild(item);
}

if (document.body.dataset.seccion === 'inicio') {
    const zona = document.querySelector('main');
    const ayuda = document.createElement('p');
    ayuda.textContent = 'Selecciona un módulo para comenzar. Las operaciones disponibles corresponden a tu rol.';
    zona.appendChild(ayuda);
    const accesos = document.createElement('section');
    accesos.className = 'row g-3 mt-2';
    accesos.setAttribute('aria-label', 'Accesos a los módulos');
    for (const [seccion, nombre] of enlacesNavegacion.filter(([seccion]) => seccion !== 'inicio')) {
        const columna = document.createElement('article');
        columna.className = 'col-md-6 col-xl-4';
        const tarjeta = document.createElement('section');
        tarjeta.className = 'sgrsi-section h-100';
        const titulo = document.createElement('h2');
        titulo.className = 'h5';
        titulo.textContent = nombre;
        const enlace = document.createElement('a');
        enlace.href = seccion + '.html';
        enlace.className = 'btn btn-primary mt-2';
        enlace.textContent = 'Abrir ' + nombre.toLowerCase();
        tarjeta.append(titulo, enlace);
        columna.appendChild(tarjeta);
        accesos.appendChild(columna);
    }
    zona.appendChild(accesos);
}
