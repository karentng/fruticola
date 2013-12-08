Proyecto Plan Frutícola
=======================


### Convenciones de Código

- Usar codificación UTF-8 (sin BOM).
- Usar indentación a 4 espacios (reemplazar tabs por espacios).


### Configuración Base de Datos


Usuario, password y nombre de base de datos: `fruticola` 

- **Instalar PostgreSQL**. En Ubuntu:

    `sudo apt-get install postgresql-9.1`
  

- Crear usuario de base de datos:

    `sudo -u postgres createuser`

    Ingrese el nombre del usuario a crear (`fruticola`). Recomendado responder yes (`y`) a la pregunta de crear un superuser.

- Establecer password de usuario:
  
    `sudo -u postgres psql`

    `>` ` alter user fruticola with encrypted password 'fruticola';`


- Desde su administrador de bases de datos favorito (como [adminer](http://www.adminer.org)) cree la base de datos `fruticola`. 

- Importe los SQL de la carpeta `sql` del proyecto.

### Otras Configuraciones Recomendadas

Active los errores de PHP. En Ubuntu, edite el archivo */etc/php5/apache2/php.ini* y establezca la variable `display_errors = On`.
Verifique que no tenga una señal de comentario (caracter punto y coma) al inicio de la linea.
Luego de realizar el cambio, reinicie el servidor apache: `sudo apachectl restart`.