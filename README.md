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
## Documentacion de como se conetara el Frontend con el Backend
