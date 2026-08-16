# Documentación Doxygen

La documentación técnica se genera desde la raíz del repositorio mediante:

```powershell
doxygen Doxyfile
```

El resultado se escribe en `docs/doxygen/html/index.html`. La configuración incluye los modelos, controladores, puntos públicos y JavaScript, y registra advertencias en `docs/doxygen-warnings.log`.

La documentación debe regenerarse después de modificar interfaces públicas, clases, métodos o controladores.
