# Authorization Requirements

## Episodio 38 - Authorization Is A Requirement

### Desarrollo del episodio

En este episodio se reforzó la seguridad de la aplicación implementando reglas de autorización para impedir que un usuario pueda acceder o modificar ideas creadas por otros usuarios.

Antes de comenzar con la autorización, se revisó la refactorización realizada en el episodio anterior utilizando **CodeRabbit**, el cual detectó un error dentro de la nueva Action Class. La revisión mostró que una variable utilizada dentro de la transacción no estaba siendo capturada correctamente por el cierre (`closure`), provocando que los pasos de una idea no se almacenaran.

Para evitar este tipo de errores en el futuro, se amplió la prueba automatizada de creación de ideas agregando verificaciones para confirmar que los pasos también fueran insertados correctamente.

```php
expect($idea->steps)->toHaveCount(2);
```

Gracias a esta nueva prueba fue posible detectar inmediatamente el problema y confirmar que la corrección funcionaba correctamente.

### Necesidad de autorización

Posteriormente se analizó un problema de seguridad presente en la aplicación.

Aunque únicamente los usuarios autenticados podían acceder a determinadas rutas, cualquier usuario autenticado podía visualizar, editar o eliminar ideas pertenecientes a otras personas simplemente modificando la URL.

Para solucionar este problema se implementó un sistema de autorización utilizando **Policies**.

### Implementación de la Policy

Se utilizó la política asociada al modelo `Idea` para definir la regla que determina si un usuario puede modificar una idea.

En lugar de mantener todos los métodos generados automáticamente, se simplificó la política creando una única habilidad personalizada.

```php
public function workWith(User $user, Idea $idea): bool
{
    return $idea->user->is($user);
}
```

La regla únicamente permite acceder cuando el usuario autenticado corresponde al propietario de la idea.

### Autorización desde el controlador

Una vez creada la política, el controlador comenzó a verificar la autorización antes de ejecutar las acciones correspondientes.

```php
Gate::authorize('workWith', $idea);
```

Si el usuario no cumple la condición definida en la Policy, Laravel responde automáticamente con un error **403 Forbidden**.

### Middleware de autorización

También se mostró una alternativa utilizando middleware directamente sobre las rutas.

```php
->can('workWith', 'idea')
```

Este enfoque delega la autorización al sistema de rutas antes de que la solicitud llegue al controlador.

Aunque ambos métodos producen el mismo resultado, se decidió mantener la autorización directamente dentro del controlador por resultar más clara durante el desarrollo del curso.

### Pruebas de autorización

Finalmente se agregaron nuevas pruebas para verificar el correcto funcionamiento del sistema de autenticación y autorización.

La primera prueba comprueba que un usuario no autenticado sea redirigido a la pantalla de inicio de sesión cuando intenta acceder a una idea.

```php
$this->get(route('idea.show', $idea))
    ->assertRedirectToRoute('login');
```

La segunda prueba verifica que un usuario autenticado no pueda acceder a una idea creada por otra persona.

```php
$this->actingAs($user);

$this->get(route('idea.show', $idea))
    ->assertForbidden();
```

Con estas pruebas se garantiza que únicamente el propietario de una idea pueda visualizarla o modificarla.

### Resultado del episodio

Al finalizar el episodio se fortaleció la seguridad de la aplicación mediante la implementación de Policies y reglas de autorización. Además, se ampliaron las pruebas automatizadas para detectar errores durante futuras refactorizaciones y se verificó que los usuarios únicamente puedan acceder a las ideas que les pertenecen, devolviendo una respuesta **403 Forbidden** cuando intentan acceder a recursos no autorizados. 
