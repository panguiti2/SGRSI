/**
 * Referencias DOM usadas por las pantallas del técnico.
 * Las propiedades pueden ser null cuando la pantalla actual no contiene ese elemento.
 */
const tecnicoDOM = {
    prestamos: {
        botonAbrirRegistro: document.getElementById("btnRegistrarPrestamo"),
        botonCerrarRegistro: document.getElementById("btnCerrarRegistarPrestamo"),
        dialogoRegistro: document.querySelector(".dialogPrestamos"),
        formularioRegistro: document.getElementById("formularioPrestamos"),
        tabla: document.getElementById("cuerpoTablaPrestamos"),
        cedulaSolicitante: document.getElementById("cedulaSolicitante"),
        turno: document.getElementById("turno"),
        nombreSolicitante: document.getElementById("nombreSolicitante"),
        maquina: document.getElementById("maquina"),
        retiro: document.getElementById("retiro"),
        devolucion: document.getElementById("devolucion"),
    },
    registrosUso: {
        tabla: document.getElementById("cuerpoTablaRegistrosUso"),
    },
    filtros: {
        formularios: document.querySelectorAll("form"),
        tablas: document.querySelectorAll("table"),
        botones: document.querySelectorAll("button"),
    },
};

