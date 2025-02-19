# Commission generator


Run application:

- Create .env file with API KEY for rate service.

- Run composer
```
composer install
```
- Execute entry

```
php index.php input.txt
```

---


Run tests
```
vendor/bin/phpunit
```


---

## Example input.txt file
```
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
```

## Running the code
```
> php app.php input.txt
1
0.46
1.65
```