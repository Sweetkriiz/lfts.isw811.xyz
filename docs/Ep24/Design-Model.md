# Design Your Model Layer

## Episodio 24 – Design Your Model Layer

## Desarrollo del Episodio

En este episodio se construye la estructura base del dominio de la aplicación. Se crean los modelos principales, las migraciones, las relaciones entre entidades, los enums para controlar el estado de las ideas, las factories para generar datos de prueba y las primeras pruebas unitarias para validar el funcionamiento del modelo. :contentReference[oaicite:0]{index=0}

---

# Desarrollo del episodio

## 1. Creación del modelo Idea

Se utiliza Artisan para generar automáticamente la mayor parte de la estructura necesaria para el modelo **Idea**, incluyendo:

- Modelo.
- Factory.
- Migration.
- Form Request.
- Policy.
- Controller.

Con esto se obtiene una base completa para comenzar el desarrollo del proyecto. :contentReference[oaicite:1]{index=1}

---

## 2. Diseño de la migración

Se define la estructura de la tabla `ideas`.

Cada idea contiene:

- Usuario propietario.
- Título.
- Descripción opcional.
- Estado.
- Imagen opcional.
- Lista de enlaces almacenada en formato JSON.
- Fechas de creación y actualización.

Además, la relación con el usuario se implementa mediante una llave foránea utilizando `foreignId()->constrained()->cascadeOnDelete()`. :contentReference[oaicite:2]{index=2}

---

## 3. Configuración de Eloquent

Se configuran algunas opciones globales para los modelos:

- Desactivar el uso de atributos protegidos (`unguard()`).
- Habilitar el modo estricto de Eloquent.
- Activar la carga automática de relaciones para reducir problemas de rendimiento (N+1 Queries).

Estas configuraciones ayudan a detectar errores durante el desarrollo y mejoran el comportamiento de la aplicación. :contentReference[oaicite:3]{index=3}

---

## 4. Uso de Enum para el estado

Se crea el enum `IdeaStatus` para representar los posibles estados de una idea:

- Pending
- In Progress
- Completed

Posteriormente se configura un **cast** en el modelo para que Laravel convierta automáticamente el valor almacenado en la base de datos a una instancia del enum.

Además, el enum incorpora un método `label()` que devuelve un texto legible para utilizar posteriormente en la interfaz gráfica. :contentReference[oaicite:4]{index=4}

---

## 5. Relaciones entre modelos

Se implementan las relaciones principales del proyecto.

### Idea pertenece a un usuario

```php
belongsTo(User::class)
```

### Usuario tiene muchas ideas

```php
hasMany(Idea::class)
```

### Idea tiene muchos pasos

```php
hasMany(Step::class)
```

### Paso pertenece a una idea

```php
belongsTo(Idea::class)
```

Estas relaciones permiten navegar fácilmente entre los modelos utilizando Eloquent ORM. :contentReference[oaicite:5]{index=5}

---

## 6. Creación del modelo Step

Se crea el modelo **Step** junto con:

- Factory.
- Migration.

La tabla almacena:

- Idea asociada.
- Descripción.
- Estado de completado.
- Fechas de creación y actualización.

También se agrega la llave foránea hacia la tabla `ideas`. :contentReference[oaicite:6]{index=6}

---

## 7. Configuración de Factories

Se actualizan las factories para generar datos falsos automáticamente.

### IdeaFactory

Genera:

- Usuario.
- Título.
- Descripción.
- Lista de enlaces.

### StepFactory

Genera:

- Idea asociada.
- Descripción.
- Estado inicial (`completed = false`).

Esto facilita la creación de datos durante pruebas y desarrollo. :contentReference[oaicite:7]{index=7}

---

## 8. Valores por defecto del modelo

Se configuran atributos iniciales directamente en los modelos para evitar repetir valores durante la creación de registros.

Por ejemplo:

- Estado inicial de una idea: `Pending`.
- Estado inicial de un paso: `completed = false`.

De esta forma los valores por defecto quedan centralizados dentro del modelo. :contentReference[oaicite:8]{index=8}

---

## 9. Pruebas unitarias

Se crean las primeras pruebas unitarias del proyecto utilizando Pest.

Se validan dos comportamientos principales:

- Una idea pertenece correctamente a un usuario.
- Una idea puede tener pasos asociados.

Durante el proceso se corrigen algunos errores detectados por las pruebas, como:

- Configuración de la base de datos de pruebas.
- Relaciones faltantes.
- Valores por defecto en migraciones.
- Configuración de `RefreshDatabase`.

Una vez realizadas las correcciones, todas las pruebas finalizan exitosamente. :contentReference[oaicite:9]{index=9}

---

# Archivos creados o modificados

- `app/Models/Idea.php`
- `app/Models/Step.php`
- `app/Models/User.php`
- `app/IdeaStatus.php`
- `database/migrations/*create_ideas_table.php`
- `database/migrations/*create_steps_table.php`
- `database/factories/IdeaFactory.php`
- `database/factories/StepFactory.php`
- `tests/Unit/IdeaTest.php`
- `tests/Pest.php`

---

# Comandos utilizados

Crear el modelo Idea con todos sus componentes:

```bash
php artisan make:model Idea -mfprc
```

Crear el enum:

```bash
php artisan make:enum IdeaStatus
```

Crear el modelo Step:

```bash
php artisan make:model Step -mf
```

Ejecutar migraciones:

```bash
php artisan migrate
```

Abrir Tinker:

```bash
php artisan tinker
```

Generar datos de prueba:

```php
App\Models\Idea::factory()->raw();
```

Ejecutar pruebas:

```bash
php artisan test tests/Unit/IdeaTest.php
```

---

# Resultado del episodio

Al finalizar este episodio queda implementada la base del dominio de la aplicación.

Se construyó la estructura de datos del proyecto mediante modelos, migraciones, relaciones, enums y factories. Además, se escribieron pruebas unitarias para validar que las relaciones funcionen correctamente, estableciendo una base sólida para comenzar el desarrollo de la interfaz de usuario en los siguientes episodios.