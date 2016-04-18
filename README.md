# Laravel Users Online

[![Latest Stable Version](https://poser.pugx.org/highideas/laravel-users-online/v/stable)](https://packagist.org/packages/highideas/laravel-users-online) [![Total Downloads](https://poser.pugx.org/highideas/laravel-users-online/downloads)](https://packagist.org/packages/highideas/laravel-users-online) [![Latest Unstable Version](https://poser.pugx.org/highideas/laravel-users-online/v/unstable)](https://packagist.org/packages/highideas/laravel-users-online) [![License](https://poser.pugx.org/highideas/laravel-users-online/license)](https://packagist.org/packages/highideas/laravel-users-online)

## Instalation

Add the new required package in your composer.json

```
"highideas\laravel-users-online": "^1.0"
```
Run `composer update` or `php composer.phar update`.

Or install directly via composer

```
composer require highideas/laravel-users-online
```

After composer command, add new service provider in `config/app.php` :

```php
'HighIdeas\UsersOnline\UsersOnlineServiceProvider::class',
'HighIdeas\UsersOnline\Providers\UsersOnlineEventServiceProvider::class',
```

And add new middleware in `app/Http/Kernel.php` :

```php
'HighIdeas\UsersOnline\Middlewares\UsersOnline:class',
```

After this, add the trait in your model User in `app/User.php`:

```php

class User extends Authenticatable
{
    use \HighIdeas\UsersOnline\Models\UsersOnline;
...

```


Finally run `php artisan vendor:publish` for add the namespaces

Go to `http://myapp/users-online` to view the users online
