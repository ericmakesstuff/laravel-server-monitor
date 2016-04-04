<?php

return [

    'server' => [

        /*
         * The name of this server. This is used to alert which server is being monitored.
         */
        'name' => env('SERVER_NAME', 'Production'),
    ],

    /*
     *  In this array you can specify which monitors will run.
     */
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
    ],

    'notifications' => [

        /*
         * This class will be used to send all notifications.
         */
        'handler' => EricMakesStuff\ServerMonitor\Notifications\Notifier::class,

        /*
         * Here you can specify the ways you want to be notified when certain
         * events take place. Possible values are "log", "mail", "slack" and "pushover".
         *
         * Slack requires the installation of the maknz/slack package.
         */
        'events' => [
            'whenDiskUsageHealthy'       => ['log'],
            'whenDiskUsageAlarm'         => ['log', 'mail'],
            'whenHttpPingUp'             => ['log'],
            'whenHttpPingDown'           => ['log', 'mail'],
            'whenSSLCertificateValid'    => ['log'],
            'whenSSLCertificateInvalid'  => ['log', 'mail'],
            'whenSSLCertificateExpiring' => ['log', 'mail'],
        ],

        /*
         * Here you can specify how emails should be sent.
         */
        'mail' => [
            'from' => 'your@email.com',
            'to'   => 'your@email.com',
        ],

        /*
         * Here you can specify how messages should be sent to Slack.
         */
        'slack' => [
            'channel'  => '#servers',
            'username' => 'Server Monitor',
            'icon'     => ':robot:',
        ],

        /*
         * Here you can specify how messages should be sent to Pushover.
         */
        'pushover' => [
            'token' => env('PUSHOVER_APP_TOKEN'),
            'user'  => env('PUSHOVER_USER_KEY'),
        ],
    ]
];
