# Backoffice

<p align="center">
    <img src="https://drive.google.com/uc?export=download&id=1yyVoEHmLQgzYpDJJJvjtpo1MHdZNP84k" width="200">
</p>

## Configuración del proyecto Laravel

1. Clona este repositorio en tu máquina local:
    ```bash
    git clone https://github.com/blitzcode-company/Backoffice.git
    ```
2. Instala las dependencias del proyecto utilizando Composer:
   ```bash
   composer install
   ```

3. Copia el archivo de configuración y genera la clave de la aplicación:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Abre el archivo `.env` y configura los parámetros de conexión LDAP según tu entorno:

    ```dotenv
    LDAP_CACHE=false
    LDAP_LOGGING=true
    LDAP_CONNECTION=default
    LDAP_HOST=192.168.x.x
    LDAP_USERNAME="CN=Blitzcode gMSA,CN=Managed Service Accounts,DC=Blitzcode,DC=company"
    LDAP_PASSWORD="Managed2024."
    LDAP_PORT=389
    LDAP_BASE_DN="DC=Blitzcode,DC=company"
    LDAP_TIMEOUT=5
    LDAP_SSL=false
    LDAP_TLS=false
    LDAP_SASL=false
    ```

## Configuración del servidor Windows Server (Active Directory)

1. Abre el Administrador de servidores en tu servidor Windows.
2. Ve a Herramientas > Usuarios y equipos de Active Directory.
3. Crea una nueva unidad organizativa (UO) llamada Blitzcode-dev y dentro crea los siguientes usuarios:

    - Diego Vega:
      - UID: Diego Vega
      - Correo electrónico: diegovegaganachipi@gmail.com
    - Kevin Vidir:
      - UID: Kevin Vidir
      - Correo electrónico: kevinvidir@gmail.com
    - Mateo Sosa:
      - UID: Mateo Sosa
      - Correo electrónico: mateesosar@gmail.com

4. Crea un grupo llamado `Blitzcode-team` y agrega a los usuarios mencionados anteriormente a este grupo.
5. Asegúrate de que el grupo `Blitzcode-team` forme parte del grupo de administradores en Active Directory para que los usuarios tengan privilegios de administradores.

6. Crea una nueva cuenta de servicio administrado (gMSA) con los siguientes detalles:

    ```
    Usuario: Blitzcode gMSA
    Contraseña: gMSAP@ssword2024
    Correo electrónico: Blitzcode.company@gmail.com
    ```

## Uso del proyecto

1. Asegúrate de crear una base de datos llamada `Backoffice` y detallarla en el archivo de configuración `.env`.

2. Ejecuta los siguientes comandos de Artisan para migrar la base de datos y probar la conexión LDAP:

    ```bash
    sudo chmod 777 storage/logs/laravel.log
    php artisan migrate
    php artisan ldap:test
    ```

3. Una vez que hayas configurado tanto el proyecto Laravel como el servidor Windows Server, puedes levantar el entorno utilizando Docker Compose:

    ```bash
    docker-compose up -d
    ```

4. Esto iniciará el servidor de desarrollo de Laravel y el servicio de Vite para compilar los activos de la aplicación.

5. Visita `http://192.168.x.x:8000` en tu navegador y deberías poder iniciar sesión utilizando las credenciales de los usuarios de Active Directory que has configurado.