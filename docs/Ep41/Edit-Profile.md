# Edit Profile & Email Change Notifications

## Episodio 42 - Edit Profile & Email Change Notifications

### Desarrollo del episodio

En este episodio se implementó la edición del perfil del usuario y se agregó un sistema de notificaciones para alertar al correo electrónico original cuando la dirección de correo es modificada.

Además, se desarrollaron pruebas automatizadas para verificar tanto la actualización del perfil como el envío de la notificación correspondiente.

### Agregar el enlace al perfil

Se añadió un nuevo enlace en la barra de navegación visible únicamente para usuarios autenticados.

```blade
@auth
    <a href="{{ route('profile.edit') }}">
        Edit Profile
    </a>
@endauth
```

Inicialmente el enlace producía un error **404**, por lo que fue necesario registrar una nueva ruta.

### Registrar las rutas del perfil

Se agregaron las rutas encargadas de mostrar el formulario y actualizar la información del usuario.

```php
Route::get('/profile', [ProfileController::class, 'edit'])
    ->middleware('auth')
    ->name('profile.edit');

Route::patch('/profile', [ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');
```

Se utilizaron nombres de ruta para evitar modificar múltiples enlaces si la URL cambia en el futuro.

### Crear el controlador

Se creó un nuevo controlador para centralizar toda la lógica relacionada con el perfil.

```bash
php artisan make:controller ProfileController
```

Inicialmente se implementó el método `edit()`.

```php
public function edit()
{
    return view('profile.edit', [
        'user' => Auth::user(),
    ]);
}
```

### Construcción del formulario

Se creó la vista:

```text
resources/views/profile/edit.blade.php
```

El formulario reutiliza el componente desarrollado anteriormente para mantener un diseño consistente.

```blade
<x-form
    title="Edit your account"
    description="Need to make a tweak?"
>
```

Los campos se inicializan utilizando la información del usuario autenticado.

```blade
<x-form.field
    name="name"
    :value="$user->name"
/>

<x-form.field
    name="email"
    :value="$user->email"
/>
```

También se agregó un campo opcional para ingresar una nueva contraseña.

### Actualizar el perfil

El método `update()` recibe la solicitud y valida los datos enviados.

```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],

    'email' => [
        'required',
        'string',
        'email',
        'max:255',
        Rule::unique('users', 'email')
            ->ignore($user->id),
    ],

    'password' => [
        'nullable',
        Password::defaults(),
    ],
]);
```

Posteriormente se actualiza el registro.

```php
$user->update([
    'name' => $request->name,
    'email' => $request->email,
    'password' => $request->password ?? $user->password,
]);
```

Al finalizar se redirige nuevamente al formulario mostrando un mensaje de éxito.

```php
return redirect()
    ->route('profile.edit')
    ->with('success', 'Profile updated!');
```

### Manejo de la contraseña

Durante el desarrollo se detectó un error al dejar el campo de contraseña vacío.

El valor llegaba como `null`, provocando una violación de la restricción **NOT NULL** en la base de datos.

La solución consistió en conservar la contraseña actual cuando el usuario no proporciona una nueva.

### Crear la notificación

Se creó una nueva notificación utilizando Artisan.

```bash
php artisan make:notification EmailChanged
```

Esta notificación será enviada al correo electrónico anterior cuando el usuario cambie su dirección de correo.

### Detectar el cambio de correo

Antes de actualizar el usuario se almacenó el correo original.

```php
$originalEmail = $user->email;
```

Después de realizar la actualización se comparan ambos valores.

```php
if ($originalEmail !== $request->email) {
    //
}
```

Si el correo cambió, se envía la notificación.

### Envío a un correo externo

Como el usuario ya posee el nuevo correo electrónico, no es posible utilizar directamente:

```php
$user->notify(...)
```

porque la notificación llegaría a la nueva dirección.

Para solucionar este problema se utilizó el sistema de rutas de notificaciones de Laravel.

```php
Notification::route('mail', $originalEmail)
    ->notify(
        new EmailChanged(
            $user,
            $originalEmail
        )
    );
```

Este mecanismo permite enviar una notificación a cualquier dirección de correo sin necesidad de que exista un modelo asociado.

### Constructor de la notificación

La notificación recibe el usuario actualizado y el correo original mediante su constructor.

```php
public function __construct(
    protected User $user,
    protected string $originalEmail,
) {
    //
}
```

Estos datos estarán disponibles posteriormente para construir el contenido del correo.

### Pruebas del perfil

Se creó un nuevo archivo de pruebas dedicado al perfil del usuario.

```text
tests/Browser/ProfileTest.php
```

La primera prueba verifica que únicamente los usuarios autenticados puedan acceder al formulario.

```php
$this->get(route('profile.edit'))
    ->assertRedirect('/login');
```

Otra prueba confirma que el usuario puede modificar correctamente su información.

```php
visit(route('profile.edit'))
    ->fill('name', 'New Name')
    ->fill('email', 'new@example.com')
    ->click('Update Account')
    ->assertSee('Profile updated!');
```

Finalmente se verifica que la base de datos refleje los cambios.

```php
expect($user->fresh())->toMatchArray([
    'name' => 'New Name',
    'email' => 'new@example.com',
]);
```

### Probar las notificaciones

Para evitar enviar correos reales durante las pruebas se utilizó el sistema de simulación de Laravel.

```php
Notification::fake();
```

Después de actualizar el correo se comprobó que la notificación fuera enviada.

Inicialmente se intentó utilizar:

```php
Notification::assertSentTo(...)
```

Sin embargo, como el destinatario no corresponde a un modelo sino a una dirección de correo específica, fue necesario utilizar:

```php
Notification::assertSentOnDemand(...)
```

Dentro del callback se verificó que la notificación fuera enviada exactamente al correo electrónico original.

```php
return $notifiable->routes['mail'] === $originalEmail;
```

Con ello se garantiza que el usuario sea advertido cuando alguien cambie la dirección de correo asociada a su cuenta.

### Resultado del episodio

Al finalizar el episodio se implementó completamente la edición del perfil del usuario. Ahora es posible modificar el nombre, correo electrónico y contraseña desde una interfaz dedicada, manteniendo validaciones apropiadas y mostrando mensajes de éxito.

Además, se incorporó una notificación que alerta al correo electrónico anterior cuando la dirección cambia, utilizando notificaciones **On Demand**, y se desarrollaron pruebas automatizadas que verifican tanto la actualización del perfil como el correcto envío de dicha notificación. 
