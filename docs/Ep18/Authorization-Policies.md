# Authorization Using Policies

## Episodio 18 - Policies (Autorización con Policies)

## Resumen

En este episodio se introduce el uso de **Policies** para implementar la autorización en Laravel. Mientras que los **Gates** son ideales para reglas sencillas o globales, las **Policies** permiten organizar las reglas de autorización relacionadas con un modelo específico, siguiendo una estructura mucho más limpia y mantenible.

Se crea una política para el modelo **Idea**, donde se definen las reglas que determinan qué usuarios pueden visualizar, editar, actualizar, crear o eliminar una idea. Posteriormente, estas reglas son utilizadas desde el controlador para impedir que un usuario acceda o modifique recursos que no le pertenecen.

Laravel asocia automáticamente una Policy con su modelo correspondiente mediante convenciones de nombres, evitando configuraciones adicionales en la mayoría de los casos.

---

## Conceptos técnicos aprendidos

### Creación de una Policy

Se genera una política utilizando Artisan:

```bash
php artisan make:policy IdeaPolicy --model=Idea
```

Laravel crea automáticamente el directorio:

```
app/
└── Policies/
    └── IdeaPolicy.php
```

y agrega métodos que representan distintas acciones sobre el modelo.

---

### Métodos de autorización

Cada método representa una habilidad (ability) que puede tener un usuario sobre un modelo.

Ejemplos:

- `viewAny()`
- `view()`
- `create()`
- `update()`
- `delete()`
- `restore()`
- `forceDelete()`

No es obligatorio utilizar todos; solamente se implementan los necesarios.

---

### Regla de autorización para actualizar una Idea

Se implementa la regla que permite modificar únicamente las ideas creadas por el usuario autenticado.

```php
public function update(User $user, Idea $idea): bool
{
    return $user->id === $idea->user_id;
}
```

De esta manera solamente el propietario puede editar o actualizar su información.

---

### Uso de Gate::authorize()

Desde el controlador se verifica la autorización antes de ejecutar una acción.

```php
Gate::authorize('update', $idea);
```

Laravel realiza automáticamente el siguiente proceso:

1. Detecta el modelo (`Idea`).
2. Busca la política correspondiente (`IdeaPolicy`).
3. Ejecuta el método `update()`.
4. Si retorna `false`, lanza automáticamente un error **403 Forbidden**.

---

### Asociación automática entre Modelo y Policy

Laravel utiliza las convenciones para encontrar la política correspondiente.

```
Idea
   │
   ▼
IdeaPolicy
```

No es necesario indicar manualmente qué Policy pertenece al modelo siempre que se respeten los nombres convencionales.

---

### Verificación del propietario mediante relaciones

En lugar de comparar directamente los IDs:

```php
$user->id === $idea->user_id
```

también puede utilizarse el helper `is()` aprovechando la relación del modelo.

```php
return $user->is($idea->user);
```

Este método compara si ambos modelos representan el mismo registro de la base de datos.

---

### Diferenciar permisos según la acción

Una aplicación puede tener reglas distintas dependiendo de la operación.

Ejemplo:

- Cualquier miembro del equipo puede **ver** una publicación.
- Solo el administrador puede **editarla**.
- Solo el creador puede **eliminarla**.

Cada regla se implementa en un método diferente dentro de la Policy.

---

### Métodos can() y cannot()

El usuario autenticado también puede consultar permisos manualmente.

```php
if (auth()->user()->can('update', $idea)) {
    // autorizado
}
```

o

```php
if (auth()->user()->cannot('update', $idea)) {
    abort(403);
}
```

Estos métodos son los mismos utilizados internamente por las directivas Blade `@can` y `@cannot`.

---

### Autorizar acciones sin una instancia del modelo

Al crear un nuevo recurso todavía no existe un objeto `Idea`.

En ese caso se autoriza utilizando la clase del modelo.

```php
Gate::authorize('create', Idea::class);
```

Laravel identifica la Policy correspondiente utilizando la clase proporcionada.

---

### Protección de rutas mediante Middleware

La autorización también puede realizarse directamente desde las rutas.

```php
Route::get('/ideas/{idea}/edit', ...)
    ->middleware('can:update,idea');
```

El middleware verifica automáticamente si el usuario tiene permiso antes de ejecutar el controlador.

---

### Protección de todas las acciones sensibles

Jeffrey recalca que no basta con proteger únicamente la vista de edición.

También deben protegerse:

- `show`
- `edit`
- `update`
- `destroy`

para evitar que un usuario acceda mediante la URL o envíe solicitudes HTTP manualmente.

---

## Buenas prácticas aprendidas

- Organizar las reglas de autorización utilizando Policies.
- Mantener una Policy por cada modelo importante.
- Centralizar la lógica de permisos en un solo lugar.
- Utilizar `Gate::authorize()` para simplificar las validaciones.
- Aprovechar las relaciones de Eloquent con `is()`.
- Proteger tanto la interfaz como las rutas y peticiones HTTP.
- Diferenciar permisos según la acción que realiza el usuario.

---
