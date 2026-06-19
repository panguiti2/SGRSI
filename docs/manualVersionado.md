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
6. Fusión final del código
7. Sincronización de la rama asignada con `main`
8. Generación de la etiqueta sobre el commit funcional

---

## 2. GESTIÓN DE RAMAS

Se establece la siguiente política de ramas:

- La rama `main` representa el estado estable del sistema.
- La rama `main` se encuentra protegida contra modificaciones directas.
- El trabajo de cada desarrollador debe realizarse exclusivamente en la rama asignada.

### Prohibiciones

- Se prohíben los commits directos sobre `main`.
- Se prohíbe mezclar cambios nuevos de múltiples módulos en un mismo commit o Pull Request.

La rama fija de un integrante puede reutilizarse para distintos módulos durante el proyecto. Su historial conservará los merges anteriores, pero cada nuevo Pull Request debe contener trabajo de un solo módulo. Después de fusionarlo, la rama debe sincronizarse nuevamente con `main` antes de comenzar el siguiente issue.

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

Cada commit funcional final aprobado y etiquetado aumenta el contador acumulado del módulo al que pertenece. Los commits intermedios de corrección y los commits de merge creados por GitHub no aumentan este contador por sí solos.

Se establecen las siguientes reglas estrictas:

**Mensajes claros y normalizados:** Cada commit debe describir la acción en minúsculas y utilizar prefijos semánticos (ej. `feat: agregar boton de descarga`, `fix: corregir respuesta del servidor`). Queda estrictamente prohibido el uso de textos como `"cambios"`, `"test"`, `"ajustes"` o `"asd"`.

**Frecuencia lógica:** Los commits deben realizarse por cada avance funcional o unidad de trabajo lógica. No se debe acumular el trabajo de varios días en un único commit masivo.

**Aislamiento de alcance:** Si se detecta un error o bug que no pertenece al módulo asignado, no debe corregirse en la rama actual. Corresponde notificarlo al líder técnico para evitar alteraciones artificiales en el contador de commits.

---

### PASO 2: Envío a Revisión por Pares (Pull Request)

Una vez concluido el desarrollo del issue y verificado de forma local que cumple con los criterios de aceptación, se deben seguir los siguientes pasos:

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

Los nuevos commits de corrección deben registrarse y subirse utilizando el comando estándar (`git push origin tu-nombre-rama`). El contador visual de commits del PR puede aumentar, pero no se utiliza para calcular directamente el tercer número de la etiqueta.

Una vez que el código cumpla con los estándares técnicos, el revisor registrará su veredicto mediante el botón **Approve** (Aprobar).

---

### PASO 4: Aprobar y Fusionar el Pull Request

Una vez que el Pull Request se encuentre formalmente aprobado, se encuentra habilitada su integración a `main`.

1. Acceder al Pull Request en GitHub.

2. Verificar que el revisor haya registrado la aprobación.

3. Seleccionar obligatoriamente la opción **Create a merge commit**.

4. Confirmar que el Pull Request y su issue asociado queden cerrados.

#### Prohibición de Squash

Queda prohibido utilizar **Squash and merge**, porque comprime los commits funcionales e impide identificar correctamente el commit que debe recibir la etiqueta.

---

### PASO 5: Sincronizar la Rama Después del Merge

Después de completar el merge, se debe actualizar la rama fija antes de comenzar otro issue:

```bash
git fetch origin
git checkout <nombre_rama_integrante>
git pull origin main
git status
git log --oneline -5
```

El historial debe mostrar primero el commit de merge y, debajo, el commit funcional realizado en la rama. La etiqueta debe apuntar al commit funcional, no al commit de merge.

---

### PASO 6: Generar la Etiqueta de Versión Institucional (Tag)

El formato obligatorio es `vVersiónPrincipal.Módulo.CantidadAcumulada`.

#### Definiciones

- **Versión Principal:** se utiliza `1` por corresponder a la primera entrega estable del proyecto.
- **Módulo:** identificador del componente desarrollado.
- **Cantidad Acumulada:** número consecutivo de commits funcionales finales etiquetados dentro del módulo. Cada nueva entrega aprobada del módulo aumenta este valor en uno.

El contador no se calcula con `git rev-list origin/main..HEAD`, porque la rama ya fue fusionada y sincronizada. En su lugar, se revisan las etiquetas existentes del módulo y se utiliza el siguiente número disponible.

#### Procedimiento

1. Consultar las etiquetas existentes del módulo:

```bash
git tag --list "v1.<modulo>.*" --sort=version:refname
```

2. Identificar el último número utilizado y aumentarlo en uno. Si no existe ninguna etiqueta para el módulo, comenzar en `1`.

3. Identificar en `git log --oneline -5` el hash del commit funcional ubicado debajo del merge.

4. Crear la etiqueta anotada indicando explícitamente ese hash:

```bash
git tag -a v1.<modulo>.<siguiente_numero> <hash_commit_funcional> -m "Versión 1.<modulo>.<siguiente_numero> - <descripción breve>"
git push origin v1.<modulo>.<siguiente_numero>
git tag
```

#### Ejemplo

Si el último commit versionado de Recursos tiene la etiqueta `v1.1.3`, el siguiente commit aprobado del mismo módulo recibe `v1.1.4`:

```bash
git tag -a v1.1.4 757c082 -m "Versión 1.1.4 - inventario técnico"
git push origin v1.1.4
```

La etiqueta histórica `v0.0.3` pertenece a una etapa anterior a este procedimiento. No debe utilizarse como referencia para nuevas versiones ni modificarse después de haber sido publicada.

---

### PASO 7: Verificación Final

Después de publicar la etiqueta, se debe comprobar:

```bash
git status
git log --oneline -5
git tag
```

La rama debe estar sincronizada con `main`, el commit funcional debe conservarse en el historial y la etiqueta debe aparecer en GitHub. Las etiquetas publicadas no se eliminan ni se mueven, salvo autorización expresa del responsable del proyecto.

---
