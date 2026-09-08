Hrsaas Service
======================

TL;DR
-----
HR data synchronization service via RabbitMQ for Laravel.

Install
-------

Install via composer

```
# TODO: 上线前改为 daotalent-distributor/hrsaas-service-single
composer require lvtiansong-collab/hrsaas-service-single
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
