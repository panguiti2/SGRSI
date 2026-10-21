# SGRSI — Sistema de Gestión de Recursos y Soporte Informático

SGRSI es un sistema web para organizar el uso de laboratorios, el inventario, los préstamos de laptops y la atención de incidencias y solicitudes de servicio de una institución educativa.

Lo desarrollamos como proyecto de egreso del Instituto Tecnológico de Informática. El equipo de desarrollo es PANGU.

## Funcionalidades

- Gestión de usuarios con roles de administrador, técnico y solicitante.
- Alta, modificación, activación, desactivación y baja definitiva de dispositivos, conservando sus registros.
- Historial de cambios del inventario.
- Registro y seguimiento de incidencias y solicitudes de servicio.
- Registro de préstamos y devoluciones de laptops.
- Registro histórico de uso de laboratorios.
- Métricas generales y por laboratorio.
- Búsqueda y orden en las tablas habilitadas.
- Interfaz adaptable a distintos tamaños de pantalla.

## Roles y permisos

### Administrador

- Registra, consulta, modifica, activa y desactiva usuarios.
- Gestiona los dispositivos del inventario y consulta los dados de baja, sin posibilidad de recuperarlos.
- Consulta incidencias, historial de inventario y métricas.

### Técnico

- Gestiona incidencias y solicitudes de servicio.
- Registra diagnósticos y soluciones de incidencias.
- Registra préstamos y sus devoluciones.
- Consulta inventario, historial, registros de uso y métricas.

### Solicitante

- Reporta incidencias y solicita servicios para dispositivos activos.
- Consulta el estado de sus propios tickets.
- Registra usos de laboratorio y consulta sus propios registros.

Los permisos se verifican en el servidor. Acceder a una dirección o cambiar datos en el navegador no concede acceso a las operaciones de otro rol.

## Reglas del sistema

- Los usuarios se desactivan, no se borran físicamente. Se conservan sus roles y registros asociados.
- Para editar los datos de un usuario inactivo, primero se debe activar.
- El cambio de rol se bloquea cuando existen referencias que impiden retirar el rol anterior.
- Los dispositivos dados de baja se conservan para consulta histórica, pero no pueden recuperarse ni modificarse desde la aplicación.
- Inactivar un dispositivo es temporal y reversible; darlo de baja es definitivo. La baja también lo deja inactivo.
- La identificación de un equipo dado de baja no puede reutilizarse en el mismo laboratorio. Un equipo nuevo debe tener otra identificación (por ejemplo, PC016.1), de hasta 8 caracteres.
- No se permite dar de baja un dispositivo con tickets sin resolver.
- No se admiten nuevos tickets sobre dispositivos inactivos o dados de baja.
- Los tickets y préstamos no permiten eliminación ni edición libre de sus datos originales. El técnico gestiona la atención y registra las devoluciones.
- Ningún usuario puede modificar ni eliminar registros de uso: son históricos.
- Los catálogos se consultan desde la base de datos para cargar los formularios. No tienen una pantalla de administración.
- Se controla que no exista otro préstamo activo para la misma cédula o el mismo identificador de laptop.

El módulo de préstamos registra las laptops utilizadas, pero no constituye un inventario completo de laptops. No se incluyen exportaciones de reportes a PDF o Excel ni notificaciones automáticas.

## Tecnologías

- HTML, CSS y JavaScript.
- Bootstrap 5.3.3.
- PHP y PDO.
- phpMyAdmin para administrar e importar la base de datos.
- MariaDB como motor de base de datos.
- Fetch para consumir la API.
- Composer y PHP dotenv para cargar la configuración.
- Git y GitHub para el trabajo en equipo.

La aplicación no utiliza un framework de JavaScript, un ORM ni una biblioteca de enrutamiento.

## Organización del repositorio

La aplicación actual se encuentra en `proyecto`. La carpeta `proyecto_anterior` conserva una versión previa y no es necesaria para ejecutarla.

```text
SGRSI/
├── proyecto/
│   ├── app/
│   │   ├── controlador/
│   │   ├── modelo/
│   │   ├── nucleo/
│   │   ├── rutas/
│   │   └── vista/
│   ├── config/
│   ├── public/
│   │   ├── admin/
│   │   ├── tecnico/
│   │   ├── solicitante/
│   │   ├── assets/
│   │   ├── index.html
│   │   └── index.php
│   ├── composer.json
│   └── composer.lock
├── baseDeDatos/
├── administracion-iti/S
└── README.md
```

- `public`: páginas HTML y recursos del cliente. Su único PHP es la entrada de la API.
- `app/rutas`: recursos disponibles y métodos HTTP permitidos.
- `app/controlador`: validaciones, permisos y coordinación de cada operación.
- `app/modelo`: acceso a la base de datos mediante DAO.
- `app/nucleo`: funciones compartidas de peticiones, conexión, sesiones y seguridad.
- `app/vista`: generación de respuestas JSON, no de pantallas HTML.
- `config`: configuración interna de la aplicación.
- `baseDeDatos`: estructura SQL, ejemplos, migraciones y permisos.
- `administracion-iti`: scripts de consulta y respaldo para la cuenta institucional.

El archivo `.env` y las dependencias de `vendor` pertenecen a `proyecto`, pero no se publican en el repositorio.

## Instalación local

### 1. Obtener el proyecto

```bash
git clone https://github.com/panguiti2/SGRSI.git
cd SGRSI/proyecto
```

### 2. Preparar el entorno

Se necesita PHP con las extensiones `pdo_mysql` y `mbstring`, acceso a phpMyAdmin para administrar la base y Composer para instalar las dependencias. El servidor debe disponer del motor de base de datos; en nuestras pruebas utilizamos MariaDB 10.4 y PHP 8.3.

phpMyAdmin es la herramienta web que utilizamos para importar y consultar los datos. MariaDB es el motor que los almacena. La aplicación se conecta al motor mediante PDO, no a la interfaz de phpMyAdmin. Para esta instalación no es necesario importar la base desde una terminal.

Desde la carpeta `proyecto`:

```bash
composer install
```

### 3. Importar la base de datos

Importar el archivo SQL de instalación en una base vacía mediante phpMyAdmin. En el servidor institucional se utiliza la base asignada por el administrador.

La aplicación utiliza nombres de tablas en mayúsculas y codificación `utf8mb4`. El respaldo debe corresponder a la versión actual, incluida la tabla de historial y el campo de baja lógica del inventario.

No importar ejemplos SQL ni un respaldo completo sobre una instalación existente para actualizarla. Antes de una actualización, realizar un respaldo y revisar las migraciones correspondientes.

### 4. Configurar el archivo .env

Crear o modificar `proyecto/.env` con los datos del entorno:

```dotenv
DB_HOST="localhost"
DB_USUARIO="usuario_asignado"
DB_CLAVE="contraseña_asignada"
DB_NOMBRE="base_asignada"
```

Si el servidor de base de datos utiliza un puerto diferente de 3306, agregar `DB_PUERTO` con el puerto correspondiente. Este es el puerto del motor, no el puerto web de phpMyAdmin ni el de WinSCP.

No subir el archivo con credenciales al repositorio ni colocarlo dentro de `public`.

### 5. Iniciar la aplicación

Desde `proyecto`:

```bash
php -S localhost:3000 -t public
```

Abrir [http://localhost:3000/index.html](http://localhost:3000/index.html) e ingresar con una cuenta activa incluida en la base importada.

No utilizar Live Server para ejecutar la aplicación: la API necesita PHP. El servidor integrado de PHP se utiliza para desarrollo local, no para el despliegue institucional.

## Despliegue institucional

Transferir los archivos de la aplicación mediante WinSCP a la ubicación autorizada. Instalar o incluir las dependencias de Composer, importar la base mediante phpMyAdmin y adaptar el archivo `.env`.

La raíz web debe apuntar a `proyecto/public`. Las carpetas `app`, `config` y `vendor`, junto con `.env`, deben quedar fuera del acceso público. Los scripts de administración y los respaldos también se mantienen fuera de la raíz web.

Utilizar los permisos de base de datos autorizados para la aplicación. Una baja lógica enviada mediante DELETE HTTP no requiere conceder DELETE SQL sobre USUARIO o DISPOSITIVO: se realiza mediante UPDATE.

Al actualizar, copiar cliente y API de la misma versión, conservar las credenciales del servidor y comprobar el acceso con cada rol.

## Comunicación con la API

El cliente envía peticiones a `index.php?ruta=recurso`. PHP devuelve JSON con el mismo contrato para éxitos y errores:

```json
{
  "status": 200,
  "message": "Operación realizada correctamente",
  "data": []
}
```

Los errores conservan esas tres propiedades y utilizan `data: null`. El valor de `status` coincide con el código HTTP de la respuesta.

| Recurso | Métodos |
| --- | --- |
| login y logout | POST |
| sesion | GET |
| usuarios | GET, POST, PUT, DELETE |
| dispositivos | GET, POST, PUT, DELETE |
| tickets y prestamos | GET, POST, PUT |
| registrosUso | GET, POST |
| historialDispositivos, metricas y formulario | GET |

En usuarios, DELETE desactiva y PUT modifica datos o activa. En dispositivos, DELETE realiza la baja lógica. Las escrituras requieren los permisos correspondientes y un token CSRF válido.

Aplicamos REST de forma parcial. Conservamos sesiones PHP para autenticar y autorizar, por lo que no presentamos la API como completamente sin estado. Las respuestas autenticadas se marcan con `Cache-Control: no-store`.

## Comprobaciones

Desde la raíz del repositorio, con Node.js y el entorno PHP configurados, se pueden ejecutar estas comprobaciones sin iniciar una base de prueba:

```bash
node tests/api.cjs
node tests/contratoCliente.cjs
node tests/presentacionEstatica.cjs
node tests/recursosHtml.cjs
php -d date.timezone=America/Montevideo tests/nucleo.php
```

Las pruebas de integración y navegador utilizan una instancia local separada y bases ficticias. No deben ejecutarse sobre la base institucional ni confundirse con una comprobación del despliegue real.

## Trabajo en equipo

Organizamos los cambios en commits pequeños, separados por funcionalidad o corrección. Utilizamos prefijos como `feat:`, `fix:`, `refactor:`, `test:` y `docs:`, con mensajes que describan lo realizado.

Antes de integrar un cambio, comprobamos que no incluya credenciales, archivos temporales ni modificaciones ajenas a la tarea.

## Equipo PANGU

- Alessia Valecillos.
- Alejo Gonzalez.
- Manu Vicente.
- Joaquin Novo.

Proyecto académico desarrollado en 2026.
