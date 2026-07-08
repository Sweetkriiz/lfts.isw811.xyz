# Idea Cards

## Episodio 28 - Creación de las Idea Cards

### Desarrollo del episodio

En este episodio se comenzó el desarrollo de la pantalla principal donde los usuarios podrán visualizar todas sus ideas mediante tarjetas (Idea Cards). Además, se realizó una breve revisión de las recomendaciones generadas por CodeRabbit antes de continuar con el desarrollo.

---

## Desarrollo

### Revisión de CodeRabbit

Al inicio del episodio se revisaron las observaciones realizadas por CodeRabbit sobre cambios implementados anteriormente.

Durante esta revisión:

- Se descartó la recomendación de cifrar manualmente la contraseña, ya que Laravel utiliza el cast `hashed` para realizar este proceso automáticamente.
- Se revisaron sugerencias relacionadas con el cierre de sesión y el manejo de la sesión.
- Se corrigieron pequeños detalles de HTML y CSS detectados por la herramienta.
- Se realizó una limpieza general del proyecto antes de agregar nuevas funcionalidades.

---

### Redirección de la página principal

Se modificó el archivo de rutas para que la ruta `/` redireccione temporalmente hacia `/ideas`, dejando abierta la posibilidad de crear posteriormente una página de inicio o marketing.

Además:

- Se creó la ruta para listar las ideas.
- Se implementó el controlador `IdeaController`.
- Se agregó el método `index()`.
- Se protegió la ruta mediante el middleware `auth`, permitiendo únicamente el acceso a usuarios autenticados.
- Se definió correctamente la ruta nombrada `login` para evitar errores al redireccionar usuarios no autenticados.

---

### Obtención de las ideas del usuario

Dentro del método `index()` se recuperaron únicamente las ideas pertenecientes al usuario autenticado utilizando la relación existente entre `User` e `Idea`.

Para ello se utilizó:

- `Auth::user()->ideas`

De esta forma cada usuario visualizará únicamente sus propias ideas.

---

### Creación de la vista Index

Se creó la vista:

```
resources/views/ideas/index.blade.php
```

En esta vista se desarrolló:

- Encabezado principal.
- Subtítulo descriptivo.
- Recorrido de todas las ideas mediante `@forelse`.
- Mensaje cuando el usuario aún no posee ideas registradas.

---

### Creación de datos de prueba

Para visualizar correctamente la interfaz se utilizaron Factories junto con Tinker para generar registros de ejemplo asociados al usuario autenticado.

Esto permitió comprobar el funcionamiento de la lista antes de implementar las funcionalidades restantes.

---

### Diseño de las Idea Cards

Cada idea comenzó a mostrarse mediante una tarjeta que incluye:

- Título.
- Descripción.
- Fecha de creación utilizando `diffForHumans()`.
- Diseño responsive mediante Grid.
- Estilos utilizando Tailwind CSS.

Las tarjetas se muestran:

- Una columna en dispositivos móviles.
- Dos columnas en pantallas grandes.

---

### Extracción del componente Card

Con el fin de reutilizar el diseño en otras partes del proyecto, la estructura de la tarjeta fue extraída hacia un componente Blade.

Se creó:

```
resources/views/components/card.blade.php
```

Este componente recibe contenido mediante `{{ $slot }}` y permite reutilizar el mismo diseño sin duplicar código.

---

### Navegación hacia cada idea

Cada tarjeta fue convertida en un enlace que apunta a la vista individual de la idea.

Para ello:

- Se creó la ruta `ideas.show`.
- Se agregó el método `show()` en el controlador.
- Se utilizaron rutas nombradas para facilitar el mantenimiento del proyecto.

---

### Creación del componente Status Label

Se agregó una etiqueta que muestra el estado actual de cada idea.

Inicialmente se mostraba únicamente el estado **Pending**, pero posteriormente se extrajo la lógica a un componente Blade independiente.

Se creó:

```
resources/views/components/idea/status-label.blade.php
```

Este componente:

- Recibe dinámicamente el estado de la idea.
- Cambia automáticamente sus colores según el estado.
- Centraliza toda la lógica de presentación.

Estados soportados:

- Pending
- In Progress
- Completed

Cada uno posee colores diferentes para facilitar su identificación.

---

### Resultado

Al finalizar el episodio quedó implementada la primera versión de la pantalla de Ideas.

Cada usuario puede visualizar únicamente sus propias ideas mediante tarjetas reutilizables que muestran:

- Estado.
- Título.
- Descripción.
- Fecha de creación.
- Enlace hacia el detalle de la idea.

Además, la interfaz quedó preparada para incorporar filtros por estado en el siguiente episodio.

---

## Conceptos aprendidos

- Redirecciones en Laravel.
- Middleware de autenticación.
- Relaciones Eloquent (`User -> Ideas`).
- Blade Components.
- Componentes reutilizables.
- Grid Layout con Tailwind CSS.
- Uso de `@forelse`.
- Factories y Tinker.
- Formateo de fechas con Carbon (`diffForHumans()`).
- Componentes dinámicos para etiquetas de estado.
- Uso de rutas nombradas.