/**
 * Referencias DOM usadas por las pantallas del administrador.
 * Las propiedades pueden ser null cuando la pantalla actual no contiene ese elemento.
 */
const administradorDOM = {
    usuarios: {
        botonAbrirAlta: document.getElementById("btnAltaUsuario"),
        botonCerrarAlta: document.getElementById("btnCerrarAltaUsuario"),
        dialogoAlta: document.querySelector(".dialogAltaUsuario"),
        formularioAlta: document.getElementById("formularioAltaUsuario"),
        tabla: document.getElementById("cuerpoTablaUsuarios"),
        cedula: document.getElementById("cedula"),
        nombre: document.getElementById("nombre"),
        apellido: document.getElementById("apellido"),
        cargo: document.getElementById("cargo"),
    },
    inventario: {
        botonAbrirAlta: document.getElementById("btnAltaDispositivo"),
        botonCerrarAlta: document.getElementById("btnCerrarAltaDispositivo"),
        dialogoAlta: document.querySelector(".dialogAltaDispositivo"),
        formularioAlta: document.getElementById("formularioAltaDispositivo"),
        tabla: document.getElementById("cuerpoTablaDispositivos"),
        id: document.getElementById("id"),
        marca: document.getElementById("marca"),
        numeroSerie: document.getElementById("numeroSerie"),
        recurso: document.getElementById("recurso"),
        laboratorio: document.getElementById("laboratorio"),
        taller: document.getElementById("taller"),
        modificaciones: document.getElementById("modificaciones"),
        estado: document.getElementById("estado"),
        fecha: document.getElementById("fecha"),
    },
    filtros: {
        formularios: document.querySelectorAll("form"),
        tablas: document.querySelectorAll("table"),
        botones: document.querySelectorAll("button"),
    },
};

