# Server monitoring for Laravel apps

[![Latest Version](https://img.shields.io/github/release/ericmakesstuff/laravel-server-monitor.svg?style=flat-square)](https://github.com/ericmakesstuff/laravel-server-monitor/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ericmakesstuff/laravel-server-monitor/master.svg?style=flat-square)](https://travis-ci.org/ericmakesstuff/laravel-server-monitor)
[![Quality Score](https://img.shields.io/scrutinizer/g/ericmakesstuff/laravel-server-monitor.svg?style=flat-square)](https://scrutinizer-ci.com/g/ericmakesstuff/laravel-server-monitor)
[![Total Downloads](https://img.shields.io/packagist/dt/ericmakesstuff/laravel-server-monitor.svg?style=flat-square)](https://packagist.org/packages/ericmakesstuff/laravel-server-monitor)

This Laravel 5 package will periodically monitor the health of your server. Currently, it provides healthy/alarm status notifications for Disk Usage.

Once installed, monitoring your server is very easy. Just issue this artisan command:

``` bash
php artisan monitor:run
```

## Installation and usage

You can install this package via composer using:

`composer require ericmakesstuff/laravel-server-monitor`

You'll need to register the ServiceProvider:

```php
// config/app.php

'providers' => [
    // ...
    EricMakesStuff\ServerMonitor\ServerMonitorServiceProvider::class,
];
```

To publish the config file to app/config/server-monitor.php run:

`php artisan vendor:publish --provider="EricMakesStuff\ServerMonitor\ServerMonitorServiceProvider"`

## Scheduling

After you have performed the basic installation you can start using the monitor:run command. In most cases you'll want to schedule this command so you don't have to manually run monitor:run every time you want to know the health of your server.

The commands can, like an other command, be scheduled in Laravel's console kernel.

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
   $schedule->command('monitor:run')->daily()->at('10:00');
}
```

Of course, the hour used in the code above is just an example. Adjust it to your own preferences.

## Testing

Run the tests with:

``` bash
vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email eric@ericmakesstuff.com instead of using the issue tracker.

## Credits

- [Eric Blount](https://github.com/ericmakesstuff)
- [Freek Van der Herten](https://github.com/freekmurze)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
