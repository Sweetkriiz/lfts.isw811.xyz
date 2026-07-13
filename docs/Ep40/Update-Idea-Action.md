
# Update Idea Action

## Episodio 40 - Update Idea Action

### Desarrollo del episodio

En este episodio se completó la funcionalidad para actualizar una idea existente mediante una nueva clase de acción llamada `UpdateIdea`.

En el episodio anterior se había comenzado a reutilizar el modal de creación para editar ideas. Sin embargo, todavía faltaba implementar la lógica encargada de guardar los cambios realizados desde ese formulario.

### Ajustes en la prueba de actualización

Antes de implementar la lógica, se actualizó la prueba automatizada para reflejar correctamente el flujo esperado.

Después de editar una idea, el usuario debe regresar a la página de detalle de esa misma idea.

```php
->click('Update')
->assertRouteIs('idea.show', $idea);
```

La prueba inicialmente falló porque la acción de actualización todavía no realizaba ningún cambio, lo cual permitió identificar claramente el siguiente paso del desarrollo.

### Creación de la Action Class

Se creó una nueva clase dentro de:

```text
app/Actions/UpdateIdea.php
```

Aunque inicialmente se consideró reutilizar la clase `CreateIdea`, se decidió crear una acción independiente debido a que la actualización posee responsabilidades diferentes, especialmente en la sincronización de los pasos existentes.

La clase recibe los atributos validados y la idea que será actualizada.

```php
public function handle(array $attributes, Idea $idea)
{
    //
}
```

### Preparación de los datos

Dentro de la acción se seleccionan únicamente los atributos correspondientes al modelo `Idea`.

```php
$data = collect($attributes)->only([
    'title',
    'description',
    'status',
    'links',
])->toArray();
```

Esto evita intentar almacenar directamente información perteneciente a otras tablas, como los pasos.

### Actualización de la imagen

Si el formulario incluye una nueva imagen, ésta se guarda dentro del disco público y su ruta se agrega al arreglo de datos.

```php
if ($attributes['image'] ?? false) {
    $data['image_path'] = $attributes['image']->store(
        'ideas',
        'public'
    );
}
```

La imagen se procesa antes de actualizar la idea para incluir su ruta en la misma operación.

### Cambio de estructura de los pasos

Durante la creación de ideas, los pasos se enviaban como un arreglo simple de descripciones.

Para la actualización fue necesario modificar esa estructura, ya que también se debía conservar el estado `completed`.

Cada paso pasó a representarse como un objeto con la siguiente información:

```php
[
    'description' => 'Record videos',
    'completed' => true,
]
```

En el formulario se agregaron campos agrupados por índice.

```blade
<input
    :name="`steps[${index}][description]`"
    x-model="step.description"
    class="input"
/>

<input
    type="hidden"
    :name="`steps[${index}][completed]`"
    :value="step.completed ? '1' : '0'"
>
```

Con esta estructura Laravel recibe correctamente cada paso con su descripción y estado.

### Actualización del arreglo en Alpine.js

El arreglo `steps` también fue modificado para almacenar objetos en lugar de cadenas de texto.

Al cargar una idea existente se utilizaron los campos necesarios:

```blade
steps: @js(
    $idea->steps->map->only([
        'id',
        'description',
        'completed',
    ])
)
```

Cuando se agrega un nuevo paso desde el modal, se incorpora un objeto con el estado inicial en `false`.

```javascript
steps.push({
    description: newStep.trim(),
    completed: false
});

newStep = '';
```

### Validación de los pasos

Las reglas de validación fueron ajustadas para aceptar la nueva estructura.

```php
'steps' => ['nullable', 'array'],
'steps.*.description' => ['required', 'string', 'max:255'],
'steps.*.completed' => ['required', 'boolean'],
```

Laravel ahora valida individualmente la descripción y el estado de cada paso.

### Sincronización de pasos

Actualizar los pasos resulta más complejo que crearlos, debido a que algunos pueden eliminarse, otros modificarse y otros agregarse.

Para simplificar el proceso, se utilizó el formulario como fuente de verdad.

Dentro de una transacción primero se actualiza la idea:

```php
$idea->update($data);
```

Después se eliminan todos los pasos existentes:

```php
$idea->steps()->delete();
```

Finalmente se crean nuevamente utilizando la información recibida desde el formulario:

```php
$idea->steps()->createMany(
    $attributes['steps'] ?? []
);
```

Este enfoque evita una sincronización más compleja mediante `upsert()` y resulta suficiente para la cantidad reducida de pasos manejada por la aplicación.

### Uso de una transacción

Todo el proceso se ejecuta dentro de una transacción de base de datos.

```php
DB::transaction(function () use ($idea, $data, $attributes) {
    $idea->update($data);

    $idea->steps()->delete();

    $idea->steps()->createMany(
        $attributes['steps'] ?? []
    );
});
```

Si alguna de las operaciones falla, Laravel revierte automáticamente todos los cambios.

### Uso de la acción en el controlador

El método `update()` del controlador recibe la nueva Action Class mediante inyección de dependencias.

```php
public function update(
    StoreIdeaRequest $request,
    Idea $idea,
    UpdateIdea $action
) {
    $action->handle(
        $request->safe()->all(),
        $idea
    );

    return back()->with(
        'success',
        'Idea updated.'
    );
}
```

Esto mantiene el controlador limpio y concentra la lógica de actualización dentro de una clase especializada.

### Separación de las acciones

Después de comparar `CreateIdea` y `UpdateIdea`, se decidió mantener ambas clases separadas.

Aunque comparten ciertas operaciones, la actualización necesita eliminar y reconstruir los pasos, mientras que la creación únicamente debe insertarlos.

Unificar ambas clases habría requerido múltiples condiciones y reducido la claridad del código.

### Organización de las pruebas

Las pruebas relacionadas con ideas se organizaron dentro de una carpeta específica.

```text
tests/Browser/Idea/
├── CreateIdeaTest.php
└── UpdateIdeaTest.php
```

También se agregaron pruebas para verificar que el modal de edición muestre correctamente los valores existentes.

```php
->assertValue('input[name="title"]', $idea->title)
->assertValue(
    'textarea[name="description"]',
    $idea->description
);
```

Estas pruebas permiten confirmar que los datos iniciales se cargan correctamente antes de realizar una actualización.

### Resultado del episodio

Al finalizar el episodio se implementó una Action Class dedicada a actualizar ideas. La aplicación ahora permite modificar el título, la descripción, el estado, los enlaces, la imagen y los pasos accionables.

Además, los pasos fueron reestructurados para conservar su estado de completado, el proceso quedó protegido mediante una transacción y las pruebas fueron reorganizadas para cubrir tanto la creación como la actualización de ideas.
