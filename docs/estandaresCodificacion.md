# Estándares de Codificación

**Filosofía base:** *Código Limpio* — Robert C. Martin.

---

## 1. Reglas de Nomenclatura

El nombre de una variable, función o clase debe responder siempre a por qué existe, qué hace y cómo se usa.

**Revelar intenciones:** Los nombres deben ser concretos y describir el valor o la función que representan. Tanto en variables como en clases o selectores CSS, el nombre debe indicar el concepto que representa.

**Pronunciables y fáciles de buscar:** Deben emplearse palabras que puedan pronunciarse y buscarse fácilmente en el IDE.

**Convención de nomenclatura — lowerCamelCase:** Se unen varias palabras sin espacios, capitalizando la primera letra de cada palabra subsiguiente. La primera palabra va en minúscula.

**Cero codificaciones ni prefijos:** No se utiliza la notación húngara ni prefijos como `m_` para variables.

**Regla gramatical:** Las clases deben ser sustantivos (ej. `Customer`), nunca verbos. Los métodos deben ser verbos o frases verbales (ej. `postPayment`).

---

## 2. Estilo y Formato

El formato de código es comunicación, y la comunicación es el principal pilar del equipo.

**Sangrado estricto:** El sangrado hace visible la jerarquía de los ámbitos y debe aplicarse sistemáticamente. Las etiquetas hijas deben sangrarse un nivel a la derecha de la etiqueta contenedora.

**La metáfora del periódico:** Los archivos deben leerse de arriba a abajo. El nombre debe funcionar como titular; los conceptos generales y de alto nivel van en la parte superior, y los detalles de bajo nivel se ubican a medida que se desciende por el archivo.

**Densidad y distancia vertical:** Los conceptos estrechamente relacionados (como funciones que se invocan entre sí, o variables y su uso) deben mantenerse verticalmente próximos para evitar que el lector deba saltar por todo el archivo.

---

## 3. Comentarios

Un comentario es siempre un síntoma de incapacidad para expresarse claramente a través del código.

**El código es la verdad:** No debe comentarse el código incorrecto; debe reescribirse. Solo el código puede contar lo que hace.

**Prohibido el código comentado:** Es una práctica inaceptable tanto en lenguajes de programación como en HTML/CSS. El código comentado se estanca, confunde y ensucia. Si no sirve, debe eliminarse; el sistema de control de versiones (Git) se encargará de recordarlo.

**Cero comentarios ruidosos:** No deben incluirse diarios de cambios, historiales de modificaciones ni comentarios que no aporten más que la propia firma del método.

---

## 4. Diseño de Funciones y Clases

Los componentes de software deben ser lo más pequeños y enfocados posible.

**Tamaño ultrarreducido:** Las funciones deben ser muy reducidas, con una longitud aproximada de 20 líneas y un nivel de sangrado no mayor a uno o dos. Las clases también deben ser reducidas, midiendo su tamaño por el número de responsabilidades.

**Hacer una sola cosa — Principio SRP:** Las funciones y clases deben hacer una sola cosa, hacerlo bien y que sea lo único que hagan. Las clases deben tener un único motivo para cambiar.

**Mínimos argumentos:** El número ideal de argumentos para una función es cero, seguido de uno o dos. Deben evitarse los parámetros booleanos (argumentos de indicador), ya que declaran abiertamente que la función hace más de una cosa.

**Sin efectos secundarios:** Las funciones no deben realizar acciones ocultas (como inicializar sesiones) que no estén descritas explícitamente en su nombre.

---

## 5. Control de Errores y Nulos

El control de errores es importante, pero no debe oscurecer la lógica del programa.

**Usar excepciones, no códigos de error:** Devolver códigos de error obliga a procesarlos inmediatamente y genera estructuras anidadas. Siempre debe lanzarse una excepción.

**Prohibido devolver `null`:** Devolver `null` obliga a llenar el sistema de comprobaciones y propicia el error `NullPointerException`. Si no hay datos, deben devolverse excepciones u *objetos de caso especial* (como listas vacías mediante `Collections.emptyList()`).

**Prohibido pasar `null`:** Pasar un valor nulo a un método es aún peor que devolverlo. Debe evitarse por completo.

---

## 6. No Repetirse — Principio DRY

La duplicación puede ser la raíz de todos los problemas del software.

Los fragmentos de lógica que se repiten deben aislarse en programación, y los estilos visuales redundantes deben agruparse en CSS. Al elevar el nivel de abstracción y aislar duplicados se reduce el tamaño del código, el riesgo de errores y se facilitan las futuras modificaciones.

---

## 7. Pruebas de Unidad (Testing)

El código de prueba es tan importante como el de producción y debe mantenerse limpio.

Sin pruebas limpias se pierde la flexibilidad del sistema, porque aparece el miedo a realizar cambios.

Las pruebas deben seguir las reglas **F.I.R.S.T.**:

| Sigla | Atributo | Descripción |
|---|---|---|
| **F** | Fast *(Rápidas)* | Las pruebas deben ejecutarse rápidamente. |
| **I** | Independent *(Independientes)* | Las pruebas no deben depender entre sí. |
| **R** | Repeatable *(Repetibles)* | Deben poder ejecutarse en cualquier entorno. |
| **S** | Self-Validating *(Autovalidables)* | El resultado debe ser un booleano claro: pasa o falla. |
| **T** | Timely *(Puntuales)* | Deben escribirse antes que el código de producción. |

---

## Regla de Oro del Equipo: La Regla del Boy Scout

> *"Dejar el campamento más limpio de lo que se ha encontrado."*
> — Martin (2009)

El código tiende a corromperse con el tiempo. Si cada integrante entrega un archivo un poco más limpio de como lo recibió —cambiando un nombre por uno mejor, eliminando un duplicado o dividiendo una función extensa— el proyecto nunca se deteriorará.

**Referencias Bibliográficas Martin, R. C. (2009). Código limpio: Manual de estilo para el desarrollo ágil de software (J. L. Gómez Celador, Trad.). epublibre.**