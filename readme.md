# API - ORM PHP

## COMANDOS PARA DE FUNCIONAMIENTO PHP

- instalar componentes de composer 

```shell
composer install
```

- actualizar composer

```shell
composer self-update
```

- instalar paquetes adicionales a composer

```shell
composer require "nombre" --dev
```

- cargamos la libreria de composer

```shell
composer dump-autoload --optimize 
```

- remover componentes de composer 

```shell
composer remove "nombre" 
composer global remove phpunit/phpunit 
```
## Manual de uso:

#### Fetching Row:
This method always returns only 1 row.
```php
<?php
$ages     =  $db->row("SELECT * FROM Persons WHERE  id = :id", array("id"=>"1"));
```
##### Result
| id | firstname | lastname | sex | age
|:-----------:|:------------:|:------------:|:------------:|:------------:|
| 1       |        John |     Doe    | M | 19

#### Fetching Single Value:
This method returns only one single value of a record.
```php
<?php
// Fetch one single value
$db->bind("id","3");
$firstname = $db->single("SELECT firstname FROM Persons WHERE id = :id");
```
##### Result
|firstname
|:------------:
| Zoe

## CONFIGURAR COMPOSER.JSON

- configurar la ruta donde va a instalar los arhivos de composer

    "config": {
        "vendor-dir": "back/vendor"
    },


### puede ejecutar el comando en el directorio vendor/bin

- instalar documentacion de php

```shell
phpdoc template:list

phpdoc -d ../../app/model -t ../../build/docs/library
phpdoc -d library  -d app -t build/docs/ --template="responsive"


phpdoc -d ../../library -t ../../library/build/docs/library
phpdoc -d ../../back/library -t ../../back/library/build/docs/ --template="responsive"

phpdoc -d ../../back/library -f ../../back/library/pdo.class  -t ../../back/library/build/docs/ --template="responsive" --ignore "testing/*"
```

### indentacion codigo php

```shell

php-cs-fixer fix back/app/model/admin.model.php --rules=@PSR2,@Symfony--using-cache=no --show-progress=evaluating


php-cs-fixer fix ../../app --rules=@PSR2,@Symfony --using-cache=no --show-progress=evaluating

```
