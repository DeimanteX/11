Money Transfers
============================

Требования
------------

* PHP >= 5.4.0
* Apache 2.4
* Composer


Установка
------------
* git clone git@github.com:DeimanteX/transfers.git
* composer install --no-dev
* создать и добавить в конфиг параметры подключения к базе данных
* yii migrate


Настройка
-------------

### Database

Добавить парамерты в `config/db.php`, например:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```
Демо
-------------

http://transactions.atrap.ru
