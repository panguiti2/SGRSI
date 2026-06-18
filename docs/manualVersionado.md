# GESTIÓN DE CONFIGURACIÓN Y VERSIONADO DE SOFTWARE

**Documentación Oficial de Proyecto**

*Fecha de vigencia: Junio 2026*

# MANUAL OPERATIVO PARA EL DESARROLLADOR

## Procedimiento Obligatorio: Commits, Versionado y Fusión de Código

El presente manual describe el procedimiento técnico y de flujo de trabajo que debe seguirse de forma obligatoria para el registro de confirmaciones (*commits*), el cálculo del número de versión exacto, la revisión de código por pares y la fusión (*merge*) en GitHub.

---

## 1. FLUJO GENERAL DE TRABAJO

Se establece el siguiente flujo obligatorio:

1. Desarrollo en rama asignada
2. Registro de commits bajo el estándar definido
3. Publicación de cambios en el repositorio remoto
4. Creación de Pull Request hacia `main`
5. Revisión por pares obligatoria
6. Generación de etiqueta de versión
7. Fusión final del código

---

## 2. GESTIÓN DE RAMAS

Se establece la siguiente política de ramas:

- La rama `main` representa el estado estable del sistema.
- La rama `main` se encuentra protegida contra modificaciones directas.
- El trabajo de cada desarrollador debe realizarse exclusivamente en la rama asignada.

### Prohibiciones

- Se prohíben los commits directos sobre `main`.
- Se prohíbe mezclar múltiples módulos en una misma rama.

La rama fija de un integrante puede reutilizarse para distintos módulos durante el proyecto, pero solo puede contener el trabajo de un módulo por vez. Después de fusionar un Pull Request, la rama debe sincronizarse nuevamente con `main` antes de comenzar el siguiente módulo.

### Módulos del proyecto

| Número | Módulo | Contenido principal |
|---|---|---|
| `0` | Funciones generales | Usuarios, notificaciones, estructura, estilos y documentación |
| `1` | Recursos | Inventario, préstamos, devoluciones, historiales y registros de uso |
| `2` | Mesa de ayuda | Incidencias, tickets, diagnósticos y soluciones |
| `3` | Solicitudes | Solicitudes de servicio y seguimiento |
| `4` | Toma de decisiones | Métricas, reportes y exportaciones |

---

## 3. PROCEDIMIENTO AL DESARROLLAR FUNCIONALIDADES

### PASO 0: Posicionamiento Correcto en Rama Asignada y Sincronización con Main

Dado que cada integrante dispone de una rama de desarrollo fija y asignada de forma permanente durante todo el proyecto, es obligatorio realizar la sincronización del entorno local antes de iniciar cualquier jornada de trabajo, implementar funcionalidades o registrar confirmaciones (commits). Este proceso previene desviaciones críticas de código respecto al tronco común.

Se debe ejecutar la siguiente secuencia de comandos en la terminal local:

```bash
# 1. Actualizar el índice local con el estado del servidor remoto
git fetch origin

# 2. Conmutar el entorno de trabajo hacia la rama fija asignada
git checkout <nombre_rama_integrante>

# 3. Integrar las últimas actualizaciones de la rama principal en la rama asignada
git pull origin main
```

**Usar el código con precaución.**

#### Reglas de control de entorno

**Verificación de la rama activa:** Es obligatorio ejecutar el comando `git branch` para confirmar que el puntero (`*`) apunte a la rama fija correspondiente. Queda estrictamente prohibido realizar modificaciones en el código fuente si el entorno se encuentra posicionado en la rama `main`.

**Actualización periódica preventiva:** La integración mediante `git pull origin main` debe realizarse al menos una vez al día. Este paso no debe postergarse hasta la finalización del módulo, con el fin de detectar y resolver de manera temprana cualquier conflicto de fusión (*merge conflict*) derivado del trabajo concurrente.

---

### PASO 1: Buenas Prácticas al Registrar Commits

Cada *commit* realizado en una rama suma de manera directa al contador de la versión final.

Se establecen las siguientes reglas estrictas:

**Mensajes claros y normalizados:** Cada commit debe describir la acción en minúsculas y utilizar prefijos semánticos (ej. `feat: agregar boton de descarga`, `fix: corregir respuesta del servidor`). Queda estrictamente prohibido el uso de textos como `"cambios"`, `"test"`, `"ajustes"` o `"asd"`.

**Frecuencia lógica:** Los commits deben realizarse por cada avance funcional o unidad de trabajo lógica. No se debe acumular el trabajo de varios días en un único commit masivo.

**Aislamiento de alcance:** Si se detecta un error o bug que no pertenece al módulo asignado, no debe corregirse en la rama actual. Corresponde notificarlo al líder técnico para evitar alteraciones artificiales en el contador de commits.

---

### PASO 2: Envío a Revisión por Pares (Pull Request)

Una vez concluido el desarrollo del módulo funcional y verificado de forma local que cumple con los criterios de aceptación, se deben seguir los siguientes pasos:

1. Subir todos los commits pendientes desde el entorno local hacia la rama remota en GitHub:

```bash
git push origin tu-nombre-rama
```

2. Acceder a la interfaz web de GitHub de la organización y abrir un **Pull Request (PR)** desde la rama actual hacia la rama principal `main`.

3. En el panel lateral derecho del PR, dentro de la sección **Reviewers**, asignar de manera obligatoria al integrante del equipo designado como revisor de código.

4. **BLOQUEO DE SEGURIDAD:** Queda prohibido forzar o ejecutar el Merge en este punto. Se debe aguardar la auditoría del revisor asignado.

---

### PASO 3: Atender Comentarios y Feedback del Revisor

El revisor asignado inspeccionará los cambios en la pestaña *Files changed* de GitHub.

Si el revisor solicita correcciones, optimizaciones o refactorizaciones, los cambios requeridos deben realizarse en el entorno de desarrollo local.

Los nuevos commits de corrección deben registrarse y subirse utilizando el comando estándar (`git push origin tu-nombre-rama`). El contador de commits en el PR aumentará de forma natural, lo cual constituye el comportamiento esperado.

Una vez que el código cumpla con los estándares técnicos, el revisor registrará su veredicto mediante el botón **Approve** (Aprobar).

---

### PASO 4: Generar la Etiqueta de Versión Institucional (Tag)

El formato de versión obligatorio se rige por la nomenclatura `vVersiónPrincipal.Módulo.CantCommits`.

Una vez que el Pull Request se encuentre formalmente **APROBADO**, pero **ANTES de ejecutar la fusión (Merge)**, se debe abrir la terminal en el equipo local sobre la rama de trabajo y ejecutar de manera exacta el siguiente bloque de comandos:

#### Definiciones

- **Versión Principal:** etapa global del sistema. Se utiliza `0` durante el desarrollo inicial y `1` a partir de la primera versión estable aprobada.
- **Módulo:** identificador del componente desarrollado.
- **Cantidad de Commits:** número de commits exclusivos de la rama respecto a `main`.

```bash
# 1. Sincronizar la información del historial con el servidor remoto
git fetch origin

# 2. Configurar las variables de la entrega (reemplazar con los números asignados al proyecto)
VERSION_PRINCIPAL="1"
MODULO="3"

# 3. El sistema calcula automáticamente los commits exclusivos en esta rama aislada
COMMITS=$(git rev-list --count origin/main..HEAD)
TAG_NAME="v$VERSION_PRINCIPAL.$MODULO.$COMMITS"

# 4. Registrar la etiqueta anotada en el historial local y desplegarla en GitHub
git tag -a "$TAG_NAME" -m "Módulo $MODULO aprobado por revisor con $COMMITS commits propios."
git push origin "$TAG_NAME"
```

---

### PASO 5: Ejecución Correcta del Merge

Con la etiqueta de versión debidamente registrada y visible en la pestaña *Tags* de GitHub, se encuentra habilitada la integración del código al tronco común.

1. Acceder a la interfaz web del Pull Request en GitHub.

2. Desplegar las opciones del botón verde de fusión y seleccionar obligatoriamente una de las siguientes dos opciones:

   - **"Create a merge commit"**
   - **"Rebase and merge"**

3. **PROHIBICIÓN EXPLÍCITA:** Queda estrictamente denegado el uso de la opción **"Squash and merge"**. Este mecanismo comprime y destruye los commits individuales, convirtiéndolos en un único registro en la rama principal, lo cual invalida por completo la coherencia del número de versión (`CantCommits`) registrado en el paso anterior.

---
