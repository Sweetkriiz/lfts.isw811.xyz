# Tailwind Theme Setup And Initial UI

## Episodio 18 – Authentication UI and Controllers

## Desarrollo del episodio

En este episodio se construyó la interfaz base para la autenticación utilizando Tailwind CSS y componentes Blade reutilizables. Además, se implementó la lógica de registro, inicio y cierre de sesión con Laravel Authentication.

---

## Configuración del tema con Tailwind CSS

Se personalizó `resources/css/app.css` utilizando la nueva sintaxis de Tailwind CSS v4.

Se definieron variables CSS dentro de `@theme` para reutilizarlas automáticamente como clases de Tailwind.

Entre los colores configurados se encuentran:

- Background
- Foreground
- Card
- Border
- Input
- Primary
- Primary Foreground
- Muted Foreground

Esto permitió utilizar clases como:

```html
bg-background
text-foreground
text-primary
border-border
```

---

## Componentes CSS reutilizables

Se creó la carpeta:

```
resources/css/components/
```

Con los archivos:

- `btn.css`
- `form.css`

Posteriormente ambos componentes fueron importados dentro de `app.css` utilizando:

```css
@import '../components/btn.css' layer(components);
@import '../components/form.css' layer(components);
```

De esta forma los estilos quedan organizados y reutilizables.

---

## Layout principal

Se creó un layout Blade reutilizable.

```
resources/views/components/layout/layout.blade.php
```

Este layout contiene:

- Inclusión de Vite
- Fondo oscuro
- Color de texto
- Barra de navegación
- Contenedor principal mediante `$slot`

---

## Barra de navegación

Se creó un componente independiente:

```
resources/views/components/layout/nav.blade.php
```

La navegación incluye:

- Logo
- Enlace al Home
- Botón Register
- Botón Sign In

Posteriormente se agregaron las directivas Blade:

```blade
@auth

@endauth

@guest

@endguest
```

para mostrar diferentes opciones dependiendo del estado del usuario.

---

## Formularios reutilizables

Se creó un componente para formularios:

```
resources/views/components/form.blade.php
```

Este componente recibe propiedades dinámicas:

- title
- description

y utiliza:

```blade
{{ $slot }}
```

para insertar cualquier formulario.

---

## Componentes de campos

También se creó un componente para cada campo del formulario:

```
resources/views/components/form/field.blade.php
```

El componente recibe:

- label
- name
- type

Ejemplo:

```blade
<x-form.field
    name="email"
    label="Email"
    type="email"
/>
```

Este componente también:

- conserva los datos usando

```php
old($name)
```

- muestra automáticamente los errores

```blade
@error($name)
```

---

## Página de Registro

Se creó:

```
resources/views/auth/register.blade.php
```

La página utiliza:

```blade
<x-layout>

<x-form>

<x-form.field>
```

Incluye los campos:

- Name
- Email
- Password

y un botón:

```
Create Account
```

---

## Página de Login

También se creó:

```
resources/views/auth/login.blade.php
```

Reutilizando exactamente los mismos componentes.

Contiene únicamente:

- Email
- Password
- Botón Sign In

---

## RegisteredUserController

Se implementó el controlador para el registro.

### Método create()

Muestra la vista:

```php
return view('auth.register');
```

### Método store()

Realiza:

- Validación
- Creación del usuario
- Inicio de sesión automático
- Redirección al Home

La validación incluye:

- Nombre obligatorio
- Email único
- Password mínima de 8 caracteres

---

## SessionsController

Se implementaron tres métodos.

### create()

Carga:

```php
return view('auth.login');
```

---

### store()

Se realiza:

- Validación del formulario
- Intento de autenticación mediante

```php
Auth::attempt()
```

Si falla:

- vuelve al formulario
- conserva los datos con

```php
withInput()
```

- muestra un mensaje mediante

```php
withErrors()
```

Si tiene éxito:

- regenera la sesión

```php
$request->session()->regenerate();
```

- redirige usando

```php
redirect()->intended('/')
```

---

### destroy()

Permite cerrar la sesión.

Utiliza:

```php
Auth::logout();
```

y posteriormente:

```php
return redirect('/');
```

---

## Rutas implementadas

Se agregaron las rutas:

```php
GET    /register
POST   /register

GET    /login
POST   /login

POST   /logout
```

Posteriormente se protegieron utilizando middleware.

### Guest

```php
->middleware('guest')
```

Para:

- register
- login

### Auth

```php
->middleware('auth')
```

Para:

- logout

---

## Conservación de datos del formulario

Los campos ahora mantienen los valores ingresados cuando ocurre un error de validación mediante:

```php
old($name)
```

Esto mejora considerablemente la experiencia del usuario.

---

## Mensajes de validación

Los errores ahora aparecen debajo de cada campo utilizando:

```blade
@error($name)
```

mostrando automáticamente el mensaje generado por Laravel.

---

## Hash automático de contraseñas

Se explicó una novedad de Laravel 12.

Ya no es necesario utilizar:

```php
Hash::make()
```

El modelo `User` incorpora automáticamente el cast:

```php
'password' => 'hashed'
```

por lo que las contraseñas se almacenan cifradas sin código adicional.

---

## Resultado

Al finalizar este episodio se obtuvo:

- Tema personalizado con Tailwind CSS v4.
- Componentes Blade reutilizables.
- Layout principal de la aplicación.
- Barra de navegación dinámica.
- Formularios reutilizables.
- Registro de usuarios.
- Inicio de sesión.
- Cierre de sesión.
- Validaciones.
- Mensajes de error.
- Conservación de datos del formulario.
- Protección de rutas mediante middleware `guest` y `auth`.