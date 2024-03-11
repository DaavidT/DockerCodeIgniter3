# Codeigniter 3 con php 7.3 y mysql 5.7

Este es un ejemplo de configuración para docker-compose que permite ejecutar un proyecto escrito en Codeigniter 3 y compatible únicamente con PHP 7.4.

## Uso

1. Agregar una carpeta llamada **NubeInvirtual** con el proyecto de Codeigniter dentro, o cambiar la línea 11 del `docker-compose.yml` para especificar el nombre del proyecto deseado.

2. Tener un archivo `.sql` para precargar la base de datos MySQL. En este caso, el archivo debe llamarse `nubeInvirtual.sql`. Si el archivo tiene otro nombre, se debe cambiar la línea 27 del `docker-compose.yml` para reflejar el nuevo nombre. El archivo `.sql` también debe estar ubicado en la raíz del proyecto.

3. Cambiar las credenciales de la base de datos según sea necesario.

4. Ejecutar el comando `docker-compose up` para iniciar los contenedores.

5. Abrir `localhost:8080` en el navegador para acceder a phpMyAdmin con las credenciales definidas.

6. El proyecto de Codeigniter estará disponible en `localhost:7700`.

### Estructura

La estructura del proyecto es la siguiente:

- /php
  - Dockerfile: Contiene las instrucciones para construir la imagen de PHP con la versión 7.3 y los comandos que se instalarán cuando se cree el contenedor.
