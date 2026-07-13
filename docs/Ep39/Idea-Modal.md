# The Edit Idea Modal

## Episodio 40 - The Edit Idea Modal

### Desarrollo del episodio

En este episodio se comenzó a implementar la funcionalidad para editar una idea existente utilizando el mismo modal empleado para crear nuevas ideas.

La meta principal fue evitar duplicar toda la estructura del formulario y reutilizar el componente del modal tanto para creación como para edición.

### Creación de la prueba de edición

El trabajo inició desde las pruebas automatizadas. El archivo de prueba utilizado para crear ideas se renombró para agrupar allí las distintas acciones relacionadas con ideas.

Se agregó una nueva prueba encargada de comprobar que un usuario pueda editar una idea que ya existe.

Primero se crea y autentica un usuario:

```php
$this->actingAs($user = User::factory()->create());
```

Después se genera una idea asociada a ese usuario:

```php
$idea = Idea::factory()
    ->for($user)
    ->create();
```

La prueba visita la página individual de la idea e intenta presionar el botón encargado de editarla.

```php
visit(route('idea.show', $idea))
    ->click('@edit-idea-button');
```

Inicialmente la prueba falla porque el botón no posee todavía un selector adecuado y el modal tampoco se encuentra disponible dentro de la página de detalle.

### Agregar el selector al botón de edición

En la vista individual de la idea se agregó un atributo `data-test` al botón de edición.

```blade
data-test="edit-idea-button"
```

Gracias a este atributo, Pest Browser puede identificar el botón mediante:

```php
->click('@edit-idea-button')
```

### Abrir el modal con Alpine.js

Al presionar el botón de edición se debe emitir el evento encargado de abrir el modal.

```blade
@click="$dispatch('open-modal', 'edit-idea')"
```

Para que el evento funcione, el botón debe encontrarse dentro de un contexto de Alpine.js.

Sin embargo, aunque el evento se ejecutaba correctamente, el modal seguía sin mostrarse.

### Problema del modal inexistente

El inconveniente era que el modal de creación se encontraba escrito directamente dentro de la vista `index`.

Por esta razón, cuando el usuario visitaba la página `show`, ese modal no existía dentro del HTML cargado y ningún evento podía abrirlo.

El evento `open-modal` solo puede ser recibido si el componente del modal está presente en la página actual.

### Extracción del modal a un componente

Para solucionar el problema se decidió mover todo el formulario a un componente Blade reutilizable.

El nuevo componente se creó dentro de la carpeta de componentes de ideas.

```text
resources/views/components/idea/modal.blade.php
```

Dentro de este archivo se trasladó toda la estructura del modal, incluyendo:

- El formulario.
- Los campos de título y descripción.
- La selección de estado.
- La carga de imagen.
- Los pasos accionables.
- Los enlaces.
- Los botones para cancelar y guardar.

De esta manera, el mismo componente puede incluirse desde diferentes vistas.

### Soporte para crear y editar

El componente recibe una instancia de `Idea` mediante sus propiedades.

```blade
@props([
    'idea' => new App\Models\Idea(),
])
```

La propiedad `$idea->exists` permite identificar si el componente se está utilizando para crear un nuevo registro o para modificar uno existente.

El nombre y el título del modal cambian dinámicamente:

```blade
<x-modal
    name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}"
>
```

La acción del formulario también se ajusta según el caso:

```blade
action="{{ $idea->exists
    ? route('idea.update', $idea)
    : route('idea.store') }}"
```

Cuando la idea ya existe, se agrega el método `PATCH`:

```blade
@if ($idea->exists)
    @method('PATCH')
@endif
```

### Cargar los datos existentes

Para que el formulario de edición muestre la información actual, los campos reciben los datos de la idea.

```blade
:value="old('title', $idea->title ?? '')"
```

También se inicializan los enlaces y pasos mediante Alpine.js.

```blade
links: @js(old('links', $idea->links ?? [])),
steps: @js(
    old(
        'steps',
        $idea->steps?->pluck('description')->all() ?? []
    )
),
```

De esta forma, al abrir el modal de edición, el usuario puede visualizar y modificar los datos previamente almacenados.

### Reutilización en diferentes vistas

El componente puede utilizarse en la página principal para crear ideas:

```blade
<x-idea.modal />
```

Y también dentro de la página individual para editar una idea:

```blade
<x-idea.modal :idea="$idea" />
```

Esto elimina la duplicación de código y mantiene toda la lógica del formulario dentro de un único archivo.

### Resultado del episodio

Al finalizar el episodio se inició la implementación del modal de edición reutilizando el formulario de creación. Se agregó una prueba automatizada para el flujo de edición, se incorporó un selector al botón correspondiente y se extrajo el modal a un componente Blade independiente.

Gracias a esta refactorización, el mismo componente puede utilizarse tanto para crear como para editar ideas, adaptando automáticamente su título, ruta, método HTTP y valores iniciales según la existencia del modelo.
