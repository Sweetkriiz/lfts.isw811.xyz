# How to Get Started Testing Your Code

## Episodio 22: Getting Started with Testing

## Objetivo

En este episodio se introdujo **Pest PHP** como framework de pruebas para Laravel. Se explicó la diferencia entre pruebas unitarias, pruebas de características (Feature Tests) y pruebas de navegador (Browser Tests), además de mostrar cómo automatizar la validación del funcionamiento de la aplicación mediante pruebas repetibles.

---

## ¿Qué se hizo?

El episodio comenzó revisando la instalación de **Pest PHP**. Se mostró que las versiones recientes de Laravel ya incluyen Pest por defecto, permitiendo ejecutar todas las pruebas mediante:

```bash
php artisan test
```

También es posible ejecutar directamente Pest utilizando:

```bash
./vendor/bin/pest
```

Laravel crea automáticamente una estructura de pruebas dentro del directorio `tests`, donde se distinguen principalmente dos tipos de pruebas:

- **Unit Tests:** verifican el comportamiento de una clase o método específico.
- **Feature Tests:** comprueban el funcionamiento completo de una característica de la aplicación simulando solicitudes HTTP.

Se explicó que para comenzar es recomendable enfocarse primero en los **Feature Tests**, ya que representan el comportamiento real que experimenta el usuario.

---

## Browser Testing

Posteriormente se introdujeron las pruebas de navegador utilizando el plugin de **Pest Browser Testing**, el cual permite abrir un navegador real para interactuar con la aplicación.

En lugar de utilizar:

```php
$this->get('/');
```

se emplea:

```php
visit('/');
```

Esto permite realizar acciones similares a las que ejecuta un usuario:

- Visitar páginas.
- Completar formularios.
- Presionar botones.
- Verificar redirecciones.
- Confirmar que determinado contenido aparece en pantalla.

---

## Configuración de Browser Tests

Para que las pruebas funcionen correctamente fue necesario configurar el archivo `tests/Pest.php`, haciendo que las pruebas del directorio **Browser** extiendan la clase `TestCase` de Laravel.

También se habilitó el trait:

```php
RefreshDatabase
```

para que antes de cada prueba la base de datos vuelva a un estado limpio, evitando que los datos de una prueba interfieran con las siguientes.

---

## Primera prueba: Registro de usuarios

Se desarrolló una prueba completa del proceso de registro de usuarios.

El flujo consistió en:

1. Visitar la página de registro.
2. Completar nombre, correo y contraseña.
3. Presionar el botón **Register**.
4. Verificar la redirección hacia `/ideas`.
5. Confirmar que el usuario fue creado en la base de datos.
6. Verificar que el usuario quedó autenticado.

Para identificar correctamente el botón de registro, se agregó el atributo personalizado:

```html
data-test="register-button"
```

Posteriormente la prueba utilizó dicho atributo mediante:

```php
->press('@register-button')
```

Este enfoque evita depender del texto visible del botón y hace las pruebas más estables ante futuros cambios en la interfaz.

---

## Pruebas sobre Ideas

Finalmente se inició la creación de pruebas para el módulo de Ideas.

Se creó un usuario utilizando Factory:

```php
$user = User::factory()->create();
```

Posteriormente se autenticó mediante:

```php
$this->actingAs($user);
```

Después se creó una idea asociada al usuario:

```php
$user->ideas()->create([
    'description' => 'Build a thing',
]);
```

Finalmente la prueba verificó que al visitar la ruta `/ideas` aparezca la descripción creada:

```php
visit('/ideas')
    ->assertSee('Build a thing');
```

También se preparó la estructura para implementar pruebas adicionales relacionadas con:

- Mostrar una idea individual.
- Mostrar el formulario de edición.
- Actualizar una idea.
- Eliminar una idea.
- Validar permisos de acceso.

Como mejora adicional, se explicó que el atributo `state` del modelo `Idea` puede recibir un valor predeterminado directamente desde el modelo para evitar repetirlo en todas las pruebas.

---

## Conceptos aprendidos

- Uso de Pest PHP como framework de pruebas.
- Diferencia entre Unit Tests y Feature Tests.
- Instalación y configuración de Browser Testing.
- Uso del método `visit()` para abrir páginas.
- Automatización del registro de usuarios.
- Uso de `assertPathIs()`.
- Verificación de autenticación mediante `assertAuthenticated()`.
- Validación de registros en la base de datos utilizando `expect()`.
- Uso de Factories para crear modelos durante las pruebas.
- Autenticación de usuarios mediante `actingAs()`.
- Creación de pruebas para visualizar ideas.
- Uso del atributo `data-test` para identificar elementos HTML durante las pruebas.

---
