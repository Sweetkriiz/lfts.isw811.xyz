# Action Classes

## Episodio 37 - Introducing Action Classes

### Desarrollo del episodio

En este episodio se refactorizó el proceso de creación de ideas mediante la implementación de una **Action Class**. Hasta este punto, toda la lógica necesaria para crear una idea se encontraba dentro del método `store()` del controlador, lo que hacía que dicho método creciera conforme se agregaban nuevas funcionalidades como enlaces, pasos e imágenes.

Con el fin de mantener el controlador más limpio y facilitar la reutilización del código, se decidió mover toda la lógica de creación hacia una clase dedicada.

### Creación de la Action Class

Se creó un nuevo directorio dentro de `app` llamado `Actions`, donde se agregó la clase `CreateIdea`.

```text
app/
└── Actions/
    └── CreateIdea.php
```

Dentro de esta clase se implementó un método `handle()` encargado de recibir los atributos necesarios para crear una nueva idea.

```php
public function handle(array $attributes)
{
    //
}
```

De esta forma, el controlador únicamente delega la responsabilidad de crear la idea.

### Simplificación del controlador

El método `store()` dejó de contener toda la lógica relacionada con la creación de la idea.

En su lugar, únicamente recibe la solicitud validada, ejecuta la acción correspondiente y redirige nuevamente al listado.

```php
$action->handle(
    $request->safe()->all()
);

return redirect()->route('ideas.index');
```

Esto permite que el controlador tenga una única responsabilidad y resulte mucho más fácil de mantener.

### Desacoplar la Action Class del Request

Uno de los objetivos principales fue evitar que la nueva clase dependiera directamente del objeto `Request`.

En lugar de acceder a los datos mediante:

```php
$request->input(...)
```

la clase recibe un arreglo con todos los atributos necesarios.

```php
public function handle(array $attributes)
```

Gracias a este enfoque, la acción puede reutilizarse desde cualquier parte de la aplicación, como controladores, comandos Artisan, pruebas automatizadas u otros servicios.

### Preparación de los datos

Dentro de la Action Class únicamente se seleccionan los atributos necesarios para crear la idea.

```php
$data = collect($attributes)
    ->only([
        'title',
        'description',
        'status',
        'links',
    ])
    ->toArray();
```

Posteriormente, si existe una imagen, ésta se almacena y se agrega la ruta al arreglo de datos antes de crear el registro.

```php
if ($attributes['image'] ?? false) {
    $data['image_path'] = $attributes['image']->store(
        'ideas',
        'public'
    );
}
```

Esto evita realizar una actualización adicional después de crear la idea.

### Creación de los pasos

Una vez creada la idea, los pasos recibidos desde el formulario se convierten en el formato esperado por el modelo `Step`.

```php
$steps = collect($attributes['steps'] ?? [])
    ->map(fn ($step) => [
        'description' => $step,
    ]);
```

Finalmente todos los pasos se insertan mediante `createMany()`.

```php
$idea->steps()->createMany($steps);
```

### Uso de transacciones

Como durante la creación de una idea se realizan varias operaciones sobre la base de datos, se decidió envolver todo el proceso dentro de una transacción.

```php
DB::transaction(function () {
    //
});
```

De esta manera, si alguna operación falla, Laravel revierte automáticamente todos los cambios realizados y evita dejar información inconsistente en la base de datos.

### Uso del contenedor de servicios

En lugar de crear manualmente una instancia de la Action Class, se aprovechó el contenedor de servicios de Laravel para obtenerla mediante inyección de dependencias.

```php
public function store(
    StoreIdeaRequest $request,
    CreateIdea $action
)
```

Laravel resuelve automáticamente la instancia necesaria antes de ejecutar el método del controlador.

### Inyección del usuario autenticado

Como mejora adicional, se utilizó el atributo `CurrentUser` para que Laravel inyecte automáticamente el usuario autenticado dentro de la Action Class.

```php
#[CurrentUser]
protected User $user
```

Con esta característica ya no es necesario obtener el usuario mediante `Auth::user()` ni pasarlo manualmente como parámetro.

### Verificación mediante pruebas

Después de completar la refactorización se ejecutaron las pruebas automatizadas desarrolladas en episodios anteriores.

Al mantenerse todas las pruebas en estado satisfactorio, se confirmó que el cambio únicamente mejoró la organización del código sin alterar el comportamiento de la aplicación.

### Resultado del episodio

Al finalizar el episodio se trasladó toda la lógica de creación de ideas hacia una Action Class especializada. El controlador quedó considerablemente más simple, la lógica pasó a ser reutilizable desde cualquier parte de la aplicación y el proceso completo quedó protegido mediante transacciones de base de datos. Además, se aprovechó el contenedor de servicios y la inyección automática del usuario autenticado para reducir el acoplamiento y mejorar la mantenibilidad del proyecto. :contentReference[oaicite:0]{index=0}
