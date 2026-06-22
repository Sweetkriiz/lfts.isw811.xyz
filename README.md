# Instalación y ejecución del proyecto

## Descripción general

Este proyecto fue desarrollado utilizando **Laravel**, **MariaDB** y **Node.js** dentro de un entorno virtualizado con **Vagrant**. Para ejecutar correctamente la aplicación es necesario iniciar la máquina virtual, configurar la base de datos, instalar las dependencias del proyecto y finalmente levantar el servidor web.

---

## Requisitos previos

Antes de comenzar, asegúrese de tener instalado lo siguiente en su equipo:

- Git
- VirtualBox
- Vagrant
- Un navegador web moderno

> **Nota:** PHP, Composer, Node.js y MariaDB ya se encuentran configurados dentro de la máquina virtual utilizada para el desarrollo del proyecto.

---

## Iniciar la máquina virtual

Ubíquese en la carpeta donde se encuentra el archivo `Vagrantfile` del entorno de desarrollo y ejecute:

```bash
vagrant up
```

Este comando iniciará la máquina virtual Debian utilizada para ejecutar el proyecto.

Para verificar que la máquina virtual se encuentra funcionando correctamente:

```bash
vagrant status
```

El estado debe aparecer como `running`.

Posteriormente, acceda a la máquina virtual mediante SSH:

```bash
vagrant ssh
```

---

## Acceder al directorio del proyecto

Una vez dentro de la máquina virtual, diríjase al directorio donde se encuentra almacenado el proyecto Laravel:

```bash
cd /vagrant/sites/lfts.isw811.xyz
```

Todos los comandos indicados en las siguientes secciones deben ejecutarse desde esta ubicación.

---

## Instalar dependencias del proyecto

Laravel utiliza Composer para administrar las dependencias de PHP y npm para gestionar las dependencias del frontend.

```bash
composer install
npm install
```

### ¿Qué realiza cada comando?

- `composer install`: descarga e instala todas las librerías PHP definidas en el archivo `composer.json`.
- `npm install`: descarga e instala las dependencias JavaScript definidas en el archivo `package.json`.

---

## Configurar el entorno de la aplicación

Laravel requiere un archivo `.env` para almacenar la configuración específica del entorno.

Si el archivo no existe, créelo a partir del archivo de ejemplo:

```bash
cp .env.example .env
```

Posteriormente genere la clave de la aplicación:

```bash
php artisan key:generate
```

Este comando genera una clave única utilizada por Laravel para procesos de cifrado y seguridad.

---

## Configurar e iniciar la base de datos

Verifique que el servicio MariaDB se encuentre activo:

```bash
sudo systemctl status mariadb
```

Si el servicio no está iniciado, ejecútelo manualmente:

```bash
sudo systemctl start mariadb
```

Acceda al gestor de bases de datos:

```bash
sudo mariadb
```

Cree la base de datos utilizada por el proyecto:

```sql
CREATE DATABASE lfts;
```

Salga de MariaDB:

```sql
EXIT;
```

---

## Configurar la conexión a la base de datos

Abra el archivo `.env` y verifique que la configuración de conexión sea similar a la siguiente:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lfts
DB_USERNAME=root
DB_PASSWORD=
```

---

## Crear la estructura de la base de datos

Laravel utiliza migraciones para crear automáticamente las tablas necesarias para el funcionamiento de la aplicación.

```bash
php artisan migrate
```

Si el proyecto incluye datos iniciales o de prueba, ejecute también:

```bash
php artisan db:seed
```

### Función de estos comandos

- `php artisan migrate`: crea las tablas definidas en las migraciones del proyecto.
- `php artisan db:seed`: inserta registros iniciales necesarios para pruebas o configuración.

---

## Compilar los recursos del frontend

Durante el desarrollo:

```bash
npm run dev
```

Para generar una versión optimizada para producción:

```bash
npm run build
```

---

## Levantar la aplicación Laravel

Una vez completados los pasos anteriores, inicie el servidor de desarrollo:

```bash
php artisan serve --host=0.0.0.0
```

Si el proceso se ejecuta correctamente, Laravel mostrará una salida similar a la siguiente:

```text
INFO  Server running on [http://0.0.0.0:8000]
```

La aplicación podrá accederse desde:

```text
http://127.0.0.1:8000
```

---

## Comandos adicionales

### Ejecutar pruebas automatizadas

```bash
php artisan test
```

### Crear enlace simbólico para almacenamiento público

```bash
php artisan storage:link
```

### Procesar trabajos en cola

```bash
php artisan queue:work
```

### Generar recursos para producción

```bash
npm run build
```

---

## Resumen rápido de ejecución

```bash
vagrant up
vagrant ssh

cd /vagrant/sites/lfts.isw811.xyz

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed

npm run dev

php artisan serve --host=0.0.0.0
```

Una vez completados estos pasos, la aplicación estará lista para utilizarse.