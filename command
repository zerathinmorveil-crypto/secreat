php artisan db:seed --class=ServiceSeeder
composer require laravel/breeze --dev
php artisan breeze:install
composer require jeroennoten/laravel-adminlte
php artisan adminlte:install
composer require barryvdh/laravel-dompdf
php artisan make:controller Customer/OrderController  
php artisan make:seeder AdminSeeder 
php artisan db:seed --class=AdminSeeder 
php artisan make:controller Customer/HomeController
php artisan make:middleware RoleMiddleware 
