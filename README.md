# Admin-Backend-Puntos-GOB-Ogtic
Puntos GOB Admin y superAdmin
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Documentacion del Backend
<img width="795" height="568" alt="image" src="https://github.com/user-attachments/assets/4e58842c-2ddc-41c1-bc6d-0d151c8916ea" />

 ### Techologias a utilizar

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white) ![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white) ![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)


UML de la DB
https://dbdiagram.io/d/Diagrama-de-BD-Sistema-de-Notificaciones-67b6ac15263d6cf9a0ce0e05
##### Navega hasta la carpeta del proyecto Laravel
```
cd Backend-Admin-GOB
 ```
##### Instala las dependencias del proyecto
```
composer install
```
##### Configura el archivo de entorno (.env)
```
cp .env.example .env
```
##### Inicia el servidor de desarrollo de Laravel
```
php artisan serve
```
#####  base de datos con datos de prueba
```
php artisan db:seed
```
####  Migración de Laravel:
```
php artisan migrate
```
#### Ejecutar las Migraciones de Laravel Limpiamente:
```
php artisan migrate:fresh --seed --force
```
.env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agenda_gob_admin  # <-- ¡Asegúrate que sea este nombre!
DB_USERNAME=root
DB_PASSWORD=
```

#### Crea un Seeder:
```
php artisan make:seeder SuperAdminSeeder
```
#### Ejecuta el Seeder:
```
php artisan migrate:fresh --seed
```
README.md: Guía Completa de la API de AgendaGOB Backend
Este README.md contiene toda la información necesaria para entender, configurar, probar y utilizar la API de AgendaGOB.

1. Estado Actual de la API
La autenticación con Laravel Sanctum y la gestión de roles/permisos con Spatie están configuradas y validadas.

Los usuarios SuperAdmin pueden iniciar sesión y realizar operaciones CRUD (GET, POST, PUT, DELETE) en Instituciones, Puntos GOB, Usuarios y Servicios.

El usuario Admin puede iniciar sesión y sus rutas de dashboard iniciales están en proceso de verificación.

Las rutas protegidas por permisos están aplicando correctamente las restricciones.

2. Pendientes para la Verificación Completa de la API
Antes de considerar la API 100% lista para producción, se deben completar las siguientes pruebas exhaustivas:

2.1. Pruebas de Rutas del Administrador (can:is-admin)
Asegúrate de que estas rutas funcionen correctamente con un token de usuario admin.

Dashboards:

GET /api/admin/dashboard/summary (Ya lo probaste, si dio 500, hay que corregirlo primero).

GET /api/admin/dashboard/appointments-status-daily

Gestión de Citas (Rol Admin):

GET /api/admin/appointments (Listar citas para el admin)

GET /api/admin/appointments/{appointment} (Ver una cita específica)

PUT /api/admin/appointments/{appointment}/process (Procesar una cita - Requiere una cita existente)

PUT /api/admin/appointments/{appointment}/cancel (Cancelar una cita - Requiere una cita existente)

GET /api/admin/appointments/export-pdf (Exportar citas a PDF)

2.2. Pruebas de Rutas Compartidas (auth:sanctum)
Estas rutas deben funcionar para cualquier usuario autenticado (SuperAdmin, Admin, Ciudadano).

Gestión de Perfil:

PUT /api/profile (Actualizar perfil de usuario)

PUT /api/profile/password (Cambiar contraseña del usuario)

Tickets de Soporte (apiResource - CRUD Completo):

GET /api/support-tickets (Listar tickets de soporte)

POST /api/support-tickets (Crear un nuevo ticket de soporte)

GET /api/support-tickets/{id} (Ver un ticket específico)

PUT /api/support-tickets/{id} (Actualizar un ticket)

DELETE /api/support-tickets/{id} (Eliminar un ticket)

Historial y Logs:

GET /api/history/appointments (Historial de citas del usuario)

GET /api/activity-logs (Logs de actividad del usuario)

2.3. Pruebas de Permisos Negativos (¡CRÍTICO para la Seguridad!)
Verificar que los usuarios NO puedan acceder a rutas que no les corresponden.

Admin vs. SuperAdmin:

Con el token de un Admin, intenta acceder a cualquier ruta GET bajo /api/superadmin/*.

Resultado esperado: 403 Forbidden. Si obtienes 200 OK, hay una vulnerabilidad en los permisos.

Ciudadano vs. Admin/SuperAdmin:

Crea un usuario con el rol Citizen (si no existe, usa POST /api/superadmin/users).

Obtén un token de este usuario citizen.

Con este token, intenta acceder a cualquier ruta GET bajo /api/admin/* o /api/superadmin/*.

Resultado esperado: 403 Forbidden.

Sin Token / Token Inválido:

Intenta acceder a cualquier ruta protegida (auth:sanctum) sin un token, o con un token inválido/expirado.

Resultado esperado: 401 Unauthorized.

2.4. Pruebas de Validación de Entradas y Manejo de Errores
Asegúrate de que la API responde correctamente a datos inválidos o recursos inexistentes.

Validación de POST/PUT:

Para cada endpoint POST y PUT (ej. institutions, users, services, support-tickets), envía peticiones con datos incompletos, incorrectos o con formatos erróneos (ej., email inválido, campo requerido vacío, slug duplicado si es único).

Resultado esperado: 422 Unprocessable Entity con un JSON detallando los errores de validación.

Recursos No Encontrados:

Intenta un GET, PUT o DELETE a un recurso con un ID que no existe (ej., GET /api/institutions/9999).

Resultado esperado: 404 Not Found.

3. Integración con el Backend Externo (API de Citas)
Esta sección describe la conexión con la API externa para la gestión de citas.

3.1. Cliente HTTP
Se utiliza el HTTP Client de Laravel (Guzzle) para realizar peticiones a la API externa.

Configuración: Las credenciales de la API externa (URL base, tokens de autenticación si son necesarios) deben configurarse en el archivo .env.

3.2. Endpoints Relevantes
Los controladores que interactúan con la API externa (probablemente AppointmentController para SuperAdmin y Admin, o un Service/Repository dedicado) serán los responsables de:

Enviar solicitudes de creación/actualización de citas.

Obtener el listado de citas.

Manejar la lógica de procesamiento/cancelación que la API externa requiera.

Manejo de Errores Externos: Se debe implementar un manejo robusto de errores para las respuestas de la API externa (ej., si la API externa devuelve un 4xx o 5xx, cómo lo maneja nuestro backend).

4. Documentación de la API (Generación Automática con Swagger/OpenAPI)
La documentación interactiva y el esquema de la API se generan automáticamente a partir de los comentarios en el código.

4.1. Herramienta Utilizada:
darkaonline/l5-swagger para Laravel, que implementa la especificación OpenAPI.

4.2. Proceso de Generación:
Anotaciones en el Código: Los desarrolladores deben añadir comentarios especiales (@OA\*) en los controladores (métodos), modelos (esquemas) y otros componentes de la API para describir:

URL del endpoint y método HTTP.

Parámetros de entrada (query, path, body).

Esquemas de los cuerpos de la petición y de la respuesta.

Tipos de seguridad (ej., Bearer Token).

Códigos de estado HTTP de respuesta y sus descripciones.

Generar Documentación: Ejecutar el comando php artisan l5-swagger:generate. Esto procesa las anotaciones y crea un archivo swagger.json (o swagger.yaml).

Acceso a la Documentación Interactiva: Una vez generada, la documentación puede ser accedida en el navegador en la siguiente URL:

http://127.0.0.1:8000/api/documentation (o la ruta configurada en config/l5-swagger.php).

4.3. Uso para el Frontend:
El equipo de frontend puede usar esta URL para explorar todos los endpoints de la API, entender sus parámetros, ver ejemplos de respuestas y probar las llamadas directamente desde la interfaz de Swagger UI.

El archivo swagger.json también puede ser importado en otras herramientas de desarrollo de API o para la generación automática de clientes.


## Documentacion de como se conetara el Frontend con el Backend
