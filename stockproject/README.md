- Instructions
- composer install
- connect your database => 
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=stockproject
DB_USERNAME=root
DB_PASSWORD=yourpassword

- php artisan migrate
- php artisan serve