# challenge-2024-12-27

## Problem
![image](https://github.com/user-attachments/assets/0ae39c54-c3b8-47fd-a839-9ab3af0c5fa7)

## Prerequisites

As this is a public repo for a tech challenge as solutions architect please build the images in your local env with:

```bash
make build-base
```

## How to Run

### Mac & Linux Running

```bash
make run
```

### Windows Running

```bash
docker-compose -p challenge-rodolfoquendo-2024-12-27 -f docker/dev.yml up -d --force-recreate --remove-orphans
 ```

## For running the test suite

### Mac & Linux Testing

```bash
make test
```

### Windows Testing

```bash
docker-compose -p challenge-rodolfoquendo-2024-12-27 -f docker/test.yml up -d --force-recreate  --remove-orphans &&
docker exec -t php-test composer install && 
docker exec -t php-test php artisan migrate --seed &&
docker exec -t php-test bash -c "./vendor/bin/phpunit --testdox --do-not-cache-result --configuration phpunit.xml --coverage-html '/platform/public/__TEST__'" && 
open src/public/__TEST__/index.html
```

## For opening coverage

### Mac & Linux

```bash
make coverage
```

### Windows

```bash
open src/public/__TEST__/index.html
```
