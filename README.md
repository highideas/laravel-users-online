# Laravel Users Online

[![Latest Stable Version](https://poser.pugx.org/highideas/laravel-users-online/v/stable)](https://packagist.org/packages/highideas/laravel-users-online) 
[![Total Downloads](https://poser.pugx.org/highideas/laravel-users-online/downloads)](https://packagist.org/packages/highideas/laravel-users-online) 
[![License](https://poser.pugx.org/highideas/laravel-users-online/license)](https://packagist.org/packages/highideas/laravel-users-online)
[![Build Status](https://travis-ci.org/highideas/laravel-users-online.svg?branch=master)](https://travis-ci.org/highideas/laravel-users-online)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/22e4eb8b71e14c24adccd8edbbd45682)](https://www.codacy.com/app/HighIdeas/laravel-users-online)
[![Codacy Badge](https://api.codacy.com/project/badge/coverage/22e4eb8b71e14c24adccd8edbbd45682)](https://www.codacy.com/app/HighIdeas/laravel-users-online)

## Instalation

Add the new required package in your composer.json

```
"highideas/laravel-users-online": "^1.0"
```
Run `composer update` or `php composer.phar update`.

Or install directly via composer

```
composer require highideas/laravel-users-online
```

After composer command, add new service provider in `config/app.php` :

```php
HighIdeas\UsersOnline\UsersOnlineServiceProvider::class,
HighIdeas\UsersOnline\Providers\UsersOnlineEventServiceProvider::class,
```

And add new middleware in `app/Http/Kernel.php` :

```php
\HighIdeas\UsersOnline\Middleware\UsersOnline::class,
```

After this, add the trait in your model User in `app/User.php`:

```php

class User extends Authenticatable
{
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
...

```

For show the users online just use the method `isOnline()`:

```php

$user->isOnline();

// Or

$users = User::all();

foreach ($users as $user) {

    if ($user->isOnline()) {
        // show the user
    }
}

```


Finally run `php artisan vendor:publish` for add the namespaces

