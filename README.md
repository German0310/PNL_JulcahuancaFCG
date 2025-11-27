Instrucciones de Instalación y Configuración
Siga estos pasos para poner en marcha el sistema:

1. Preparar el Entorno (XAMPP)
Instalar XAMPP: Asegúrese de que XAMPP  esté instalado.

Iniciar Servicios: Inicie los servicios de Apache y MySQL desde el panel de control de XAMPP.

2. Configuración del Código Fuente
Mover el Proyecto: Coloque la carpeta principal del proyecto (denuncias_app) dentro del directorio raíz de su servidor web (htdocs/ en XAMPP).

Ruta típica de XAMPP: C:\xampp\htdocs\denuncias_app\

3. Configuración de la Base de Datos
Crear Base de Datos: Acceda a phpMyAdmin (http://localhost/phpmyadmin/) y cree una nueva base de datos con el nombre exacto: denuncias_db.

Ejecutar Script SQL:

Vaya a la pestaña SQL en la base de datos denuncias_db.

Copie y pegue el contenido del archivo denuncias_db.txt (o importe denuncias_db.sql) para crear la tabla denuncias  e insertar los registros de prueba.
Credenciales de Conexión (Archivos config/database.php):

Host: localhost

DB Name: denuncias_db

User: root

Password: (Vacía)

4. Ejecución del Sistema
Abrir en Navegador: Acceda a la aplicación a través de su navegador web:

URL de Acceso: http://localhost/denuncias_app/index.php
