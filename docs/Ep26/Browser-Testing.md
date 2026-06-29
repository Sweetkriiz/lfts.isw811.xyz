# Browser Testing Registration Forms With Pest

## Episodio 23: Installing Browser Testing

## Desarrollo del episodio

En este episodio se introduce el uso de **Browser Testing** con Pest para automatizar pruebas que simulan la interacción real de un usuario con la aplicación desde el navegador. Se crean pruebas para los procesos de registro, inicio de sesión, cierre de sesión y validaciones de formularios.

---

## Instalación de Browser Testing

Se instala el complemento de Browser Testing para Pest mediante Composer:

```bash
composer require pestphp/pest-plugin-browser --dev
```

Este paquete permite controlar un navegador durante las pruebas utilizando funciones como `visit()`, `fill()`, `click()` y diferentes tipos de aserciones.

---

## Prueba de registro de usuario

Se crea una prueba que simula el proceso completo de registro.

```php
it('registers a user', function () {
    visit('/register')
        ->fill('name', 'John Doe')
        ->fill('email', 'john@example.com')
        ->fill('password', 'password123!@#')
        ->click('Create Account')
        ->assertPathIs('/');
});
```

### Validaciones realizadas

- El navegador visita la página de registro.
- Se completan todos los campos del formulario.
- Se envía el formulario.
- Se verifica que el usuario sea redirigido a la página principal.

---

## Verificación de autenticación

Después del registro se comprueba que el usuario haya iniciado sesión correctamente.

```php
$this->assertAuthenticated();
```

También se valida que el usuario autenticado corresponda a los datos registrados.

```php
expect(Auth::user())->toMatchArray([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);
```

---

## Prueba de inicio de sesión

Para probar el login primero se crea un usuario utilizando una Factory.

```php
$user = User::factory()->create([
    'password' => Hash::make('password123!@#'),
]);
```

Posteriormente se simula el inicio de sesión.

```php
visit('/login')
    ->fill('email', $user->email)
    ->fill('password', 'password123!@#')
    ->click('@login-button')
    ->assertPathIs('/');
```

Finalmente se verifica que el usuario haya quedado autenticado.

```php
$this->assertAuthenticated();
```

---

## Uso de atributos `data-test`

Para evitar depender del texto visible de los botones, se agrega un atributo personalizado.

```html
<button
    type="submit"
    data-test="login-button">
    Sign in
</button>
```

Esto permite seleccionar el botón directamente desde las pruebas mediante:

```php
->click('@login-button')
```

Este enfoque hace que las pruebas sean más estables cuando cambia el texto de la interfaz.

---

## Prueba de cierre de sesión

Se crea un usuario y se autentica antes de iniciar la prueba.

```php
$user = User::factory()->create();

$this->actingAs($user);
```

Luego se simula el cierre de sesión.

```php
visit('/')
    ->click('Log out');
```

Finalmente se verifica que el usuario ya no esté autenticado.

```php
$this->assertGuest();
```

---

## Pruebas de validación

Además de probar los casos exitosos, se realizan pruebas para validar errores de entrada.

Ejemplo de correo electrónico inválido:

```php
visit('/register')
    ->fill('name', 'John Doe')
    ->fill('email', 'john123')
    ->fill('password', 'password123!@#')
    ->click('Create Account')
    ->assertPathIs('/register');
```

En este caso la aplicación permanece en la página de registro debido a que el correo electrónico no cumple con el formato requerido.

---

## Depuración de pruebas

Durante el desarrollo de una prueba se utiliza el método:

```php
->debug();
```

Este abre el navegador para observar visualmente el comportamiento de la aplicación y facilitar la identificación de errores antes de agregar las aserciones definitivas.

---

## Conceptos aprendidos

- Instalación de Pest Browser Testing.
- Automatización de pruebas desde el navegador.
- Simulación de acciones del usuario mediante `visit()`, `fill()` y `click()`.
- Uso de `assertPathIs()` para comprobar redirecciones.
- Verificación de autenticación con `assertAuthenticated()`.
- Verificación de usuarios invitados mediante `assertGuest()`.
- Uso de `actingAs()` para autenticar usuarios durante las pruebas.
- Creación de usuarios con Factories.
- Selección de elementos mediante atributos `data-test`.
- Pruebas de casos exitosos y casos de error en formularios.
- Uso de `debug()` para inspeccionar el comportamiento de las pruebas.