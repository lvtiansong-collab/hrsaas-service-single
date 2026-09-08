Hrsaas Service
======================

TL;DR
-----
HR data synchronization service via RabbitMQ for Laravel.

Install
-------

Install via composer

```
composer require daotalent-distributor/hrsaas-service-single
```

Generate config file

```
php artisan hrsaas:config
```

Generate migrations

```
php artisan hrsaas:make
```

Start queue

```
php artisan hrsaas:work
```

Usage
-----

```
php artisan hrsaas:work
```
