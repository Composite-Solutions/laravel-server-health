# Composite Server Health Monitor wrapper
## Laravel Server & App Health Monitor and Notifier

## Install

You can install the package via [Composer](https://getcomposer.org/)
```bash
composer require composite/server-health
```

In Laravel 5.5 or above the service provider will automatically get registered. In older versions of the framework just add the service provider in `config/app.php` file:
```php
'providers' => [
    ...
    /*
     * Package Service Providers...
     */
    Composite\ServerHealth\HealthServiceProvider::class,
    ...
],
```

## Usage

- your_domain.com/healthservice
