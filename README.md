# Server monitoring for Laravel apps

[![Latest Version](https://img.shields.io/github/release/ericmakesstuff/laravel-server-monitor.svg?style=flat-square)](https://github.com/ericmakesstuff/laravel-server-monitor/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ericmakesstuff/laravel-server-monitor/master.svg?style=flat-square)](https://travis-ci.org/ericmakesstuff/laravel-server-monitor)
[![Quality Score](https://img.shields.io/scrutinizer/g/ericmakesstuff/laravel-server-monitor.svg?style=flat-square)](https://scrutinizer-ci.com/g/ericmakesstuff/laravel-server-monitor)
[![Total Downloads](https://img.shields.io/packagist/dt/ericmakesstuff/laravel-server-monitor.svg?style=flat-square)](https://packagist.org/packages/ericmakesstuff/laravel-server-monitor)

This Laravel 5 package will periodically monitor the health of your server and website. Currently, it provides healthy/alarm status notifications for Disk Usage, an HTTP Ping function to monitor the health of external services, and a validation/expiration monitor for SSL Certificates.

Once installed, monitoring your server is very easy. Just issue this artisan command:

``` bash
php artisan monitor:run
```

You can run only certain monitors at a time:

``` bash
php artisan monitor:run HttpPing
php artisan monitor:run SSLCertificate,DiskUsage
```

## How It Works

Using the configuration file in your project, any number of monitors can be configured to check for problems with your server setup.

When the `monitor:run` artisan command is executed, either from the command line or using the Laravel command scheduler, the monitors run and
alert if there is an issue. The alarm state is configurable, and alerts can be sent to the log, or via email, Pushover, and Slack.

##### Disk Usage Monitors

Disk usage monitors check the percentage of the storage space that is used on the given partition, and alert if the percentage exceeds the configurable alarm percentage.

##### HTTP Ping Monitors

HTTP Ping monitors perform a simple page request and alert if the HTTP status code is _not_ 200. They can optionally check that a certain phrase is included in the source of the page.

##### SSL Certificate Monitors

SSL Certificate monitors pull the SSL certificate for the configured URL and make sure it is valid for that URL. Wildcard and multi-domain certificates are supported.

The monitor will alert if the certificate is invalid or expired, and will also alert when the expiration date is approaching. The days on which to alert prior to expiration is also configurable.

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

## Monitor Configuration

After publishing the configuration file, you can edit the `'monitors'` section of app/config/server-monitor.php.

The default monitor configurations are:

```php
'monitors' => [
    /*
     * DiskUsage will alert when the free space on the device exceeds the alarmPercentage.
     * path is any valid file path, and the monitor will look at the usage of that disk partition.
     *
     * You may add as many DiskUsage monitors as you require.
     */
    'DiskUsage' => [
        [
            'path' => base_path(),
            'alarmPercentage' => 75,
        ],
    ],
    /*
     * HttpPing will perform an HTTP request to the configured URL and alert if the response code
     * is not 200, or if the optional checkPhrase is not found in the response.
     */
    'HttpPing' => [
        [
            'url' => 'http://www.example.com/',
        ],
        [
            'url' => 'http://www.example.com/',
            'checkPhrase' => 'Example Domain',
            'timeout' => 10,
            'allowRedirects' => false,
        ],
    ],
    /*
     * SSLCertificate will download the SSL Certificate for the URL and validate that the domain
     * is covered and that it is not expired. Additionally, it can warn when the certificate is
     * approaching expiration.
     */
    'SSLCertificate' => [
        [
            'url' => 'https://www.example.com/',
        ],
        [
            'url' => 'https://www.example.com/',
            'alarmDaysBeforeExpiration' => [14, 7],
        ],
    ],
```

## Alert Configuration

Alerts can be logged to the default log handler, or sent via email, Pushover, or Slack. Allowed values are `log`, `mail`, `pushover`, and `slack`.

The default alert configurations are:

```php
'events' => [
    'whenDiskUsageHealthy'       => ['log'],
    'whenDiskUsageAlarm'         => ['log', 'mail'],
    'whenHttpPingUp'             => ['log'],
    'whenHttpPingDown'           => ['log', 'mail'],
    'whenSSLCertificateValid'    => ['log'],
    'whenSSLCertificateInvalid'  => ['log', 'mail'],
    'whenSSLCertificateExpiring' => ['log', 'mail'],
],
```

## Scheduling

After you have performed the basic installation you can start using the monitor:run command. In most cases you'll want to schedule this command so you don't have to manually run monitor:run every time you want to know the health of your server.

The commands can, like an other command, be scheduled in Laravel's console kernel.

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
   $schedule->command('monitor:run')->daily()->at('10:00');
   $schedule->command('monitor:run HttpPing')->hourly();
}
```

Of course, the schedules used in the code above are just an example. Adjust them to your own preferences.

## Testing

Run the tests with:

``` bash
vendor/bin/phpunit
```

## Next Steps

More monitoring metrics. Feel free to submit ideas via issues or pull requests!

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email eric@ericmakesstuff.com instead of using the issue tracker.

## Credits

- [Eric Blount](https://github.com/ericmakesstuff) - Author
- [Freek Van der Herten](https://github.com/freekmurze) - Inspiration/Base Package ([Backup](https://github.com/spatie/laravel-backup))

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
