# Laravel API auth
Project using Laravel Passport.

## Get started
### Install composer
```
$ composer install
$ cp .env.example .env
```

### Configure your .env file
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_name
DB_USERNAME=db_username
DB_PASSWORD=db_password
```

### Final steps
```
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed
$ php artisan passport:install
```

## API endpoints
```
1. Authorization
1 ) Register -> /api/register
2 ) Login -> /api/login
3 ) Logout -> /api/logout

2. Forgot password
1 ) Create link -> /api/create
2 ) Find token -> /api/find/{token}
3 ) Reset password -> /api/reset
```
