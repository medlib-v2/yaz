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

### Examples ###
  * Request with a return type of `BibliographicRecord` (all records):
  
        use Medlib\Yaz\Query\YazQuery;
        
        $this->results = YazQuery::create()
        ->from('SUDOC')
        ->where('au="totok" and ti="Handbuch"')
        ->orderBy('au ASC')
        ->all();
  
  
  * Request with a return type of `BibliographicRecord` (one record):
  
        use Medlib\Yaz\Query\YazQuery;
        
        $this->results = YazQuery::create()
        ->from('SUDOC')
        ->where('au="totok" and ti="Handbuch"')
        ->first();
  * Request with a return type of `BibliographicRecord` (limit):
  
        use Medlib\Yaz\Query\YazQuery;
        
        $this->results = YazQuery::create()
        ->from('BNF')
        ->where('au="totok" and ti="Handbuch"')
        ->limit(0,10)
        ->all();
  
  * Request with a return type of `string` (all records):
  
        use Medlib\Yaz\Query\YazQuery;
        use Medlib\Yaz\Record\YazRecords;
        
        $this->results = YazQuery::create()
        ->from('BNF')
        ->where('au="totok" and ti="Handbuch"')
        ->orderBy('au ASC')
        ->all(YazRecords::TYPE_STRING);

  * Request with a return type of `xml` equal to `BibliographicRecord` (all records):

        use Medlib\Yaz\Query\YazQuery;
        use Medlib\Yaz\Record\YazRecords;
        
        $this->results = YazQuery::create()
        ->from('connection_name')
        ->where('au="totok" and ti="Handbuch"')
        ->orderBy('au ASC')
        ->all(YazRecords::TYPE_XML);

#### Allowed parameters

 ##### Type:
    - TYPE_STRING
    - TYPE_RAW
    - TYPE_XML 
    - TYPE_SYNTAX 
    - TYPE_ARRAY

Order           | Description 
--------------- | -------------
ASC             | Sort ascending
IASC            | Sort ascending, Case insensitive sorting
SASC            | Sort ascending, Case sensitive sorting
DESC            | Sort descending
IDESC           | Sort descending, Case insensitive sorting
SDESC           | Sort descending, Case sensitive sorting

### Pagination ###


  * Action:
  
        use Medlib\Yaz\Pagination\Paginator;
        use Medlib\Yaz\Record\YazRecords;
        
        $pagination = new Paginator('BNF', 10);

        $pagination->getQuery()
        ->where('au="totok"')
        ->orderBy('ti ASC');

        $pagination->setPage($request->get('page', 1));

        $pagination->render();


  * Template:
  
          Count: echo $pagination->getNbResults();
          foreach($pagination->getResults(YazRecords::TYPE_STRING) AS $result):
                echo $result;
          endforeach;

### Required ###

- [YAZ Client 4|5](http://www.indexdata.dk/yaz/)
- [PHP YAZ](http://pecl.php.net/package/yaz)

Congratulations, you have successfully installed Yaz Query Builder !

