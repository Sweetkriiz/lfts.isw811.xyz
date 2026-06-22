# Authentication Explained

## Episodio 14: Authentication Explained

### Desarrollo del episodio

En este episodio se implementó el sistema de autenticación de usuarios utilizando las herramientas nativas de Laravel. Se agregaron funcionalidades para registrar usuarios, iniciar sesión y cerrar sesión, permitiendo controlar el acceso a la aplicación mediante cuentas individuales.

Para organizar la lógica de autenticación se crearon controladores dentro del directorio `Auth`, específicamente `RegisteredUserController` para el registro de usuarios y `SessionsController` para la gestión de sesiones.

Durante el proceso de registro se utilizó la validación integrada de Laravel mediante el método `$request->validate()`, verificando que el nombre sea obligatorio, que el correo electrónico tenga un formato válido y que no exista previamente en la tabla `users`, además de validar la contraseña utilizando las reglas predeterminadas del framework.

```php
$attributes = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users,email'],
    'password' => ['required', Password::defaults()],
]);
```

Una vez validados los datos, se creó el usuario mediante Eloquent ORM. Para proteger la información sensible, la contraseña se almacenó utilizando un hash generado por Laravel con `Hash::make()`.

```php
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
]);
```

Después de crear el usuario, se inició automáticamente su sesión mediante:

```php
Auth::login($user);
```

También se desarrolló el formulario de inicio de sesión, donde Laravel verifica las credenciales utilizando `Auth::attempt()`. Si las credenciales son correctas, se autentica al usuario y se regenera la sesión para aumentar la seguridad.

```php
if (Auth::attempt($attributes)) {
    $request->session()->regenerate();

    return redirect('/ideas');
}
```

Para finalizar la sesión se implementó el método `Auth::logout()`, eliminando la autenticación activa y redirigiendo nuevamente al usuario a la página principal.

```php
Auth::logout();
```

Además, se utilizaron las directivas Blade `@guest` y `@auth` para mostrar diferentes opciones en la barra de navegación dependiendo de si el usuario ha iniciado sesión o no.

```blade
@guest
    <!-- Login y Register -->
@endguest

@auth
    <!-- Logout -->
@endauth
```

Con estas implementaciones, la aplicación ahora cuenta con un sistema básico de autenticación que permitirá asociar información y funcionalidades a usuarios específicos en los siguientes episodios.

## Archivos modificados

- routes/web.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Http/Controllers/Auth/SessionsController.php
- resources/views/auth/register.blade.php
- resources/views/auth/login.blade.php
- resources/views/components/nav.blade.php
- app/Models/User.php

## Evidencias

### Vista del formulario de registro implementado con DaisyUI

![Formulario de registro](../images/Screenshot%202026-06-21%20234050.png)

### Validación HTML de campos obligatorios

![Validación HTML](../images/Screenshot%202026-06-21%20234412.png)

### Comprobación de los datos recibidos en el controlador utilizando dd()

![Datos recibidos](../images/Screenshot%202026-06-22%20001830.png)

### Usuario persistido correctamente en la tabla users

![Usuario registrado](../images/Screenshot%202026-06-22%20000140.png)

### Implementación del formulario de inicio de sesión

![Formulario login](../images/Screenshot%202026-06-22%20001812.png)