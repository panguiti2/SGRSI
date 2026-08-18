/**
 * Controla los diálogos de alta y devolución de préstamos.
 * Las tablas y las operaciones se realizan mediante PHP y PDO.
 * @file
 */

const dialogPrestamos = document.querySelector(".dialogPrestamos");
const formularioPrestamos = document.getElementById("formularioPrestamos");
const btnRegistrarPrestamo = document.getElementById("btnRegistrarPrestamo");
const btnCerrarRegistroPrestamo = document.getElementById("btnCerrarRegistarPrestamo");

if (btnRegistrarPrestamo && dialogPrestamos) {
    btnRegistrarPrestamo.addEventListener("click", () => dialogPrestamos.showModal());
}

function cerrarDialogoPrestamo() {
    if (formularioPrestamos) formularioPrestamos.reset();
    if (dialogPrestamos) dialogPrestamos.close();
}

if (btnCerrarRegistroPrestamo) {
    btnCerrarRegistroPrestamo.addEventListener("click", cerrarDialogoPrestamo);
}

if (dialogPrestamos) {
    dialogPrestamos.addEventListener("cancel", () => formularioPrestamos?.reset());
}

const dialogDevolucion = document.querySelector(".dialogDevolucionPrestamo");
const formularioDevolucion = document.getElementById("formularioDevolucionPrestamo");
const idPrestamoDevolucion = document.getElementById("idPrestamoDevolucion");
const btnCerrarDevolucion = document.getElementById("btnCerrarDevolucionPrestamo");

document.querySelectorAll(".btnRegistrarDevolucion").forEach((boton) => {
    boton.addEventListener("click", () => {
        if (idPrestamoDevolucion) idPrestamoDevolucion.value = boton.dataset.id;
        if (dialogDevolucion) dialogDevolucion.showModal();
    });
});

function cerrarDialogoDevolucion() {
    if (formularioDevolucion) formularioDevolucion.reset();
    if (dialogDevolucion) dialogDevolucion.close();
}

if (btnCerrarDevolucion) {
    btnCerrarDevolucion.addEventListener("click", cerrarDialogoDevolucion);
}

if (dialogDevolucion) {
    dialogDevolucion.addEventListener("cancel", () => formularioDevolucion?.reset());
}
