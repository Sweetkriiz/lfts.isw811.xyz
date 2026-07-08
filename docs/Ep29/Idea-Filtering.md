# Idea Filtering

## Episodio 29 - Idea Filtering

### Desarrollo del episodio

En este episodio se implementó un sistema de filtrado para las ideas según su estado, permitiendo mostrar únicamente aquellas que coinciden con un filtro seleccionado desde la interfaz.

Inicialmente se comprobó que el filtrado podía realizarse directamente mediante una consulta Eloquent utilizando el método `where()` sobre el campo `status`.

```php
Idea::where('status', 'pending')->get();
```

Posteriormente el filtro dejó de estar fijo y pasó a obtenerse dinámicamente desde el **query string** de la URL utilizando el objeto `request()`.

```php
request('status');
```

Esto permitió realizar consultas como:

```text
/ideas?status=pending
/ideas?status=in-progress
/ideas?status=completed
```

De esta manera el mismo controlador puede responder a diferentes filtros sin necesidad de crear nuevas rutas.

### Uso del método when()

Para evitar aplicar el filtro cuando el usuario no selecciona ningún estado, se utilizó el método `when()` de Eloquent.

```php
Idea::query()
    ->when(request('status'), function ($query, $status) {
        $query->where('status', $status);
    })
    ->get();
```

Con esta implementación:

- Si existe un valor para `status`, se agrega el `where()`.
- Si no existe, se muestran todas las ideas.

Esto permite mantener el código limpio y evita múltiples estructuras `if`.

### Creación de filtros en la interfaz

Se agregaron botones tipo **pill** para facilitar la selección de estados desde la interfaz.

Cada botón genera automáticamente la URL con el parámetro correspondiente.

Ejemplo:

```text
All
Pending
In Progress
Completed
```

Además, el botón correspondiente al filtro activo cambia de estilo para indicar visualmente cuál opción está seleccionada.

### Generación dinámica de los filtros

Para evitar escribir manualmente cada botón, se recorrieron todos los valores definidos en el Enum `IdeaStatus`.

```php
IdeaStatus::cases()
```

De esta manera cualquier nuevo estado agregado al Enum aparecerá automáticamente en la interfaz sin necesidad de modificar la vista.

### Conteo de ideas por estado

Posteriormente se añadió un contador junto a cada filtro para indicar cuántas ideas existen en cada estado.

Para obtener esta información se utilizó una consulta SQL agrupando los registros por estado.

```sql
SELECT status, COUNT(*)
FROM ideas
GROUP BY status;
```

En Laravel esta consulta fue implementada mediante:

```php
selectRaw()
groupBy()
```

Esto permitió obtener el número de ideas pendientes, en progreso y completadas.

### Conversión de resultados

Los resultados obtenidos desde la base de datos fueron transformados utilizando las colecciones de Laravel.

Se empleó el método:

```php
pluck()
```

para construir una colección donde:

- La llave corresponde al estado.
- El valor corresponde al número de registros.

Posteriormente se utilizó:

```php
mapWithKeys()
```

para garantizar que todos los estados del Enum aparezcan en la colección, incluso cuando alguno tenga un conteo de cero.

### Conteo total de ideas

Además de los estados individuales, se agregó un filtro **All**, el cual muestra todas las ideas del usuario.

Su contador se obtiene utilizando:

```php
$user->ideas()->count();
```

De esta forma la interfaz muestra tanto el total de ideas como la cantidad correspondiente a cada estado.

### Refactorización

Una vez comprobado el funcionamiento del sistema, toda la lógica encargada de calcular los conteos fue movida al modelo `Idea`.

Se creó un método estático encargado de devolver los conteos por estado.

Con ello el controlador quedó considerablemente más limpio y la lógica pasó a encontrarse en el lugar donde corresponde.

### Validación del filtro

Durante la revisión realizada por CodeRabbit se detectó que el parámetro `status` recibido desde la URL podía contener valores inválidos.

Como mejora se validó que únicamente se acepten los estados definidos dentro del Enum.

En caso de recibir un valor no permitido, el filtro simplemente es ignorado y se muestran todas las ideas.

### Archivos modificados

```text
app/Http/Controllers/IdeaController.php
app/Models/Idea.php
app/Enums/IdeaStatus.php
resources/views/ideas/index.blade.php
resources/views/components/card.blade.php
```
