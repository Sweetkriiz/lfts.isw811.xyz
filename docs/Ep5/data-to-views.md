# Passing Data to Views

## Episodio 05 - Passing Data to Views

### Desarrollo del episodio

En este episodio aprendí cómo enviar información desde una ruta hacia una vista en Laravel. Anteriormente las vistas mostraban contenido estático, pero ahora es posible pasar variables dinámicas para que la información mostrada cambie según los datos recibidos.

Laravel permite enviar datos a una vista mediante un arreglo asociativo, donde cada clave se convierte en una variable disponible dentro del archivo Blade.

### Código utilizado

#### Enviar datos a una vista

```php
Route::get('/', function () {
    return view('welcome', [
        'greeting' => 'Hello',
        'person' => 'John'
    ]);
});
```

#### Mostrar los datos en la vista

```blade
<h1>{{ $greeting }}, {{ $person }}</h1>
```

### Lectura de parámetros desde la URL

También aprendí a obtener valores desde la query string utilizando el helper `request()`.

```php
Route::get('/', function () {
    return view('welcome', [
        'greeting' => 'Hello',
        'person' => request('person', 'World')
    ]);
});
```

Ejemplo de URL:

```text
http://localhost:8000/?person=Sarah
```

Resultado:

```text
Hello, Sarah
```

Si no se envía el parámetro `person`, Laravel utiliza el valor por defecto:

```text
Hello, World
```

### Seguridad con Blade

Una parte importante del episodio fue comprender cómo Blade protege automáticamente contra ataques XSS (Cross-Site Scripting).

Forma segura:

```blade
{{ $person }}
```

Forma no recomendada cuando los datos provienen del usuario:

```blade
{!! $person !!}
```

Las dobles llaves `{{ }}` escapan automáticamente caracteres especiales y evitan la ejecución de código malicioso enviado por un usuario.

### Archivos modificados

- routes/web.php
- resources/views/welcome.blade.php


