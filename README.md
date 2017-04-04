## YAZ Query Builder ##

### Installation Laravel ###

#### Dependencies
[medlib/marcxml](https://github.com/medlib-v2/marcxml)

You can install this package by using [Composer](http://getcomposer.org), running this command:
```bash
    composer require medlib/yaz
```

The next required step is to add the service provider to config/app.php :
```php
'providers' => [
    ...
    Medlib\Yaz\Providers\YazServiceProvider::class,
    Medlib\MarcXML\Providers\ParserServiceProvider::class,
]
```

```php
'aliases' => [
    ...
    'Yaz' => Medlib\Yaz\Facades\Yaz::class,
    'Query' => Medlib\Yaz\Facades\Query::class,
    'MarcXML' => Medlib\MarcXML\Facades\MarcXML::class,
]
```

### Publish ###

The last required step is to publish views and assets in your application with :
```bash
    php artisan vendor:publish
```

If you get the error
```bash
    Nothing to publish for tag []!
```

Then run this command :

```bash
    php artisan config:clear
```


Congratulations, you have successfully installed Yaz Query Builder !

