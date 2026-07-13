# Deploy And Then Implement A Feature Request

## Episodio 42 - Deploy And Then Implement A Feature Request

### Desarrollo del episodio

En este episodio se realizaron las verificaciones finales del proyecto antes de publicarlo en producción. Se ejecutó el formateador de código, se revisaron todas las pruebas automatizadas, se solucionaron pequeños errores encontrados durante la ejecución y finalmente se desplegó la aplicación utilizando **Laravel Forge**.

Además, se implementó una nueva funcionalidad que permite escribir las descripciones de las ideas utilizando **Markdown**, aprovechando los Accessors de Eloquent y las utilidades incluidas en Laravel.

### Formatear el proyecto

Antes de realizar el despliegue se ejecutó el formateador del proyecto.

```bash
composer run format
```

Este comando aplicó automáticamente el estilo de código definido para la aplicación.

### Ejecutar las pruebas

Posteriormente se ejecutó toda la suite de pruebas.

```bash
php artisan test
```

Durante la ejecución aparecieron algunos errores relacionados con las rutas de autenticación.

Después de revisar los controladores correspondientes, se actualizaron los redireccionamientos para utilizar la nueva ruta del listado de ideas.

```php
return redirect()->route('ideas.index');
```

También fue necesario actualizar las pruebas para reflejar correctamente el nuevo flujo de navegación.

```php
->assertRouteIs('ideas.index');
```

Una vez realizados los cambios, todas las pruebas finalizaron correctamente.

### Despliegue con Laravel Forge

Con el proyecto validado se envió el código al repositorio remoto.

```bash
git push
```

Laravel Forge detectó automáticamente los cambios mediante un Webhook y comenzó un nuevo despliegue.

Durante el primer intento apareció un error relacionado con la configuración de la base de datos.

El proyecto utilizaba SQLite localmente, mientras que el servidor estaba configurado para MySQL, generando incompatibilidades con algunos valores por defecto.

Para solucionarlo se modificó la configuración del entorno en Forge para utilizar SQLite.

Una vez actualizado el archivo `.env`, se ejecutó nuevamente el despliegue y la aplicación quedó funcionando correctamente en producción.

### Nuevo requerimiento

Después del despliegue se decidió implementar una mejora solicitada para las descripciones de las ideas.

Hasta ese momento la descripción se mostraba como texto plano.

El objetivo fue permitir que los usuarios escribieran contenido utilizando sintaxis Markdown.

### Crear un Accessor

Para evitar modificar directamente el contenido almacenado en la base de datos, se creó un nuevo Accessor dentro del modelo `Idea`.

```php
public function formattedDescription(): Attribute
{
    return Attribute::get(
        fn ($value, $attributes) => $attributes['description']
    );
}
```

Inicialmente el Accessor simplemente devolvía la descripción original.

Esto permitió crear un punto central desde donde posteriormente aplicar cualquier transformación.

### Convertir Markdown a HTML

Una vez creado el Accessor, se aprovechó el helper `Str::markdown()` incluido en Laravel.

```php
return Attribute::get(
    fn ($value, $attributes) => Str::markdown(
        $attributes['description']
    )
);
```

Laravel convierte automáticamente la sintaxis Markdown en HTML listo para mostrarse en la vista.

### Mostrar el contenido

Como el contenido ya llega convertido en HTML, fue necesario evitar el escape automático de Blade.

En lugar de utilizar:

```blade
{{ $idea->formattedDescription }}
```

se reemplazó por:

```blade
{!! $idea->formattedDescription !!}
```

De esta forma el navegador interpreta correctamente el HTML generado por Markdown.

### Ajustar el componente

El componente que mostraba la descripción utilizaba internamente una etiqueta `<a>`.

Como ahora la descripción puede contener enlaces, listas y otros elementos HTML, se cambió el contenedor por un `<div>` para evitar HTML inválido.

### Mejorar la presentación

Aunque el Markdown ya funcionaba, la apariencia seguía siendo muy básica.

Para mejorar el resultado se instaló el plugin de tipografía de Tailwind CSS.

```bash
npm install @tailwindcss/typography
```

Posteriormente se registró el plugin dentro de la configuración de Tailwind.

```css
@plugin "@tailwindcss/typography";
```

Finalmente se aplicaron las clases correspondientes al contenedor.

```html
class="prose prose-invert"
```

Estas utilidades proporcionan automáticamente estilos para:

- Encabezados.
- Párrafos.
- Listas.
- Enlaces.
- Texto en negrita.
- Código.
- Citas.

Además, `prose-invert` adapta los colores para funcionar correctamente con el tema oscuro de la aplicación.

### Prueba unitaria

Para verificar el nuevo Accessor se creó una prueba unitaria.

Se construyó una idea manualmente.

```php
$idea = new Idea();

$idea->description = 'Hello **World**';
```

Luego se comprobó que la propiedad calculada devolviera el HTML esperado.

```php
expect($idea->formattedDescription)
    ->toEqual('<p>Hello <strong>World</strong></p>');
```

Con esta prueba se garantiza que futuras modificaciones no rompan el formateo Markdown.

### Despliegue de la nueva funcionalidad

Después de ejecutar nuevamente el formateador y todas las pruebas, se realizó un nuevo despliegue.

```bash
composer run format

php artisan test

git push
```

Forge detectó automáticamente los cambios, ejecutó el despliegue y pocos segundos después la nueva funcionalidad ya estaba disponible en producción.

La aplicación ahora permite escribir descripciones utilizando Markdown, mostrando automáticamente títulos, listas, enlaces, texto en negrita y otros elementos con un formato mucho más agradable.

### Resultado del episodio

Al finalizar el episodio se completó el flujo de despliegue de la aplicación utilizando Laravel Forge y se implementó soporte completo para Markdown en las descripciones de las ideas. Gracias al uso de Accessors, `Str::markdown()`, el plugin de tipografía de Tailwind CSS y una prueba unitaria dedicada, las descripciones ahora se renderizan correctamente con un formato profesional tanto en desarrollo como en producción. 
