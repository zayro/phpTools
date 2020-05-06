# PHP Tools

## SYNOPSIS
Is an open source project that allows creating multi-platform connections with php language code, allowing easy requests and jwt or session access.

## INSTALLATION
- instalar componentes de composer 

```shell
composer install
```

- actualizar composer

```shell
composer self-update
```

-- cargamos la libreria de composer

```shell
composer dump-autoload --optimize 
```

- remover componentes de composer 

```shell
composer remove "nombre" 
composer global remove phpunit/phpunit 
```

#### CONFIGURAR COMPOSER.JSON

- configurar la ruta donde va a instalar los arhivos de composer

```json
    "config": {
        "vendor-dir": "back/vendor"
    }
```

## Development tools:

### puede ejecutar el comando en el directorio vendor/bin

- instalar documentacion de php

```shell
phpdoc template:list

phpdoc -d ./test/pdo/pdo_database.class_manual.php -t ./build/docs/library --template="responsive"
phpdoc -d library  -d app -t build/docs/ --template="responsive"


phpdoc -d ../../library -t ../../library/build/docs/library
phpdoc -d ../../back/library -t ../../back/library/build/docs/ --template="responsive"

phpdoc -d ../../back/library -f ../../back/library/pdo.class  -t ../../back/library/build/docs/ --template="responsive" --ignore "testing/*"
```

### Code indentation

```bash

php-cs-fixer fix back/app/model/admin.model.php --rules=@PSR2,@Symfony --using-cache=no --show-progress=evaluating


php-cs-fixer fix ./library --rules=@PSR2,@Symfony --using-cache=no --show-progress=evaluating

```
