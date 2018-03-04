Install
------------------------------------------
Run:
1. `composer install`
2. `php bin/console doctrine:database:create`
3. `php bin/console doctrine:schema:update --force`
4. `php bin/console server:run`

## Requirements

* PHP >= 5.4
* The PHP [cURL](http://php.net/manual/en/book.curl.php) extension
* The PHP [SimpleXML](http://php.net/manual/en/book.simplexml.php) extension
* The PHP [JSON](http://php.net/manual/en/book.json.php) extension
