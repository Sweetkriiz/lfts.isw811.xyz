# Create A Functional Modal With AlpineJS

## Episodio 31 - Create A Functional Modal With AlpineJS

### Desarrollo del episodio

Durante este episodio se desarrolló un modal completamente funcional utilizando AlpineJS. El objetivo fue preparar una ventana emergente reutilizable que posteriormente permitirá crear y editar ideas sin abandonar la página principal.

El trabajo comenzó en la vista:

```text
resources/views/ideas/index.blade.php
```

Se agregó una tarjeta que funcionará como botón para abrir el modal.

```blade
<x-card
    is="button"
    type="button"
    class="mt-10 h-32 w-full cursor-pointer text-left"
    x-data
    @click="$dispatch('open-modal', 'create-idea')"
>
    <p>What's the idea?</p>
</x-card>
```

Para ello se modificó el componente `Card`, permitiendo que pudiera renderizarse como distintos elementos HTML mediante una propiedad `is`, en lugar de comportarse únicamente como un enlace (`<a>`). En este caso se utilizó como un botón.

---

## Uso de eventos personalizados con AlpineJS

En lugar de controlar directamente la visibilidad del modal, se utilizó el sistema de eventos del navegador que ofrece AlpineJS mediante `$dispatch`.

Cuando el usuario hace clic sobre la tarjeta se dispara un evento personalizado:

```blade
@click="$dispatch('open-modal', 'create-idea')"
```

El primer parámetro corresponde al nombre del evento (`open-modal`) y el segundo indica qué modal se desea abrir (`create-idea`).

Este enfoque permite que cualquier componente de la aplicación pueda solicitar la apertura de un modal sin depender de referencias directas entre componentes.

---

## Construcción del modal

En la parte inferior de la vista se agregó una estructura básica para representar el modal.

```blade
<div
    x-data="{ show: false, name: 'create-idea' }"
    x-show="show"
>
    <x-card>
        I am a modal
    </x-card>
</div>
```

Se utilizó `x-data` para crear un pequeño componente de AlpineJS con dos propiedades:

- `show`: controla si el modal es visible.
- `name`: almacena el identificador del modal.

Inicialmente el modal permanece oculto porque `show` inicia con el valor `false`.

---

## Escuchar el evento desde la ventana

El modal comienza a escuchar el evento personalizado enviado anteriormente.

```blade
@open-modal.window="
    if ($event.detail === name) {
        show = true
    }
"
```

Cuando AlpineJS detecta el evento `open-modal`, compara el contenido de `event.detail` con el nombre del modal.

Si ambos coinciden, la propiedad `show` cambia a `true` y el modal aparece en pantalla.

Gracias a este mecanismo pueden existir múltiples modales en la aplicación sin interferir entre sí.

---

## Posicionamiento del modal

Posteriormente se agregaron clases de Tailwind CSS para posicionar el modal sobre toda la pantalla.

Entre las principales clases utilizadas se encuentran:

- `fixed`
- `inset-0`
- `z-50`
- `flex`
- `items-center`
- `justify-center`
- `bg-black/50`
- `backdrop-blur`

Con esto el modal aparece centrado sobre un fondo oscuro semitransparente que bloquea la interacción con el resto de la página.

---

## Animaciones con x-transition

Se añadieron transiciones utilizando la directiva `x-transition`.

```blade
x-transition:enter="duration-200 ease-out"
x-transition:enter-start="opacity-0 translate-y-4"
x-transition:enter-end="opacity-100 translate-y-0"

x-transition:leave="duration-150 ease-in"
x-transition:leave-start="opacity-100 translate-y-0"
x-transition:leave-end="opacity-0 translate-y-4"
```

Estas transiciones producen un efecto donde el modal aparece con una ligera animación de desplazamiento y desvanecimiento, ofreciendo una experiencia más fluida al usuario.

---

## Cerrar el modal haciendo clic fuera

Para mejorar la interacción se aprovechó una de las funcionalidades de AlpineJS:

```blade
@click.away="show = false"
```

Con esta instrucción el modal se cierra automáticamente cuando el usuario hace clic fuera de la tarjeta.

---

## Cerrar el modal con la tecla Escape

También se agregó soporte para cerrar el modal mediante la tecla **Escape**.

```blade
@keydown.escape.window="show = false"
```

El evento se escucha a nivel de la ventana (`window`) para garantizar que funcione independientemente del elemento que tenga el foco.

---

## Evitar el parpadeo inicial

Durante la carga de la página puede producirse un pequeño parpadeo antes de que AlpineJS inicialice el componente.

Para evitarlo se añadió:

```html
style="display: none;"
```

De esta manera el modal permanece completamente oculto hasta que AlpineJS toma el control.

---

## Mejoras de accesibilidad

Se incorporaron varios atributos ARIA para indicar que el elemento corresponde a un diálogo modal.

```blade
role="dialog"
aria-modal="true"
:aria-hidden="!show"
tabindex="-1"
```

Estos atributos permiten que lectores de pantalla y otras tecnologías de asistencia interpreten correctamente el propósito del componente.

---

## Extracción a un componente Blade

Una vez comprobado su funcionamiento, toda la estructura del modal fue movida a un componente reutilizable.

Se creó el archivo:

```text
resources/views/components/modal.blade.php
```

El componente recibe dos propiedades principales:

```blade
@props([
    'name',
    'title'
])
```

Para enviar correctamente el nombre del modal desde PHP hacia JavaScript se utilizó la directiva:

```blade
@js($name)
```

Esto evita problemas de conversión entre ambos lenguajes.

---

## Título dinámico del modal

Dentro del componente se agregó un encabezado configurable.

```blade
<h2 id="modal-{{ $name }}-title" class="text-lg font-bold">
    {{ $title }}
</h2>
```

El identificador generado también se utiliza mediante `aria-labelledby`, mejorando aún más la accesibilidad del modal.

---

## Uso del componente

Después de extraer la lógica, el modal puede incorporarse en cualquier vista utilizando un único componente Blade.

```blade
<x-modal
    name="create-idea"
    title="New Idea"
>
    Modal content here.
</x-modal>
```

Todo el comportamiento relacionado con la apertura, cierre, animaciones y accesibilidad queda encapsulado dentro del componente, facilitando su reutilización en cualquier parte del proyecto.

---

## Resultado final

Al finalizar el episodio se obtuvo un componente de modal completamente funcional que incorpora las siguientes características:

- Tarjeta utilizada como botón para abrir el modal.
- Uso de eventos personalizados mediante `$dispatch`.
- Comunicación entre componentes utilizando eventos del navegador.
- Control de visibilidad mediante AlpineJS.
- Fondo superpuesto con efecto de desenfoque.
- Animaciones de entrada y salida con `x-transition`.
- Cierre automático al hacer clic fuera del modal.
- Cierre mediante la tecla **Escape**.
- Prevención del parpadeo inicial durante la carga.
- Implementación de atributos básicos de accesibilidad (ARIA).
- Extracción de toda la lógica a un componente Blade reutilizable.
- Soporte para nombre y título dinámicos mediante propiedades del componente.
