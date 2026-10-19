/** Unifica el mensaje de campos requeridos, sin sustituir otras restricciones. */
function actualizarValidacionCampos(formulario) {
    for (const campo of formulario.elements) {
        if (typeof campo.setCustomValidity !== "function") continue;
        const obligatorio = campo.required && !campo.disabled;
        const seleccion = campo.type === "radio" || campo.type === "checkbox";
        const vacio = obligatorio && (seleccion
            ? campo.validity.valueMissing
            : campo.value.trim() === "");
        campo.setCustomValidity(vacio ? "Existen campos vacios" : "");
    }
}

for (const formulario of document.forms) {
    actualizarValidacionCampos(formulario);
    formulario.addEventListener("input", () => actualizarValidacionCampos(formulario));
    formulario.addEventListener("change", () => actualizarValidacionCampos(formulario));
    formulario.addEventListener("click", () => actualizarValidacionCampos(formulario), true);
    formulario.addEventListener("submit", (evento) => {
        actualizarValidacionCampos(formulario);
        if (!formulario.checkValidity()) {
            evento.preventDefault();
            evento.stopImmediatePropagation();
            formulario.reportValidity();
        }
    }, true);
}

if (new URLSearchParams(window.location.search).get("error") === "campos_vacios") {
    alert("Existen campos vacios");
}
