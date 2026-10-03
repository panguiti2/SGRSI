# Comprobación de la migración API

Fecha: 3 de octubre de 2026.

## Referencia utilizada

Se revisaron el JavaScript de usuarios y su controlador en el commit [cc5a5cb del profesor](https://github.com/sideW-Nico/sistema-elvis-tek/commit/cc5a5cb6284ca3a7f6745b80e89a0605f03d60e5). No se utilizaron versiones posteriores ni bibliotecas nuevas.

La coincidencia comprobada es del flujo y de las herramientas utilizadas, no una copia literal de un módulo de usuarios sobre entidades diferentes.

## Comparación del flujo

| Paso del ejemplo | Implementación en SGRSI |
| --- | --- |
| Capturar los campos en un objeto | Los formularios conservan sus campos y se recuperan sus valores. |
| Distinguir alta y modificación | Usuarios e inventario utilizan una variable de edición. |
| Evitar el envío tradicional | El evento submit llama a preventDefault(). |
| Enviar la petición | Se utiliza fetch(), async/await, method, headers y JSON.stringify(). |
| Comprobar la respuesta | Se comprueba respuesta.ok; un rechazo no limpia el formulario. |
| Atender el método en gestionar() | Los controladores seleccionan GET, POST, PUT o DELETE según la sección. |
| Validar y acceder a la base | Los controladores validan y delegan las consultas preparadas a los DAO. |
| Responder en JSON | Se mantiene RespuestaJson en la capa de vista. |
| Actualizar la pantalla | Tras una operación correcta se vuelve a consultar con GET y se crean las filas con createElement(), textContent y appendChild(). |

## Operaciones y reglas conservadas

| Sección | Operaciones |
| --- | --- |
| Usuarios | GET, POST y PUT. Activar/desactivar es un PUT: no se eliminan usuarios. Un único rol por usuario. |
| Inventario | GET, POST, PUT y DELETE. El técnico solo consulta. El alta comienza sin modificaciones. Un equipo relacionado con tickets no se elimina. |
| Préstamos | GET, POST y PUT para devolver. El prestatario no necesita una cuenta. Se conservan por separado la fecha esperada y la real. No se eliminan préstamos. |
| Solicitudes | POST del solicitante, GET según el rol y PUT del técnico para gestionar el estado. No se editan los datos del pedido ni se elimina el ticket. No se exige solución. |
| Incidencias | POST del solicitante, GET según el rol y PUT del técnico para diagnóstico, solución y estado. Para resolver, diagnóstico y solución son obligatorios. |
| Registro de uso | POST del solicitante y GET propio o general para el técnico. Se conserva la fecha enviada desde el formulario. |
| Métricas | GET para administración y técnico. Indicadores calculados desde la base, sin operaciones de escritura. |

Se mantienen las 16 vistas originales de los tres roles, sus menús, formularios, diálogos, columnas, estilos e imágenes. Estas vistas y el login son ahora 17 archivos .html reales en app/vista, sin etiquetas PHP. La carga de tablas y las operaciones correspondientes están conectadas con la API.

Los puntos de entrada públicos .php únicamente comprueban sesión/rol y cargan el archivo HTML. No generan sus opciones ni sus filas. El login conserva su procesamiento PHP tradicional, mientras su vista HTML muestra los errores mediante JavaScript.

## Adaptaciones necesarias

- Las respuestas existentes de SGRSI contienen los resultados dentro de datos; JavaScript recupera contenido.datos.
- Se envía X-CSRF-Token porque el controlador comprueba el token de la sesión.
- Las reglas de cierre y baja lógica sustituyen las eliminaciones que no corresponden a SGRSI.
- Se conserva la selección de laboratorio y sus dispositivos, así como los catálogos de los formularios.
- FormularioController, FormularioDAO y public/api/formulario.php recuperan por GET los catálogos y el token de la sesión. formulario.js rellena las opciones con createElement(), textContent y appendChild(). Los envíos esperan esta carga antes de utilizar el token.
- Se validan fechas y tipos de datos en el servidor. Los errores de conexión de las API devuelven JSON con estado 500, sin mostrar credenciales ni mensajes internos de PDO.
- Los valores booleanos se envían a las consultas como 1 o 0. Las inserciones relacionadas mantienen las transacciones.
- Los diagnósticos enviados se guardan también mientras una incidencia está en proceso.

## Comprobaciones ejecutadas

Se realizaron 51 comprobaciones de DAO y 123 comprobaciones HTTP con MySQL: 174 en total. Se utilizó una base temporal con una copia del esquema y los catálogos locales, sin modificar los registros reales.

Incluyeron altas, consultas, modificaciones, cambios sin diferencias, usuarios duplicados, rollback, filtros por solicitante, dispositivos relacionados, préstamos de personas externas, fechas de devolución, cierres, diagnóstico y solución, fechas de apertura/cierre, registro de uso y métricas.

Las peticiones HTTP comprobaron los métodos, respuestas JSON, sesión, roles, CSRF, datos vacíos o malformados, login de los tres roles, credenciales incorrectas, usuario inactivo, usuario sin rol y bloqueo después del logout. También se verificaron las 16 páginas con y sin cada rol.

Las pruebas de JavaScript comprobaron las 16 vistas, las columnas de 11 tablas, los eventos de 7 botones de filas y los 9 formularios de operaciones. Comprobaron envío JSON, token, actualización GET, mensajes de rechazo y conservación de diálogos. Se revisaron las rutas locales y la sintaxis de PHP y JavaScript.

La comparación de ocho flujos de escritura con el ejemplo confirmó async/await, Fetch, JSON.stringify(), comprobación de respuesta.ok, métodos correspondientes y recarga mediante GET.

Tras retirar el PHP de las vistas, se repitieron las comprobaciones de JavaScript de las 16 pantallas, sus rutas y los 9 formularios. Se comprobó también que los 17 HTML no contienen etiquetas PHP. Una nueva prueba ejecutó 170 comprobaciones HTTP de HTML, sesión, rol, token y catálogos contra MySQL, sin modificar registros.

También se retiraron todos los bloques JavaScript incrustados. Los 17 HTML solo contienen referencias script src a archivos externos, sin contenido ejecutable ni atributos de eventos. Los diálogos de tickets están en sus archivos JS de sección; la selección de alumno está en solicitante/incidencias.js y los mensajes de páginas están en formulario.js. Se comprobaron nuevamente las referencias y la sintaxis, y se probaron los diálogos, campos obligatorios, mensajes y selección de alumno.

## Revisión final de separación y carga

En la revisión final se comprobaron 58 archivos PHP sin errores de sintaxis, los 17 HTML y los 16 JavaScript restantes. Se ejecutaron 216 comprobaciones de referencias locales y carga conjunta de scripts, sin rutas inexistentes ni conflictos de declaraciones. No hay SQL fuera del modelo, PHP dentro de los HTML, scripts incrustados, eventos inline, estilos inline ni uso de localStorage en la migración.

Se retiró administrador.js, que ya no se utilizaba en los módulos API, y sus referencias. Se quitaron las acciones de formulario que apuntaban a procesadores antiguos inexistentes en esta carpeta; los eventos submit siguen enviando las peticiones a la API. Los tamaños de los diálogos se trasladaron a public/assets/css/dialogos.css. Se mantiene Bootstrap, que ya formaba parte del diseño original; no se incorporó ninguna biblioteca nueva.

La prueba de ejecución de los scripts en las 17 pantallas detectó y permitió corregir un fallo en métricas del técnico: usuariosActivos no existe en esa vista. Ahora ese campo se rellena solamente cuando está presente y los demás indicadores se cargan normalmente. También se incorporó captura de excepciones al actualizar tablas y métricas. Las pruebas de carga, columnas y envío interceptado de los nueve formularios pasaron tras la corrección. Esta simulación no es una revisión visual en un navegador.

Las 13 consultas de lectura de datos y catálogos funcionaron contra la base local. Se repitieron 142 comprobaciones HTTP: páginas para los tres roles y sin sesión, API GET, catálogos, token, rechazos de escritura sin token, métodos no permitidos y parámetros GET con tipos incorrectos. Todas pasaron. Esta revisión no insertó, modificó ni eliminó registros de la base real; se usaron sesiones sintéticas en un directorio temporal para comprobar permisos. Las pruebas anteriores con altas y modificaciones sobre una base aislada siguen descritas arriba y no se repitieron en esta revisión.

La fidelidad al ejemplo del profesor es de herramientas, flujo y separación de capas, con las adaptaciones del dominio descritas en este informe; no se afirma igualdad literal de todos los archivos. Login, logout, configuración y estilos siguen compartiendo partes del proyecto original. La carpeta migracion_api no es todavía una distribución independiente del directorio proyecto.

## Límites de verificación

Las comprobaciones funcionales fueron automatizadas. No sustituyen una revisión visual manual de las pantallas en un navegador. Los estilos y la estructura se conservaron del sistema original.

El servidor, las sesiones y las bases temporales de pruebas se retiraron al terminar. No se modificó el sistema antiguo en proyecto durante esta implementación. No se realizaron commits ni etiquetas.
