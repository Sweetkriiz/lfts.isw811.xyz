# Test The Create Idea Form

## Episodio 34 - Test The Create Idea Form

### Desarrollo del episodio

En este episodio se automatizó el proceso de creación de una idea mediante una prueba de navegador utilizando **Pest Browser**. Hasta este momento, el funcionamiento del modal se verificaba únicamente de forma manual, lo que implicaba el riesgo de introducir errores sin detectarlos inmediatamente.

El objetivo fue crear una prueba automatizada que simule las acciones de un usuario al crear una nueva idea desde el modal y comprobar que toda la funcionalidad continúe funcionando correctamente.

### Agregar atributos `data-test`

Para facilitar la automatización, se agregaron atributos `data-test` a los elementos con los que interactuará la prueba.

Al botón que abre el modal se le añadió el atributo:

```blade
data-test="create-idea-button"
```

Posteriormente, a cada uno de los botones de selección de estado se les agregó un atributo dinámico utilizando el valor del estado correspondiente.

Ejemplo:

```blade
data-test="button-status-{{ $status }}"
```

Esto permite identificar cada botón de forma independiente durante la ejecución de las pruebas.

### Creación de la prueba

Se creó un nuevo archivo dentro de la carpeta:

```text
tests/Browser/CreateIdeaTest.php
```

En este archivo se definió una prueba encargada de verificar el flujo completo de creación de una idea.

Inicialmente se crea un usuario utilizando una fábrica y posteriormente se autentica para poder acceder a la página de ideas.

```php
$user = User::factory()->create();

$this->actingAs($user);
```

### Automatización del formulario

La prueba visita la página principal de ideas y abre el modal utilizando el atributo `data-test`.

```php
visit('/ideas')
    ->click('[data-test="create-idea-button"]');
```

Una vez abierto el formulario, se completan todos los campos necesarios.

```php
->fill('title', 'Some example title')
->click('[data-test="button-status-completed"]')
->fill('description', 'An example description')
->click('Create')
```

Finalmente se verifica que el usuario haya sido redirigido nuevamente a la página principal.

```php
->assertPathIs('/ideas');
```

### Validación de la información almacenada

Después de enviar el formulario, no solamente se comprueba la redirección, sino también que la información haya sido almacenada correctamente en la base de datos.

Para ello se actualiza el modelo del usuario y se obtiene la primera idea asociada.

```php
$user->refresh();
```

Posteriormente se valida que los datos almacenados coincidan con los ingresados durante la prueba.

```php
expect($user->ideas->first())->toMatchArray([
    'title' => 'Some example title',
    'status' => 'completed',
    'description' => 'An example description',
]);
```

De esta forma se garantiza que la idea fue creada correctamente y quedó asociada al usuario autenticado.

### Resultado del episodio

Al finalizar el episodio se logró automatizar completamente el flujo de creación de una idea desde el modal. La prueba verifica que un usuario autenticado pueda abrir el modal, completar el formulario, seleccionar un estado, guardar la información, regresar a la página principal y confirmar que los datos fueron almacenados correctamente en la base de datos mediante pruebas automatizadas.
