# Final Project Setup

## Episodio 23 – Project Overview and Goals

## Objetivo del episodio

En este episodio inicia el proyecto final del curso. Se crea una nueva aplicación Laravel y se configura un entorno de desarrollo profesional, incorporando herramientas para mejorar la calidad del código, automatizar tareas y facilitar el desarrollo con inteligencia artificial.

---

# Desarrollo del episodio

## 1. Creación del proyecto

Se crea una nueva aplicación Laravel utilizando el instalador oficial y seleccionando **Pest** como framework de pruebas.

Posteriormente se verifica que el proyecto funcione correctamente accediendo a la página principal desde el navegador.

---

## 2. Inicialización del repositorio Git

Se inicializa un repositorio Git para comenzar el control de versiones del proyecto.

Luego se realiza el primer commit y se conecta el repositorio local con un repositorio remoto en GitHub para mantener el código respaldado.

---

## 3. Despliegue inicial

Como demostración del flujo de trabajo, el proyecto se despliega utilizando **Laravel Forge**, automatizando tareas como:

- Instalación de dependencias.
- Configuración del servidor.
- Ejecución de migraciones.
- Publicación de la aplicación.

Con esto el proyecto queda disponible en un entorno de producción.

---

## 4. Configuración de Laravel Pint

Se configura **Laravel Pint**, la herramienta oficial para mantener un estilo de código consistente.

Se agrega un script personalizado en `composer.json` para ejecutar Pint mediante el siguiente comando:

```bash
composer run format
```

De esta manera se puede formatear todo el proyecto con un único comando.

---

## 5. Instalación y configuración de Rector

Se instala **Rector**, una herramienta que analiza y moderniza automáticamente el código PHP aplicando buenas prácticas.

Se crea el archivo `rector.php`, donde se configuran:

- Directorios que serán analizados.
- Carpetas excluidas del análisis.
- Reglas que no se desean ejecutar.
- Reglas de calidad de código.
- Declaración estricta de tipos.
- Integración con Laravel mediante Rector Laravel.

Después de la configuración se ejecuta Rector para actualizar automáticamente los archivos del proyecto.

---

## 6. Automatización del proceso de formato

El script `format` de Composer se modifica para ejecutar primero Rector y luego Pint.

Con ello, un solo comando permite actualizar y formatear todo el proyecto:

```bash
composer run format
```

Este procedimiento es recomendable ejecutarlo antes de realizar cada commit.

---

## 7. Herramientas adicionales

Durante el episodio también se presentan herramientas que ayudan durante el desarrollo del proyecto:

### CodeRabbit

Permite realizar revisiones automáticas del código mediante inteligencia artificial, detectando posibles errores, vulnerabilidades y oportunidades de mejora.

### Laravel Boost

Integra asistentes de inteligencia artificial con el proyecto Laravel, proporcionando contexto sobre rutas, modelos, comandos y documentación para generar respuestas más precisas durante el desarrollo.

---

# Archivos creados o modificados

- `composer.json`
- `composer.lock`
- `rector.php`
- Archivos modificados automáticamente por Rector.
- Archivos reformateados mediante Laravel Pint.

---

# Comandos utilizados

Instalar Rector:

```bash
composer require rector/rector --dev
```

Instalar Rector Laravel:

```bash
composer require rector/rector-laravel --dev
```

Ejecutar Rector:

```bash
vendor/bin/rector
```

Ejecutar Rector y Pint mediante Composer:

```bash
composer run format
```

Instalar Laravel Boost:

```bash
php artisan boost:install
```

---

# Resultado del episodio

Al finalizar este episodio se cuenta con un proyecto Laravel preparado para el desarrollo del proyecto final.

Se configuró un flujo de trabajo que incorpora herramientas para:

- Mantener un estilo de código uniforme.
- Aplicar mejoras automáticas al código PHP.
- Automatizar tareas mediante Composer.
- Revisar el código utilizando inteligencia artificial.
- Integrar asistentes de IA especializados en Laravel.

Esta configuración servirá como base para desarrollar el resto del proyecto final del curso siguiendo buenas prácticas y manteniendo un código limpio y consistente.