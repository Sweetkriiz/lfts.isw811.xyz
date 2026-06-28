# Eloquent Relationships

## Episodio 16: Eloquent Relationships

### Desarrollo del episodio

En este episodio se implementan las relaciones entre los modelos `Idea` y `User` utilizando Eloquent ORM. Gracias a estas relaciones, Laravel permite acceder a los registros relacionados de forma sencilla sin escribir consultas SQL manualmente.

---

## Relación `belongsTo` en el modelo `Idea`

Cada idea pertenece a un único usuario. Para representar esta relación se agrega el método `user()` en el modelo `Idea`.

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Idea extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

Con esta relación es posible obtener el usuario que creó una idea:

```php
$idea = Idea::first();

$idea->user;
```

Laravel ejecuta automáticamente una consulta similar a:

```sql
SELECT * FROM users
WHERE id = idea.user_id;
```

> Aunque la relación se define como un método (`user()`), normalmente se accede como una propiedad (`$idea->user`). Laravel resuelve esto internamente mediante propiedades dinámicas.

---

## Relación `hasMany` en el modelo `User`

La relación inversa indica que un usuario puede crear múltiples ideas.

En el modelo `User` se agrega el método `ideas()`.

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function ideas(): HasMany
{
    return $this->hasMany(Idea::class);
}
```

Ahora se pueden obtener todas las ideas de un usuario autenticado:

```php
$user = User::first();

$user->ideas;
```

El resultado es una colección (`Collection`) de objetos `Idea`.

También es posible acceder a una idea específica:

```php
$user->ideas[0]->description;
```

---

## Probando las relaciones con Tinker

Laravel permite verificar las relaciones desde la consola utilizando Tinker.

Obtener la primera idea:

```bash
php artisan tinker
```

```php
Idea::first();
```

Obtener el usuario propietario:

```php
Idea::first()->user;
```

Obtener el primer usuario:

```php
User::first();
```

Obtener todas sus ideas:

```php
User::first()->ideas;
```

---

## Refactorizando el controlador

Antes se consultaban las ideas manualmente utilizando el `user_id`.

Ahora simplemente se utiliza la relación definida en el modelo.

Antes:

```php
Idea::where('user_id', Auth::id())->get();
```

Ahora:

```php
Auth::user()->ideas;
```

El código queda mucho más limpio y expresivo, aprovechando las capacidades de Eloquent.

---

## Creando registros mediante la relación

También es posible crear una nueva idea directamente desde la relación del usuario.

Antes:

```php
Idea::create([
    'description' => $request->description,
    'state' => 'pending',
    'user_id' => Auth::id(),
]);
```

Ahora:

```php
Auth::user()->ideas()->create([
    'description' => $request->description,
    'state' => 'pending',
]);
```

Laravel asigna automáticamente el valor de `user_id`, por lo que ya no es necesario incluirlo manualmente.

Es importante notar la diferencia:

- `ideas` devuelve la colección de ideas.
- `ideas()` devuelve el objeto de relación, permitiendo construir consultas o crear registros asociados.

---

## Tipado para mejorar el autocompletado

El instructor explica que algunos editores muestran advertencias porque no reconocen las propiedades dinámicas de Eloquent.

Una solución consiste en utilizar el plugin **Laravel Idea** (PhpStorm), que genera automáticamente los archivos auxiliares para mejorar el autocompletado.

Otra alternativa es documentar las propiedades del modelo mediante PHPDoc.

Ejemplo:

```php
/**
 * @property-read Collection<int, \App\Models\Idea> $ideas
 */
```

Esto mejora el reconocimiento de relaciones en editores como Visual Studio Code.

---

## Problema de autorización detectado

Al finalizar el episodio se identifica un problema de seguridad.

Aunque cada usuario solo visualiza sus propias ideas en el listado, cualquier persona autenticada puede modificar manualmente la URL para acceder a ideas que pertenecen a otros usuarios.

Ejemplo:

```
/ideas/4
```

Si el usuario cambia la URL por:

```
/ideas/1
```

Puede visualizar una idea que no le pertenece.

Este comportamiento representa una vulnerabilidad de autorización y será solucionado en el siguiente episodio utilizando las herramientas de autorización que ofrece Laravel.

---

## Conceptos aprendidos

- Relaciones `belongsTo` y `hasMany`.
- Acceso a modelos relacionados mediante propiedades dinámicas.
- Uso de Tinker para probar relaciones entre modelos.
- Refactorización del controlador utilizando relaciones de Eloquent.
- Creación de registros mediante relaciones (`create()`).
- Diferencia entre acceder a una relación como propiedad y como método.
- Uso de anotaciones PHPDoc para mejorar el autocompletado.
- Identificación de un problema de autorización que será corregido posteriormente.

---
