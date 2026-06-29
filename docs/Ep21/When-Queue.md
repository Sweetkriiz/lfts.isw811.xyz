# When to Queue it Up

## Episodio 21: When to Queue it Up

### Desarrollo del episodio

En este episodio se introdujo el uso de **Queues (colas)** en Laravel para ejecutar tareas en segundo plano. Se explicó cómo las notificaciones, el envío de correos electrónicos y otros procesos que consumen tiempo pueden ejecutarse de forma asíncrona, mejorando el rendimiento de la aplicación y reduciendo el tiempo de espera para el usuario.

---

## ¿Qué se hizo?

Inicialmente se cambió el tema de DaisyUI a **Sunset**, configurándolo como el tema predeterminado desde `app.css`, evitando así tener que definir el atributo `data-theme` en el layout de la aplicación.

Luego se revisó el flujo de creación de una idea. Después de guardar una nueva idea en la base de datos, el controlador continúa enviando una notificación al usuario autenticado mediante el método `notify()`:

```php
Auth::user()->notify(new IdeaPublished($idea));
```

Aunque el correo se envía correctamente, se explicó que esta operación puede tardar algunos segundos en un entorno de producción, especialmente cuando deben enviarse múltiples correos electrónicos. Para evitar que el usuario espere, Laravel permite colocar estas tareas en una **cola (Queue)**.

Para lograrlo, la notificación implementa la interfaz `ShouldQueue` y utiliza el trait `Queueable`:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class IdeaPublished extends Notification implements ShouldQueue
{
    use Queueable;
}
```

Cuando Laravel detecta que una notificación implementa `ShouldQueue`, automáticamente la coloca en la cola en lugar de ejecutarla inmediatamente.

También se explicó la diferencia entre los principales componentes del sistema de colas:

- **Job:** tarea que debe ejecutarse.
- **Queue:** cola donde se almacenan los trabajos pendientes.
- **Worker:** proceso encargado de ejecutar los trabajos almacenados en la cola.

Para procesar los trabajos pendientes se ejecutó el siguiente comando:

```bash
php artisan queue:work
```

Este comando inicia un **worker**, el cual toma los trabajos pendientes y los ejecuta.

Posteriormente se mostró cómo crear un trabajo personalizado utilizando Artisan:

```bash
php artisan make:job UpdateIdeaStatistics
```

Laravel crea automáticamente una nueva clase dentro del directorio `app/Jobs`, donde la lógica principal se implementa en el método `handle()`.

Ejemplo:

```php
public function handle(): void
{
    logger('The UpdateIdeaStatistics job is being processed.');
}
```

El trabajo se envía a la cola utilizando el método `dispatch()`:

```php
UpdateIdeaStatistics::dispatch();
```

Una vez despachado, el trabajo permanece almacenado en la tabla `jobs` de la base de datos hasta que un worker lo procesa mediante:

```bash
php artisan queue:work
```

Finalmente se explicó que la conexión utilizada para las colas se configura mediante la variable:

```env
QUEUE_CONNECTION=database
```

Con esta configuración, todos los trabajos pendientes se almacenan temporalmente en la tabla `jobs`. Después de ser procesados por un worker, desaparecen automáticamente de dicha tabla.

---

## Conceptos aprendidos

- Uso de colas (Queues) para ejecutar procesos en segundo plano.
- Implementación de la interfaz `ShouldQueue`.
- Uso del trait `Queueable`.
- Procesamiento de trabajos mediante `php artisan queue:work`.
- Creación de trabajos personalizados con `php artisan make:job`.
- Envío de trabajos a la cola utilizando `dispatch()`.
- Registro de información mediante `logger()`.
- Almacenamiento temporal de trabajos en la tabla `jobs`.
- Diferencia entre **Job**, **Queue** y **Worker**.
- Beneficios de ejecutar tareas asíncronas para mejorar el rendimiento de la aplicación.

---
