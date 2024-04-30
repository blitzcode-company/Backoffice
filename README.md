# Configuración de Laravel con LDAP para Autenticación

Este repositorio proporciona un ejemplo de cómo configurar un proyecto Laravel para la autenticación utilizando un servidor LDAP (Protocolo Ligero de Acceso a Directorios). Este README te guiará a través de los pasos necesarios para configurar tanto el proyecto Laravel como el servidor Windows Server, específicamente Active Directory, para la autenticación LDAP.

## Configuración del proyecto Laravel

1. Clona este repositorio en tu máquina local:

    Utiliza el comando `git clone`.

2. Instala las dependencias del proyecto utilizando Composer:

    Ejecuta el comando `composer install` dentro del proyecto.

3. Copia el archivo de configuración de ejemplo para LDAP:

   `cp .env.example .env`

4. Abre el archivo `.env` y configura los parámetros de conexión LDAP según tu entorno:

    ```dotenv
    LDAP_CACHE=false
    LDAP_LOGGING=true
    LDAP_CONNECTION=default
    LDAP_HOST=10.0.2.15
    LDAP_USERNAME="CN=Blitzcode gMSA,CN=Managed Service Accounts,DC=Blitzcode,DC=company"
    LDAP_PASSWORD="GMSP@ssword2024"
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
    - Fabian Gonzalez:
      - UID: Fabian Gonzalez
      - Correo electrónico: fgonzalez.estudios@gmail.com

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
    php artisan migrate
    php artisan ldap:test
    ```

3. Una vez que hayas configurado tanto el proyecto Laravel como el servidor Windows Server, puedes ejecutar el servidor de desarrollo de Laravel:

    ```bash
    php artisan serve
    ```

4. Visita `http://localhost:8000` en tu navegador y deberías poder iniciar sesión utilizando las credenciales de los usuarios de Active Directory que has configurado.
