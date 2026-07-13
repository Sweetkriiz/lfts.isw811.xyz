# Allow For One or Many Links

## Episodio 35 - Allow For One or Many Links

### Desarrollo del episodio

En este episodio se agregó soporte para que una idea pueda contener uno o varios enlaces asociados. Debido a que la cantidad de enlaces no es fija, se implementó una interfaz dinámica utilizando **Alpine.js**, permitiendo agregar y eliminar enlaces antes de enviar el formulario.

Inicialmente se analizaron dos posibles enfoques para capturar los enlaces. El primero consistía en utilizar un área de texto donde cada enlace se escribiera en una línea distinta para posteriormente convertir ese contenido en un arreglo desde el servidor. El segundo, que fue el elegido, consiste en ofrecer un campo de entrada acompañado por un botón para agregar cada enlace individualmente, aprovechando la validación del navegador mediante un campo de tipo `url`.

### Creación del campo de enlaces

Debajo del campo de descripción se agregó un nuevo `fieldset` para agrupar todos los controles relacionados con los enlaces.

```blade
<fieldset class="space-y-3">
    <legend class="label">Links</legend>
</fieldset>
```

Dentro del `fieldset` se incorporó un campo de tipo `url` junto con un botón encargado de agregar el enlace al listado.

```blade
<input
    type="url"
    id="new-link"
    placeholder="http://example.com"
    autocomplete="url"
    class="input flex-1"
    spellcheck="false"
    x-model="newLink"
>
```

El botón utiliza un icono de cierre rotado cuarenta y cinco grados para representar visualmente el símbolo de agregar.

```blade
<button
    type="button"
    @click="links.push(newLink)"
>
    <x-icons.close class="rotate-45" />
</button>
```

### Administración del estado con Alpine.js

Para controlar los enlaces ingresados por el usuario se agregaron dos nuevas propiedades al componente de Alpine.js.

```javascript
newLink: '',
links: [],
```

La propiedad `newLink` mantiene sincronizado el contenido del campo de entrada mediante `x-model`, mientras que `links` almacena todos los enlaces agregados.

Cada vez que el usuario presiona el botón de agregar, el enlace actual se incorpora al arreglo y posteriormente se limpia el campo de entrada.

```javascript
links.push(newLink);

newLink = '';
```

Además, antes de almacenar el enlace se elimina cualquier espacio sobrante utilizando `trim()`.

### Evitar enlaces vacíos

Para impedir que el usuario agregue enlaces vacíos, el botón de agregar permanece deshabilitado mientras el campo no contenga información.

```blade
:disabled="!newLink.length"
```

De esta manera únicamente es posible agregar enlaces cuando existe un valor válido.

### Envío del arreglo al servidor

Como el formulario continúa siendo un formulario HTML tradicional, fue necesario generar dinámicamente un conjunto de campos ocultos para enviar todos los enlaces al servidor.

Para ello se utilizó `x-for`, recorriendo el arreglo `links`.

```blade
<template x-for="link in links">
    <input
        type="hidden"
        name="links[]"
        x-model="link"
    >
</template>
```

El nombre `links[]` permite que Laravel reciba automáticamente todos los enlaces agrupados dentro de un arreglo.

### Validación de enlaces

Posteriormente se agregaron reglas de validación para verificar que el campo `links` sea un arreglo y que cada elemento corresponda a una URL válida.

```php
'links' => ['nullable', 'array'],
'links.*' => ['url'],
```

Laravel valida cada elemento individual mediante la sintaxis `links.*`, evitando almacenar direcciones inválidas en la base de datos.

### Mostrar y eliminar enlaces

En lugar de mantener los enlaces ocultos, se decidió mostrarlos al usuario para que puedan visualizarse antes de enviar el formulario.

Cada enlace agregado se representa mediante un nuevo campo de texto acompañado por un botón para eliminarlo.

```blade
<template x-for="(link, index) in links">
    <div class="flex gap-x-2 items-center">
        <input
            class="input flex-1"
            name="links[]"
            x-model="link"
        >

        <button
            type="button"
            @click="links.splice(index, 1)"
        >
            <x-icons.close />
        </button>
    </div>
</template>
```

La función `splice()` elimina el enlace seleccionado utilizando su posición dentro del arreglo.

### Actualización de las pruebas automatizadas

Finalmente se actualizó la prueba del episodio anterior para verificar el nuevo comportamiento.

Se agregó un atributo `data-test` al campo encargado de ingresar nuevos enlaces y otro al botón que agrega cada enlace al listado.

Posteriormente la prueba automatizada llena el campo, agrega uno o varios enlaces y verifica que el formulario envíe correctamente el arreglo al servidor.

Con esta modificación, las pruebas ahora validan no solamente la creación de una idea, sino también el correcto funcionamiento del sistema dinámico de enlaces implementado con Alpine.js.

### Resultado del episodio

Al finalizar el episodio se implementó un sistema dinámico para administrar múltiples enlaces dentro del formulario de creación de ideas. El usuario puede agregar, visualizar y eliminar enlaces antes de enviar el formulario, mientras Laravel recibe todos los valores agrupados como un arreglo y valida que cada uno corresponda a una URL válida. Además, las pruebas automatizadas fueron actualizadas para comprobar el funcionamiento completo de esta nueva característica.
