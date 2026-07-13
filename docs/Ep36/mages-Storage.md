# Upload Featured Images To Storage

## Episodio 36 - Upload Featured Images To Storage

### Desarrollo del episodio

En este episodio se agregó soporte para que cada idea pueda incluir una **imagen destacada**. Para ello se modificó el formulario de creación de ideas, se implementó la carga de archivos, se almacenó la imagen en el sistema de archivos de Laravel y finalmente se mostró tanto en la vista de detalle como en la lista principal de ideas.

### Agregar el campo para subir imágenes

Dentro del formulario de creación de ideas se añadió un nuevo campo de tipo `file`, permitiendo seleccionar únicamente archivos de imagen.

```blade
<label for="image" class="label">Featured Image</label>

<input
    type="file"
    name="image"
    accept="image/*"
>

<x-form.error name="image" />
```

El atributo `accept="image/*"` limita la selección de archivos a imágenes compatibles con el navegador.

### Configuración del formulario

Como el formulario ahora envía archivos, fue necesario modificar el atributo `enctype`.

```html
enctype="multipart/form-data"
```

Este tipo de codificación permite que el navegador envíe correctamente tanto los datos del formulario como los archivos seleccionados.

### Validación de la imagen

En el `StoreIdeaRequest` se agregaron nuevas reglas de validación para el archivo recibido.

```php
'image' => [
    'nullable',
    'image',
    'max:5120',
],
```

Con estas reglas la imagen es opcional, debe corresponder a un archivo de imagen válido y no puede superar aproximadamente los cinco megabytes.

### Almacenamiento de la imagen

Después de crear la idea, la imagen se almacena utilizando el sistema de almacenamiento de Laravel.

```php
$request->image->store(
    'ideas',
    'public'
);
```

Laravel genera automáticamente un nombre único para cada archivo y lo almacena dentro del disco público.

Una vez obtenida la ruta del archivo, ésta se guarda en el campo `image_path` de la idea.

```php
$idea->update([
    'image_path' => $imagePath,
]);
```

De esta manera cada idea conserva la ubicación de su imagen destacada.

### Exclusión del archivo durante la creación

Como el atributo `image` no pertenece directamente a la tabla `ideas`, fue necesario excluirlo de los datos enviados al método `create()`.

Para ello se utilizó el método `safe()` del `FormRequest`.

```php
$request
    ->safe()
    ->except([
        'steps',
        'image',
    ]);
```

Posteriormente la imagen se procesa de forma independiente.

### Crear el enlace simbólico

Las imágenes se almacenan dentro del directorio `storage`, por lo que fue necesario crear el enlace simbólico hacia la carpeta pública utilizando Artisan.

```bash
php artisan storage:link
```

Este comando crea la carpeta `public/storage`, permitiendo que los archivos almacenados puedan ser accesibles desde el navegador.

### Mostrar la imagen

En la vista de detalle de la idea se agregó una condición para mostrar la imagen únicamente cuando exista una ruta almacenada.

```blade
@if ($idea->image_path)
    <img
        src="{{ asset('storage/' . $idea->image_path) }}"
        alt=""
    >
@endif
```

Para obtener la URL pública se utilizó el helper `asset()` apuntando al directorio `storage`.

### Mejoras de presentación

Posteriormente se ajustó la apariencia de la imagen utilizando clases de Tailwind CSS.

Entre las principales mejoras se encuentran:

- Bordes redondeados.
- Ocultar el contenido que sobresale del contenedor.
- Imagen ocupando todo el ancho disponible.
- Ajuste mediante `object-cover` para mantener una buena presentación independientemente del tamaño original.

Estas modificaciones permiten integrar la imagen con el diseño general de la aplicación.

### Mostrar miniaturas en la lista de ideas

Finalmente se reutilizó el mismo componente visual para mostrar una miniatura de la imagen dentro de cada tarjeta de la página principal.

La imagen se coloca en la parte superior de la tarjeta y solamente se renderiza cuando la idea posee una imagen asociada.

Con esto, la lista principal resulta más visual y facilita identificar rápidamente cada idea.

### Resultado del episodio

Al finalizar el episodio se implementó el soporte completo para imágenes destacadas. Ahora el formulario permite subir archivos de imagen, Laravel valida y almacena el archivo dentro del disco público, registra la ruta en la base de datos y muestra la imagen tanto en la vista individual como en las tarjetas del listado principal. Además, se configuró el enlace simbólico mediante `php artisan storage:link` para que los archivos almacenados sean accesibles desde el navegador.
