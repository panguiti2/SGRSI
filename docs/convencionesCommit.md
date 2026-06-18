# Convenciones de Commits

Se adoptó una convención de commits inspirada en *Conventional Commits* para facilitar la comprensión del historial de cambios.

## Prefijos Utilizados

| Prefijo | Descripción |
|---|---|
| `feat` | Incorporación de nuevas funcionalidades. |
| `fix` | Corrección de errores. |
| `style` | Cambios relacionados con estilos visuales o formato. |
| `refactor` | Reorganización o mejora del código sin alterar su comportamiento. |
| `chore` | Tareas de mantenimiento o configuración. |
| `docs` | Modificaciones en la documentación del proyecto. |

---

## Resolución de Tareas e Incidencias

Las tareas y funcionalidades del proyecto se gestionan mediante **Issues** en GitHub. Cada Issue representa una actividad específica a desarrollar, permitiendo realizar un seguimiento ordenado del avance del proyecto.

Los commits pueden hacer referencia a los Issues correspondientes mediante su identificador, facilitando la trazabilidad entre las tareas planificadas y los cambios implementados.

### Closing Keywords

Para automatizar la gestión de Issues se utilizan *closing keywords* proporcionadas por GitHub:

| Keyword | Uso |
|---|---|
| `Closes #n` | Cierra el Issue al integrar el cambio. |
| `Fixes #n` | Cierra el Issue al integrar el cambio. |
| `Resolves #n` | Cierra el Issue al integrar el cambio. |

Estas palabras clave permiten cerrar automáticamente un Issue cuando el cambio asociado es integrado al repositorio.

### Ejemplo

```
feat: implemento módulo de inventario. Closes #12
```