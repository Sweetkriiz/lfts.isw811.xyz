# Actionable Steps

## Episodio 35 - Actionable Steps

### Desarrollo del episodio

En este episodio se implementó el soporte para agregar **pasos accionables** a cada idea. Al igual que los enlaces, una idea puede tener uno o varios pasos, por lo que se reutilizó gran parte de la interfaz desarrollada en el episodio anterior utilizando **Alpine.js** para administrar una lista dinámica.

Cada paso representa una tarea que posteriormente podrá marcarse como completada o pendiente.

### Creación de la sección de pasos

Dentro del modal de creación de ideas se agregó un nuevo `fieldset` para administrar los pasos.

Cada nuevo paso se introduce mediante un campo de texto acompañado por un botón para agregarlo a la lista.

```blade
<input
    type="text"
    id="new-step"
    placeholder="What needs to be done?"
    class="input flex-1"
    x-model="newStep"
>
```

El botón agrega el contenido del campo al arreglo de pasos y posteriormente limpia el valor ingresado.

```javascript
steps.push(newStep);

newStep = '';
```

Para administrar esta información se agregaron dos nuevas propiedades al componente de Alpine.js.

```javascript
newStep: '',
steps: [],
```

Mientras el usuario escribe, `newStep` mantiene sincronizado el contenido del campo y `steps` almacena todos los pasos agregados.

### Mostrar y eliminar pasos

Cada elemento almacenado dentro del arreglo se muestra inmediatamente debajo del formulario utilizando `x-for`.

Cada paso puede eliminarse mediante un botón que utiliza `splice()` para remover el elemento correspondiente del arreglo.

```javascript
steps.splice(index, 1);
```

De esta forma el usuario puede agregar o eliminar pasos antes de enviar el formulario.

### Validación de la solicitud

Al enviarse el formulario se agregaron nuevas reglas de validación para verificar que los pasos correspondan a un arreglo y que cada elemento sea una cadena de texto válida.

```php
'steps' => ['nullable', 'array'],
'steps.*' => ['string', 'max:255'],
```

Con estas reglas Laravel valida automáticamente cada paso recibido antes de almacenarlo.

### Uso de `safe()`

Para crear la idea únicamente con los atributos correspondientes a la tabla `ideas`, se utilizó el método `safe()` del objeto `FormRequest`.

Esto permite excluir el arreglo de pasos antes de crear el registro principal.

```php
$request->safe()->except('steps');
```

Una vez creada la idea, los pasos se almacenan utilizando la relación correspondiente.

### Inserción de los pasos

Los pasos recibidos desde el formulario llegan como un arreglo de cadenas.

Antes de almacenarlos fue necesario transformar cada elemento al formato esperado por el modelo `Step`.

Para ello se utilizó una colección junto con el método `map()`.

```php
collect($request->steps)
    ->map(fn ($step) => [
        'description' => $step,
    ]);
```

Finalmente se utilizó la relación entre `Idea` y `Step` para insertar todos los registros de una sola vez mediante `createMany()`.

```php
$idea->steps()->createMany($steps);
```

Cada paso queda asociado automáticamente a la idea recién creada.

### Mostrar los pasos en la vista

En la página de detalle de una idea se agregó una nueva sección llamada **Actionable Steps**.

Esta sección únicamente se muestra cuando la idea posee pasos registrados.

Cada paso presenta:

- Un indicador de estado.
- La descripción correspondiente.

Cuando no existen pasos, la sección simplemente no se renderiza.

### Marcar pasos como completados

Posteriormente se agregó la posibilidad de marcar un paso como completado o pendiente.

Cada paso fue envuelto dentro de un formulario independiente que envía una solicitud `PATCH` para actualizar únicamente su estado.

Se registró una nueva ruta para actualizar un paso individual.

```php
Route::patch('/steps/{step}', [StepController::class, 'update'])
    ->name('steps.update');
```

También se creó un nuevo `StepController` con la acción `update`.

Dentro de este controlador se invierte el valor actual del campo `completed`.

```php
$step->update([
    'completed' => ! $step->completed,
]);
```

Después de actualizar el estado, el usuario es redirigido nuevamente a la página anterior.

### Indicador visual

Finalmente se mejoró la presentación visual de los pasos completados.

Cuando un paso se encuentra marcado como completado:

- Se muestra un indicador visual de confirmación.
- La descripción aparece tachada (`line-through`).
- El texto utiliza un color más tenue para indicar que la tarea ya fue realizada.

De esta manera resulta sencillo distinguir entre tareas pendientes y tareas finalizadas.

### Resultado del episodio

Al finalizar el episodio se implementó un sistema completo de pasos accionables para cada idea. Ahora el usuario puede agregar múltiples tareas durante la creación de una idea, almacenarlas en una tabla independiente, visualizarlas posteriormente y actualizar su estado entre pendiente y completado mediante solicitudes `PATCH`. Esta funcionalidad aprovecha Alpine.js para la interacción dinámica del formulario y Laravel para la validación, persistencia y actualización de los datos. 
