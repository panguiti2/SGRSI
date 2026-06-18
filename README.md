# SGRSI - Sistema de Gestión de Recursos y Soporte Informático

Sistema web desarrollado para optimizar la gestión de incidencias técnicas, solicitudes de servicio y recursos informáticos dentro de instituciones educativas. SGRSI centraliza la comunicación entre usuarios, técnicos y administradores, permitiendo un seguimiento eficiente de las solicitudes y facilitando la toma de decisiones mediante reportes y métricas.

---
##  Documentación del Proyecto

Antes de empezar a programar, es obligatorio leer las normas de desarrollo en los siguientes enlaces:

*   [Manual de Versionado y Etiquetas](./docs/manualVersionado.md)
*   [Especificación de Requerimientos de Software](./docs/ERS.pdf)
*   [Convenciones de Mensajes de Commit](./docs/convencionesCommit.md)
*   [Estándares de Codificación de Código](./docs/estandaresCodificacion.md)


## Desarrollado por PANGU

**PANGU** es un equipo de desarrollo de software enfocado en la creación de soluciones tecnológicas que mejoran la gestión y organización de procesos institucionales. Nuestro objetivo es aplicar buenas prácticas de ingeniería de software, diseño UX/UI y desarrollo web para construir sistemas funcionales, escalables y centrados en el usuario.

### Misión

Desarrollar soluciones tecnológicas innovadoras que contribuyan a la eficiencia operativa de las organizaciones mediante herramientas digitales accesibles y de calidad.

### Visión

Convertirse en un equipo reconocido por la creación de software útil, intuitivo y alineado con las necesidades reales de los usuarios.

---

## Descripción del Proyecto

El Sistema de Gestión de Recursos y Soporte Informático (SGRSI) surge de la necesidad de mejorar el proceso de atención y seguimiento de incidencias técnicas dentro de una institución educativa.

Actualmente, muchos procesos se realizan de forma manual, dificultando el control de solicitudes, la asignación de tareas y la generación de información para la toma de decisiones. SGRSI busca digitalizar estos procesos mediante una plataforma web intuitiva y centralizada.

---

## Objetivos

- Centralizar la gestión de incidencias y solicitudes de servicio.
- Mejorar el seguimiento de tickets y tiempos de respuesta.
- Facilitar la asignación de tareas al personal técnico.
- Mantener un historial de incidencias y soluciones.
- Proporcionar métricas y reportes para la toma de decisiones.
- Optimizar la comunicación entre usuarios y el área de soporte.

---

## Características

- Registro de incidencias técnicas.
- Gestión de solicitudes de servicio.
- Seguimiento de tickets.
- Administración de usuarios y roles.
- Asignación de incidencias a técnicos.
- Historial de actividades.
- Panel de métricas.
- Generación de reportes.
- Interfaz adaptable a dispositivos móviles.
- Diseño basado en Bootstrap 5.

---

## Roles del Sistema

### Usuario Solicitante

Usuarios que requieren asistencia técnica o servicios informáticos.

#### Funcionalidades

- Reportar incidencias.
- Realizar solicitudes de servicio.
- Realizar reporte de uso de recursos
- Consultar estado de solicitudes.

---

### Técnico

Responsable de la atención y resolución de incidencias.

#### Funcionalidades

- Consultar incidencias asignadas.
- ABM de prestamos
- Actualizar estados.
- Registrar soluciones.
- Gestionar solicitudes de servicio.

---

### Administrador

Responsable de la gestión global del sistema.

#### Funcionalidades

- ABM de usuarios.
- ABM de inventario
- Gestionar roles.
- Supervisar incidencias.
- Consultar métricas.
- Generar reportes.
- Configurar parámetros del sistema.

---

## Tecnologías Utilizadas

### Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript

### Diseño

- Figma

### Gestión del Proyecto

- Git
- GitHub

---

## Estructura del Proyecto

```text
SGRSI/
│
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
│
├── pages/
│   ├── admin/
│   ├── tecnico/
│   └── user/
│
├── index.html
│
└── README.md
```

---

## Instalación

### Clonar el repositorio

```bash
gh repo clone panguiti2/SGRSI
```

### Acceder al directorio

```bash
cd SGRSI
```

### Ejecutar el proyecto

Abrir el archivo `index.html` en el navegador o utilizar una extensión como Live Server en Visual Studio Code.

---

## Convenciones de Desarrollo

### Nombres de Archivos

Utilizar formato:

```text
lowCamel-case
```

Ejemplo:

```text
gestionUsuario.html
registroUso.html
```

### Clases CSS

```css
.ticket-card {
    padding: 1rem;
}
```

### Variables CSS

```css
:root {
    --primary-color: #0d6efd;
}
```

---

## Flujo de Trabajo Git


### Registrar cambios

```bash
git add .
git commit -m "feat: descripción del cambio"
```


## Estado del Proyecto

En desarrollo.

Actualmente se encuentra en fase de construcción de la interfaz de usuario y organización de la arquitectura frontend.

### Próximas tareas

- [ ] Implementar autenticación.
- [ ] Gestión completa de tickets.
- [ ] Gestión de solicitudes de servicio.
- [ ] Panel de reportes.
- [ ] Integración con backend.
- [ ] Pruebas funcionales.
- [ ] Despliegue del sistema.

---

## Mockups y Diseño

Los prototipos de interfaz fueron diseñados en Figma siguiendo un enfoque Mobile First y utilizando componentes compatibles con Bootstrap 5 para facilitar la implementación del diseño en el desarrollo.

---

## Buenas Prácticas Aplicadas

- Arquitectura organizada por módulos.
- Diseño responsive.
- Mobile First.
- Reutilización de componentes.
- Uso de Bootstrap para consistencia visual.
- Control de versiones mediante Git.
- Documentación del proyecto.

---

## Equipo de Desarrollo

### PANGU

- Alessia Valecillos
- Aejo Gonzalez
- Manu Vicente
- Joaquin Novo

---

## Licencia

Este proyecto fue desarrollado con fines académicos como parte del proceso de aprendizaje y aplicación de metodologías de Ingeniería de Software, Programación FULL STACK, y Administración de Sistemas Operativos

---

© 2026 PANGU. Todos los derechos reservados.
