/**
 * Actualiza el selector de dispositivos de los formularios del solicitante.
 * La colección llega desde PHP en window.dispositivosFormulario.
 * @file
 */

const selectorLaboratorio = document.getElementById("idLaboratorio");
const selectorDispositivo = document.getElementById("numeroDispositivo");

if (selectorLaboratorio && selectorDispositivo && Array.isArray(window.dispositivosFormulario)) {
    selectorLaboratorio.addEventListener("change", () => {
        const dispositivos = window.dispositivosFormulario.filter((dispositivo) => (
            dispositivo.idLaboratorio === selectorLaboratorio.value
        ));

        selectorDispositivo.replaceChildren();
        selectorDispositivo.disabled = dispositivos.length === 0;

        const opcionInicial = document.createElement("option");
        opcionInicial.value = "";
        opcionInicial.selected = true;
        opcionInicial.disabled = true;
        opcionInicial.textContent = dispositivos.length === 0
            ? "No hay dispositivos registrados"
            : "Seleccione el dispositivo";
        selectorDispositivo.appendChild(opcionInicial);

        dispositivos.forEach((dispositivo) => {
            const opcion = document.createElement("option");
            opcion.value = dispositivo.numeroDispositivo;
            opcion.textContent = dispositivo.numeroDispositivo;
            selectorDispositivo.appendChild(opcion);
        });
    });
}
