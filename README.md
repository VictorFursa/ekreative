Install
------------------------------------------
Run:
1. `git clone git@github.com:VictorFursa/ekreative.git`
2. `cd ekreative`
3. `composer install`
4. `php bin/console doctrine:database:create`
5. `php bin/console doctrine:schema:update --force`
6. `php bin/console server:run`

## Requirements

* PHP >= 7.0
* The PHP [cURL](http://php.net/manual/en/book.curl.php) extension
* The PHP [SimpleXML](http://php.net/manual/en/book.simplexml.php) extension
* The PHP [JSON](http://php.net/manual/en/book.json.php) extension
