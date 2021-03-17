# Laravel Legends Interactive Model

This is a Laravel package to help you to record data in the Eloquent models interactively via the command line.

Example:

```bash
php artisan ll:interactive-model User
```
or

```bash
php artisan ll:interactive-model App\\Models\\User
```

```text
Type the "name" value:
> Wallace Maxters

Type the "email" value:
> wallacemaxters@test.com

Type the "password" value:
> [automatic hidden]

Data inserted in table users
```

This command assume that your models is placed in `App\Models` namespace by default, if you not passed full namespace as argument.

The data asked to fill should be defined in `$fillable` in your model. For fields placed in `$hidden`, the prompt is automatically hidden.

Example:

```php

namespace App\Models;

class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];
}
```