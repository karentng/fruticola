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

