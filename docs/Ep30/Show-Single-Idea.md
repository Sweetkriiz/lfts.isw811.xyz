# Show A Single Idea

## Episodio 30 - Show A Single Idea

### Desarrollo del episodio

En este episodio se comenzó a desarrollar la vista para mostrar una idea individual dentro de la aplicación. Hasta este momento era posible acceder a la ruta correspondiente al hacer clic sobre una idea, pero aún no existía la vista encargada de mostrar su contenido.

Para solucionar esto, se utilizó el método `show()` del controlador de ideas, el cual retorna una nueva vista llamada `ideas.show` y envía como parámetro la idea seleccionada.

```php
public function show(Idea $idea)
{
    return view('ideas.show', [
        'idea' => $idea,
    ]);
}
```

Posteriormente se creó el archivo:

```text
resources/views/ideas/show.blade.php
```

Inicialmente la vista únicamente mostraba el título de la idea para verificar que el modelo estaba llegando correctamente.

---

## Construcción de la interfaz

Una vez comprobado el funcionamiento de la ruta, se comenzó a construir el diseño de la página utilizando Tailwind CSS.

La estructura principal quedó conformada por:

- Un contenedor con ancho máximo.
- El título de la idea.
- Una tarjeta para mostrar la descripción.
- Márgenes y espaciados para mejorar la presentación.

La descripción fue colocada dentro del componente `card`, reutilizando el mismo diseño utilizado anteriormente para mantener una interfaz consistente.

---

## Navegación

Se agregó una barra superior con acciones para facilitar la navegación.

En el lado izquierdo se añadió un enlace que permite regresar al listado principal de ideas.

```text
Back to Ideas
```

Este enlace utiliza la ruta nombrada correspondiente al índice de ideas.

En el lado derecho se agregaron los botones:

- Edit Idea
- Delete

Estos elementos fueron alineados utilizando `flex`, `justify-between`, `items-center` y `gap`, logrando una distribución limpia de los controles.

---

## Uso de componentes SVG

Para mejorar la apariencia de la interfaz se utilizaron íconos SVG almacenados como componentes Blade.

Este enfoque ofrece varias ventajas:

- Reutilización de íconos.
- Posibilidad de aplicar estilos mediante Tailwind CSS.
- Cambio sencillo de colores.
- Personalización mediante clases.
- Evita utilizar imágenes independientes.

Durante el episodio se incorporaron un ícono de flecha para regresar al listado y un ícono de enlace externo para representar acciones adicionales.

---

## Eliminación de ideas

También se preparó la funcionalidad para eliminar una idea desde esta misma página.

Se creó un formulario que envía una solicitud `DELETE` hacia la ruta correspondiente utilizando las directivas de Laravel:

```blade
@csrf
@method('DELETE')
```

Posteriormente se implementó el método `destroy()` dentro del controlador para eliminar el registro seleccionado y redirigir nuevamente al listado de ideas.

---

## Estado y fecha de la idea

Debajo del título se agregó información adicional sobre la idea.

Se reutilizó el componente encargado de mostrar el estado actual de la idea, permitiendo visualizar si se encuentra pendiente, en progreso o completada.

Además, se mostró la fecha utilizando el método:

```php
$idea->created_at->diffForHumans()
```

El instructor comentó que posteriormente podría cambiarse para mostrar la fecha de la última actualización en lugar de la fecha de creación.

---

## Enlaces relacionados

Se añadió una sección para mostrar los enlaces asociados a cada idea.

Antes de renderizar esta sección se verifica que realmente existan enlaces registrados.

Cada enlace:

- Se muestra dentro de una tarjeta.
- Es completamente clickeable.
- Utiliza un color diferente para destacarlo.
- Incluye un ícono indicando que abrirá un recurso externo.

Cuando una idea no posee enlaces asociados, esta sección simplemente no se renderiza.

---
